<?php
session_start();
header('Content-Type: application/json');

function sendErrorResponse($errors) {
    global $conn;
    if ($conn && $conn->ping()) {
        $conn->close();
    }
    exit(json_encode(['success' => false, 'errors' => $errors]));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    
    exit;
}

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "qlysukien";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    sendErrorResponse(["Lỗi kết nối cơ sở dữ liệu. Vui lòng kiểm tra lại cấu hình. Chi tiết: " . $conn->connect_error]);
}

$user_name = trim($_POST["user_name"] ?? '');
$tel = trim($_POST["tel"] ?? '');
$email = trim(strtolower($_POST["email"] ?? ''));
$raw_password = $_POST["password"] ?? '';
$redirect_url = $_POST["redirect"] ?? '';

$errors = [];

if (empty($user_name) || empty($tel) || empty($email) || empty($raw_password)) {
    $errors[] = "Vui lòng điền đầy đủ tất cả các trường bắt buộc.";
}
if (strlen($raw_password) < 5) {
    $errors[] = "Mật khẩu phải có tối thiểu 5 ký tự.";
}

if (!preg_match('/^\d{10,11}$/', $tel)) {
    $errors[] = "Số điện thoại không hợp lệ. Vui lòng nhập 10 hoặc 11 chữ số.";
}



if (empty($errors)) {
    $check_email_sql = "
        SELECT email FROM khachhang WHERE email = ?
        UNION
        SELECT email FROM nhatochuc WHERE email = ?
        UNION
        SELECT email FROM nhanviensoatve WHERE email = ?
        UNION
        SELECT email FROM quantrivien WHERE email = ?
    ";
    $stmt_check = $conn->prepare($check_email_sql);
    
    if ($stmt_check === false) {
         sendErrorResponse(["Lỗi chuẩn bị truy vấn SQL (Kiểm tra Email)."]);
    }
    $stmt_check->bind_param("ssss", $email, $email, $email, $email); 
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $errors[] = "Email này đã được sử dụng bởi một tài khoản khác. Vui lòng dùng email khác.";
    }
    $stmt_check->close();
}


if (!empty($errors)) {
    sendErrorResponse($errors);
}

$password = md5($raw_password); 

$sql = "INSERT INTO khachhang (email, user_name, tel, password) 
        VALUES (?, ?, ?, ?)";

$stmt_insert = $conn->prepare($sql);

if ($stmt_insert === false) {
    sendErrorResponse(["Lỗi chuẩn bị truy vấn SQL (Thêm người dùng)."]);
}

$stmt_insert->bind_param("ssss", $email, $user_name, $tel, $password); 

if ($stmt_insert->execute()) {

    
    $redirect_to = "dangnhap.php"; 
    if (!empty($redirect_url)) {
        setcookie("email", $email, time() + 3600, "/");
        setcookie("user_name", $user_name, time() + 3600, "/");
        $redirect_to = urldecode($redirect_url);
    }
    
    $stmt_insert->close();
    $conn->close();

    echo json_encode(['success' => true, 'redirect_url' => $redirect_to]);

} else {
    sendErrorResponse(["Lỗi hệ thống: Không thể đăng ký. Chi tiết: " . $conn->error]);
}
?>