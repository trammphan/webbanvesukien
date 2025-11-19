<?php
session_start();

// Kết nối CSDL
$servername = "localhost";
$username = "root";
$password_db = ""; // Đặt tên khác để tránh trùng
$dbname = "qlysukien";

$conn = new mysqli($servername, $username, $password_db, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Khi người dùng nhấn nút Đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Lấy dữ liệu
    $email = mysqli_real_escape_string($conn, trim(strtolower($_POST['email'])));
    
    // LƯU Ý: Mật khẩu dùng để kiểm tra (verify) phải là mật khẩu thô, KHÔNG dùng escape string
    $password_raw = trim($_POST['password']); 
    
    $redirect_url = isset($_POST['redirect']) ? $_POST['redirect'] : '';

    $tables = ['khachhang', 'quantrivien', 'nhatochuc', 'nhanviensoatve'];
    $user = null; 

    // 2. Tìm user trong các bảng
    foreach ($tables as $table) {
        $sql = "SELECT * FROM $table WHERE email = '$email' LIMIT 1";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $user['table'] = $table; 
            break; 
        }
    }

    // 3. Kiểm tra đăng nhập
    if (!empty($user)) {
        
        // Lấy mật khẩu trong DB (hỗ trợ cả cột tên 'password' hoặc 'matkhau')
        $db_pass = isset($user['password']) ? $user['password'] : (isset($user['matkhau']) ? $user['matkhau'] : '');
        
        $login_success = false;
        $need_update_hash = false;

        // --- LOGIC KIỂM TRA MẬT KHẨU NÂNG CAO ---
        
        // Ưu tiên 1: Kiểm tra mã hóa (Dành cho pass mới đổi)
        if (password_verify($password_raw, $db_pass)) {
            $login_success = true;
        }
        // Ưu tiên 2: Kiểm tra pass thường (Dành cho pass cũ)
        elseif ($password_raw === $db_pass) {
            $login_success = true;
            $need_update_hash = true; // Cần mã hóa lại ngay
        }
        // Ưu tiên 3: Kiểm tra MD5 (Dành cho hệ thống cũ)
        elseif (md5($password_raw) === $db_pass) {
            $login_success = true;
            $need_update_hash = true;
        }

        // --- XỬ LÝ KẾT QUẢ ---
        if ($login_success) {
            
            // Nếu là pass cũ -> Tự động nâng cấp lên mã hóa
            if ($need_update_hash) {
                $new_hash = password_hash($password_raw, PASSWORD_DEFAULT);
                $table_name = $user['table'];
                $user_id = $user['id'];
                
                // Cập nhật lại mật khẩu đã mã hóa vào DB (cột password hoặc matkhau)
                $col_name = isset($user['password']) ? 'password' : 'matkhau';
                $conn->query("UPDATE $table_name SET $col_name = '$new_hash' WHERE id = '$user_id'");
            }

            // Lưu Session (Quan trọng cho đăng nhập)
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['table'];
            $_SESSION['user_name'] = isset($user['user_name']) ? $user['user_name'] : (isset($user['hoten']) ? $user['hoten'] : $user['email']);

            // Lưu Cookie (như code cũ của bạn)
            setcookie("email", $user['email'], time() + 3600, "/");
            setcookie("user_name", $_SESSION['user_name'], time() + 3600, "/");
            setcookie("user_role", $user['table'], time() + 3600, "/");

            // Chuyển hướng
            switch ($user['table']) {
                case 'khachhang':
                    if (!empty($redirect_url)) {
                        header('Location: ' . $redirect_url);
                    } else {
                        header("Location: index.php");
                    }
                    break;
                case 'quantrivien':
                    header("Location: admin.php");
                    break;
                case 'nhatochuc':
                    header("Location: nhatochuc.php");
                    break;
                case 'nhanviensoatve':
                    header("Location: nhanvien.php");
                    break;
            }
            exit();
        } else {
            go_back_error('Mật khẩu không đúng!', $redirect_url);
        }
    } else {
        go_back_error('Email không tồn tại!', $redirect_url);
    }
}

$conn->close();

// Hàm báo lỗi và quay lại
function go_back_error($msg, $redirect) {
    $error_location = 'dangnhap.php';
    if (!empty($redirect)) {
        $error_location .= '?redirect=' . urlencode($redirect);
    }
    // Sử dụng JS để alert và redirect
    echo "<script>
            alert('$msg'); 
            window.location.href = '$error_location';
          </script>";
    exit();
}
?>