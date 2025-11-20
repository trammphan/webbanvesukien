<?php
// Cấu hình CSDL (Lấy từ admin.php)
$servername = "localhost";
$username = "root";
$password = ""; // Thay bằng mật khẩu CSDL của bạn
$dbname = "qlysukien";

// Kết nối CSDL
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Kết nối CSDL thất bại: ' . $conn->connect_error]);
    exit;
}

$conn->set_charset("utf8mb4");

// Lấy tham số số ngày từ request. Mặc định là 10 ngày.
$days = isset($_GET['days']) ? (int)$_GET['days'] : 10; 
if ($days != 10 && $days != 30) {
    $days = 10; // Đảm bảo chỉ nhận 10 hoặc 30
}

// Lấy ngày bắt đầu ($days ngày trước)
$startDate = date('Y-m-d', strtotime("-{$days} days"));

// Truy vấn SQL: Tính TỔNG doanh thu và số vé THEO NGÀY (TỔNG HỢP)
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
        V.TrangThai = 'đã bán' AND DATE(TT.NgayTao) >= '{$startDate}'
    GROUP BY
        Ngay
    ORDER BY
        Ngay ASC;
";

$result = $conn->query($sql);
$labels = [];
$chartData = [];

// KHỞI TẠO MẢNG 10 NGÀY (Date Padding)
for ($i = $days - 1; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-{$i} days"));
    $labels[$date] = [
        'TongDoanhThu' => 0,
        'TongSoVe' => 0
    ];
}
$data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = [
            'Ngay' => $row['Ngay'],
            'TongDoanhThu' => (float)$row['TongDoanhThu'],
            'TongSoVe' => (int)$row['TongSoVe']
        ];
    }
}
foreach ($labels as $ngay => $data) {
    $chartData[] = [
        'Ngay' => date('d/m', strtotime($ngay)), // Chỉ hiển thị ngày/tháng
        'TongDoanhThu' => $data['TongDoanhThu'],
        'TongSoVe' => $data['TongSoVe']
    ];
}
$response = [
    'success' => true,
    'data' => $chartData
];
// Trả về dữ liệu dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>