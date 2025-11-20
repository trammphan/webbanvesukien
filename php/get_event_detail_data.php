<?php
// Cấu hình CSDL (sử dụng lại từ các tệp khác)
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "qlysukien";

header('Content-Type: application/json');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Kết nối CSDL thất bại: ' . $conn->connect_error]);
    exit;
}

$conn->set_charset("utf8mb4");

// Lấy tham số MaSK và days
$maSK = isset($_GET['MaSK']) ? $_GET['MaSK'] : null;
$days = isset($_GET['days']) ? (int)$_GET['days'] : 30; // Mặc định 30 ngày

if (!$maSK) {
    echo json_encode(['success' => false, 'error' => 'Thiếu mã sự kiện (maSK).']);
    exit;
}

// Lấy ngày bắt đầu ($days ngày trước)
$startDate = date('Y-m-d', strtotime("-{$days} days"));

// Truy vấn SQL: Tính TỔNG doanh thu và số vé THEO NGÀY cho SỰ KIỆN CỤ THỂ
$sql = "
    SELECT
        DATE(TT.NgayTao) AS Ngay,
        COALESCE(SUM(LV.Gia), 0) AS TongDoanhThu,
        COALESCE(COUNT(V.MaVe), 0) AS TongSoVe
    FROM
        thanhtoan TT
    JOIN
        ve V ON TT.MaTT = V.MaTT
    JOIN
        loaive LV ON V.MaLoai = LV.MaLoai
    WHERE
        V.TrangThai = 'Đã bán' AND V.MaSK = ? AND DATE(TT.NgayTao) >= ?
    GROUP BY
        Ngay
    ORDER BY
        Ngay ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $maSK, $startDate);
$stmt->execute();
$result = $stmt->get_result();

$chartData = [];
$labels = [];

// Khởi tạo tất cả các ngày trong phạm vi $days để tránh thiếu dữ liệu
for ($i = $days - 1; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-{$i} days"));
    $labels[$date] = [
        'TongDoanhThu' => 0,
        'TongSoVe' => 0
    ];
}

// Chèn dữ liệu thực tế vào mảng labels
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $labels[$row['Ngay']] = [
            'TongDoanhThu' => (float)$row['TongDoanhThu'],
            'TongSoVe' => (int)$row['TongSoVe']
        ];
    }
}

// Định dạng dữ liệu cuối cùng để gửi cho Chart.js
foreach ($labels as $ngay => $data) {
    $chartData[] = [
        'Ngay' => date('d/m', strtotime($ngay)), // Hiển thị ngày/tháng
        'TongDoanhThu' => $data['TongDoanhThu'],
        'TongSoVe' => $data['TongSoVe']
    ];
}

$response = [
    'success' => true,
    'data' => $chartData
];

echo json_encode($response);
$conn->close();
?>