<?php
// Cấu hình CSDL (Sử dụng cấu hình từ admin.php)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlysukien";

// Thiết lập header
header('Content-Type: text/html; charset=utf-8');

// 1. Lấy MaLoai từ AJAX request
$maLoai = isset($_GET['maloai']) ? $_GET['maloai'] : null;

if (!$maLoai) {
    echo '<p style="color: red;">Lỗi: Không tìm thấy Mã Loại Sự Kiện.</p>';
    exit;
}

// 2. Kết nối CSDL
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    echo '<p style="color: red;">Lỗi kết nối CSDL: ' . $conn->connect_error . '</p>';
    exit;
}

// Thiết lập encoding (quan trọng cho tiếng Việt)
$conn->set_charset("utf8mb4");

// 3. Chuẩn bị truy vấn SQL an toàn (Prepared Statement)
// Giả định trường liên kết giữa loaisk và sukien là MaLSK
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

$stmt->bind_param("s", $maLoai); // 's' cho string/char
$stmt->execute();
$result = $stmt->get_result();

// 4. Xử lý kết quả và tạo HTML
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

// 5. Đóng kết nối và in kết quả
$stmt->close();
$conn->close();

echo $html_output;
?>