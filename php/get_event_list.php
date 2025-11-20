<?php
// Tái sử dụng cấu hình CSDL từ admin.php
$servername = "localhost";
$username = "root";
$password = ""; // Thay bằng mật khẩu CSDL của bạn nếu có
$dbname = "qlysukien";

// Thiết lập header để trả về JSON
header('Content-Type: application/json');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    // Trả về lỗi nếu kết nối CSDL thất bại
    echo json_encode(['success' => false, 'error' => 'Kết nối CSDL thất bại: ' . $conn->connect_error]);
    exit;
}

$conn->set_charset("utf8mb4");

// Lấy tham số số ngày. Tham số này dùng để lọc sự kiện nếu cần (ví dụ: sự kiện trong 30 ngày)
// Dựa trên snippet admin.js của bạn, tôi giữ lại biến $days
$days = isset($_GET['days']) ? (int)$_GET['days'] : 30; // Mặc định 30 ngày

// Lấy ngày hiện tại
$currentDate = date('Y-m-d'); 

// Tùy chọn 1: Lấy TẤT CẢ sự kiện (đơn giản nhất)
$sql = "SELECT MaSK, TenSK FROM sukien ORDER BY TenSK ASC";

/*
// Tùy chọn 2: Lấy sự kiện đã/đang/sắp diễn ra trong X ngày
$startDateFilter = date('Y-m-d', strtotime("-{$days} days"));
$sql = "SELECT MaSK, TenSK 
        FROM sukien 
        WHERE NgayBatDau >= '{$startDateFilter}' OR NgayKetThuc >= '{$currentDate}'
        ORDER BY TenSK ASC";
*/

$result = $conn->query($sql);

$events = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $events[] = [
            'MaSK' => $row['MaSK'], 
            'TenSK' => $row['TenSK']
        ];
    }
}

// Trả về mảng sự kiện dưới dạng JSON
$response = [
    'success' => true,
    'events' => $events
];

echo json_encode($response);
$conn->close();
?>