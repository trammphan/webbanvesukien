<?php
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'qlysukien';
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8");
    
    // Check connection
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }
?>