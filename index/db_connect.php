<?php
$servername = "localhost";
$username = "root"; // Thay đổi nếu khác
$password = "";     // Thay đổi nếu khác
$dbname = "qlysukien";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thiết lập charset để hiển thị tiếng Việt
$conn->set_charset("utf8mb4");
?>