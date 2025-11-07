<?php
// Tệp: /webbanvesukien/connect_1.php (ĐÃ SỬA)

// 1. Gọi "két sắt" config.php
// (Giả sử thư mục gốc của bạn là /webbanvesukien)
require_once 'config.php';

// 2. Sử dụng các biến $db_... từ config.php
// (Các biến $servername, $username... cũ đã bị xóa)
    
// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
$conn->set_charset("utf8");
    
// Check connection
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>