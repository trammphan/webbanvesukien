<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlysukien";

// Thiết lập tiêu đề để trả về dữ liệu JSON
header('Content-Type: application/json');

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);
// Kiểm tra kết nối
if ($conn->connect_error) {
    echo json_encode(['exists' => false, 'error' => 'Database connection failed']);
    exit();
}

// Lấy email từ tham số GET và làm sạch
$email = trim(strtolower($_GET["email"] ?? ''));

// Nếu email rỗng, trả về false
if (empty($email)) {
    echo json_encode(['exists' => false]);
    $conn->close();
    exit();
}

// *** CÂU LỆNH SQL KIỂM TRA TRÊN NHIỀU BẢNG BẰNG UNION ***
// Sử dụng Prepared Statement và UNION để kiểm tra:
// 1. khachhang (Bắt buộc)
// 2. nhatochuc (Ví dụ)
// 3. nhanviensoatve (Ví dụ)
// Chỉ cần SELECT 1 cột email từ mỗi bảng.
$sql = "
    SELECT email FROM khachhang WHERE email = ?
    UNION
    SELECT email FROM nhatochuc WHERE email = ?
    UNION
    SELECT email FROM nhanviensoatve WHERE email = ?
";

// Chuẩn bị câu lệnh
$stmt_check = $conn->prepare($sql);

if ($stmt_check) {
    // Liên kết 3 tham số (đều là email)
    // "sss" nghĩa là 3 tham số đều là kiểu string
    $stmt_check->bind_param("sss", $email, $email, $email); 
    
    $stmt_check->execute();
    $stmt_check->store_result();

    // Nếu số hàng trả về > 0, email đã tồn tại trong ít nhất một bảng
    $email_exists = $stmt_check->num_rows > 0;
    echo "Email đã tồn tại!!!"
    
    $stmt_check->close();

    // Trả về kết quả dưới dạng JSON
    echo json_encode(['exists' => $email_exists]);
} else {
    // Xử lý lỗi chuẩn bị câu lệnh (ví dụ: tên bảng sai)
    error_log("SQL prepare failed: " . $conn->error);
    echo json_encode(['exists' => false, 'error' => 'SQL prepare failed']);
}

$conn->close();
?>