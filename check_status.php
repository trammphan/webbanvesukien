<?php
// Kết nối CSDL
$conn = new mysqli('localhost', 'root', '', 'qlysukien');

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn các giá trị TrangThai duy nhất trong bảng thanhtoan
$sql = "SELECT DISTINCT TrangThai FROM thanhtoan";
$result = $conn->query($sql);

echo "<h2>Các giá trị TrangThai trong bảng thanhtoan:</h2>";
if ($result->num_rows > 0) {
    echo "<ul>";
    while($row = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row['TrangThai']) . "</li>";
    }
    echo "</ul>";
} else {
    echo "Không tìm thấy dữ liệu nào.";
}

$conn->close();
?>
