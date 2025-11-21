<?php
require_once 'config.php';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
$conn->set_charset("utf8");
    
// Check connection
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>