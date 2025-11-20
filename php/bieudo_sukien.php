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
    // Trả về lỗi JSON
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Kết nối CSDL thất bại: ' . $conn->connect_error]);
    exit;
}

// Thiết lập mã hóa UTF-8
$conn->set_charset("utf8mb4");

// Truy vấn SQL: Đếm số lượng sự kiện theo loại
$sql = "
    SELECT
        lsk.TenLoaiSK,
        COUNT(sk.MaSK) AS SoLuongSuKien
    FROM
        loaisk lsk
    LEFT JOIN
        sukien sk ON lsk.MaloaiSK = sk.MaLSK
    GROUP BY
        lsk.TenLoaiSK
    ORDER BY
        SoLuongSuKien DESC;
";

$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = [
            'label' => $row['TenLoaiSK'],
            'value' => (int)$row['SoLuongSuKien']
        ];
    }
}

$conn->close();

// Trả về dữ liệu dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($data);
?>