<?php
// Tái sử dụng cấu hình CSDL từ admin.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlysukien";

// Thiết lập header để trả về JSON
header('Content-Type: application/json');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Kết nối CSDL thất bại: ' . $conn->connect_error]);
    exit;
}

$conn->set_charset("utf8mb4");

// Truy vấn tên sự kiện và số lượt truy cập (Top 10)
$sql = "SELECT MaSK, luot_truycap FROM sukien ORDER BY luot_truycap DESC LIMIT 10"; 
$result = $conn->query($sql);

$data = []; // KHỞI TẠO MẢNG DỮ LIỆU ĐƠN GIẢN
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = [
            'label' => $row['MaSK'],    // Tên sự kiện
            'value' => (int)$row['luot_truycap'] // Số lượt truy cập
        ];
    }
}
// TRẢ VỀ CẤU TRÚC ĐƠN GIẢN
$response = [
    'success' => true,
    'data' => $data // PHẢI LÀ 'data'
];

echo json_encode($response);
$conn->close();
?>