<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlysukien";

header('Content-Type: text/html; charset=utf-8');

$maLoai = isset($_GET['maloai']) ? $_GET['maloai'] : null;

if (!$maLoai) {
    echo '<p style="color: red;">Lỗi: Không tìm thấy Mã Loại Sự Kiện.</p>';
    exit;
}

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    echo '<p style="color: red;">Lỗi kết nối CSDL: ' . $conn->connect_error . '</p>';
    exit;
}


$conn->set_charset("utf8mb4");

$sql = "SELECT MaSK, TenSK, TGian, luot_truycap
        FROM sukien 
        WHERE MaLSK = ? 
        ORDER BY TGian DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo '<p style="color: red;">Lỗi chuẩn bị truy vấn SQL: ' . $conn->error . '</p>';
    $conn->close();
    exit;
}

$stmt->bind_param("s", $maLoai); 
$stmt->execute();
$result = $stmt->get_result();

$html_output = '';

if ($result->num_rows > 0) {
    $html_output .= '
    <div class="table-responsive">
        <table class="table table-bordered table-sm table-striped">
            <thead>
                <tr>
                    <th class="tieudeqlve">Mã SK</th>
                    <th class="tieudeqlve">Tên Sự Kiện</th>
                    <th class="tieudeqlve">Thời Gian</th>
                    <th class="tieudeqlve">Lượt Truy Cập</th>
                </tr>
            </thead>
            <tbody>';
                while ($row = $result->fetch_assoc()) {
                    $timestamp = strtotime($row['TGian']);
                    $formatted_time = date('d/m/Y H:i', $timestamp);
                    
                    $html_output .= '
                            <tr>
                                <td class="ndsk">' . htmlspecialchars($row['MaSK']) . '</td>
                                <td class="ndsk">' . htmlspecialchars($row['TenSK']) . '</td>
                                <td class="ndsk">' . $formatted_time . '</td>
                                <td class="ndsk">' . number_format($row['luot_truycap'] ?? 0) . '</td>
                            </tr>';
                }
                
                $html_output .= '
            </tbody>
        </table>
    </div>';
    
} else {
    $html_output = '<p>Không có sự kiện nào thuộc danh mục này.</p>';
}


$stmt->close();
$conn->close();

echo $html_output;
?>