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

// Truy vấn SQL: Tính tổng doanh thu theo sự kiện
$sql = "
    SELECT
                sk.MaSK, sk.TenSK, 
                COALESCE(COUNT(v.MaVe), 0) AS TongSoVeBan, 
                COALESCE(SUM(lv.Gia), 0) AS TongDoanhThu
            FROM
                sukien sk
            LEFT JOIN
                loaive lv ON sk.MaSK = lv.MaSK
            LEFT JOIN
                ve v ON lv.MaLoai = v.MaLoai
            LEFT JOIN
                thanhtoan tt ON v.MaTT = tt.MaTT
                WHERE
            v.TrangThai = 'đã bán'  
            GROUP BY
                sk.MaSK
            ORDER BY
                TongDoanhThu DESC;
";

$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = [
            'label' => $row['TenSK'],
            'value' => (float)$row['TongDoanhThu']
        ];
    }
}

$conn->close();

// Trả về dữ liệu dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($data);
?>