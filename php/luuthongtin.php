<?php
session_start();
// Thiết lập header để trả về JSON
header('Content-Type: application/json');

// Hàm chuẩn hóa kết quả lỗi
function sendErrorResponse($errors) {
    global $conn;
    // Đảm bảo đóng kết nối nếu nó đang mở
    if ($conn && $conn->ping()) {
        $conn->close();
    }
    // Dùng exit(json_encode) để dừng script và xuất JSON
    exit(json_encode(['success' => false, 'errors' => $errors]));
}

// Chỉ cho phép POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    
    exit;
}

// *** Đảm bảo các thông số này chính xác ***
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "qlysukien";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    // THAY THẾ die() bằng JSON error response
    sendErrorResponse(["Lỗi kết nối cơ sở dữ liệu. Vui lòng kiểm tra lại cấu hình. Chi tiết: " . $conn->connect_error]);
}

// Lấy dữ liệu từ POST và làm sạch
$user_name = trim($_POST["user_name"] ?? '');
$tel = trim($_POST["tel"] ?? '');
$email = trim(strtolower($_POST["email"] ?? ''));
$raw_password = $_POST["password"] ?? '';
$redirect_url = $_POST["redirect"] ?? '';

$errors = [];

// --- 1. XÁC THỰC SERVER-SIDE ---
if (empty($user_name) || empty($tel) || empty($email) || empty($raw_password)) {
    $errors[] = "Vui lòng điền đầy đủ tất cả các trường bắt buộc.";
}
if (strlen($raw_password) < 5) {
    $errors[] = "Mật khẩu phải có tối thiểu 5 ký tự.";
}
// Thêm kiểm tra số điện thoại (bị thiếu trong code gốc)
if (!preg_match('/^\d{10,11}$/', $tel)) {
    $errors[] = "Số điện thoại không hợp lệ. Vui lòng nhập 10 hoặc 11 chữ số.";
}


// --- 2. BẮT SỰ KIỆN EMAIL ĐÃ TỒN TẠI (Kiểm tra 4 bảng) ---

if (empty($errors)) {
    // Kiểm tra Email đã tồn tại trong BỐN BẢNG
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
    
    // Liên kết 4 tham số (4 email string)
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

// --- 3. XỬ LÝ LỖI VÀ THỰC HIỆN INSERT ---

if (!empty($errors)) {
    // TRẢ VỀ JSON CHỨA LỖI VÀ DỪNG LẠI
    sendErrorResponse($errors);
}

// Hash mật khẩu 
$password = md5($raw_password); // Sửa: Dùng $raw_password thay vì trim($_POST["password"]) vì đã lấy ở trên

// Sử dụng Prepared Statement cho câu lệnh INSERT
$sql = "INSERT INTO khachhang (email, user_name, tel, password) 
        VALUES (?, ?, ?, ?)";

$stmt_insert = $conn->prepare($sql);

if ($stmt_insert === false) {
    sendErrorResponse(["Lỗi chuẩn bị truy vấn SQL (Thêm người dùng)."]);
}

$stmt_insert->bind_param("ssss", $email, $user_name, $tel, $password); 

if ($stmt_insert->execute()) {
    // Đăng ký thành công
    
    $redirect_to = "dangnhap.php"; 
    if (!empty($redirect_url)) {
        // Tự động đăng nhập
        setcookie("email", $email, time() + 3600, "/");
        setcookie("user_name", $user_name, time() + 3600, "/");
        $redirect_to = urldecode($redirect_url);
    }
    
    $stmt_insert->close();
    $conn->close();

    // TRẢ VỀ JSON THÀNH CÔNG VỚI URL CHUYỂN HƯỚNG
    echo json_encode(['success' => true, 'redirect_url' => $redirect_to]);

} else {
    // Lỗi SQL khi INSERT
    // THAY THẾ echo "Error: ..." bằng JSON error response
    sendErrorResponse(["Lỗi hệ thống: Không thể đăng ký. Chi tiết: " . $conn->error]);
}
?>