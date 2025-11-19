<?php
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh'); 
// 1. Nạp thư viện PHPMailer
// (Giữ nguyên đường dẫn ../ vì file này nằm trong thư mục php)
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// 2. Kiểm tra nút bấm (Hỗ trợ cả tên 'submit' và 'btn_gui_yeu_cau')
$co_nut_bam = isset($_POST['submit']) || isset($_POST['btn_gui_yeu_cau']);

if ($co_nut_bam && isset($_POST['email'])) {
    
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    try {
        // 3. Kết nối Database 'qlysukien'
        // Ưu tiên dùng file connect có sẵn, nếu lỗi thì tự connect thủ công
        if (file_exists('db_connect.php')) {
            require_once 'db_connect.php';
        } elseif (file_exists('../db_connect.php')) {
            require_once '../db_connect.php';
        }
        
        if (!isset($pdo)) {
            $servername = "localhost";
            $username = "root"; 
            $password = "";     
            $dbname = "qlysukien"; 
            $pdo = new PDO("mysql:host=localhost;dbname=$dbname;charset=utf8mb4", $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        }

        // 4. Tìm email trong bảng khachhang
        $stmt = $pdo->prepare("SELECT * FROM khachhang WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // 5. Tạo token bảo mật
            $token = bin2hex(random_bytes(32));
            $expiry = date("Y-m-d H:i:s", time() + 3600); // Hết hạn sau 1 giờ
            
            $stmt = $pdo->prepare("UPDATE khachhang SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
            $stmt->execute([$token, $expiry, $email]);

            // 6. Gửi mail
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0; // Tắt chế độ debug (quan trọng để không hiện chữ linh tinh)
            
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            
            // Thông tin đăng nhập của bạn (Giữ nguyên vì đang chạy tốt)
            $mail->Username   = 'tramb2303785@student.ctu.edu.vn'; 
            $mail->Password   = 'uvuz fcsq wedw vdri'; 

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('huynhtram020405@gmail.com', 'Ho Tro Lay Lai Mat Khau');
            $mail->addAddress($email);

            // Link bấm vào để đặt lại mật khẩu
            $link = "http://localhost/webbanvesukien/php/dat_lai_mat_khau.php?token=" . $token;
            
            $mail->isHTML(true);
            $mail->Subject = 'Yeu cau dat lai mat khau - Web Ban Ve';
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; line-height: 1.6;'>
                    <h3>Xin chào,</h3>
                    <p>Hệ thống nhận được yêu cầu đặt lại mật khẩu cho tài khoản: <b>$email</b></p>
                    <p>Vui lòng bấm vào nút bên dưới để tạo mật khẩu mới (Link chỉ có hiệu lực trong 1 giờ):</p>
                    <p>
                        <a href='$link' style='background-color: #0d6efd; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;'>
                            ĐẶT LẠI MẬT KHẨU
                        </a>
                    </p>
                    <p>Hoặc copy đường dẫn sau: <br> <a href='$link'>$link</a></p>
                    <p>Nếu bạn không yêu cầu, vui lòng bỏ qua email này.</p>
                </div>
            ";

            $mail->send();
        }

        // 7. Thông báo kết quả
        // Luôn báo thành công dù có tìm thấy email hay không (để hacker không dò được email nào tồn tại)
        $_SESSION['thong_bao'] = "Yêu cầu đã được tiếp nhận! Vui lòng kiểm tra hộp thư đến (và cả mục Spam) để nhận hướng dẫn.";
        header("Location: quen_mk.php"); // Quay về trang nhập
        exit();

    } catch (Exception $e) {
        // Ghi log lỗi vào file hệ thống server (không hiện ra cho user thấy)
        error_log("Mail Error: " . $e->getMessage());
        $_SESSION['loi'] = "Hệ thống gửi mail đang bận. Vui lòng thử lại sau ít phút.";
        header("Location: quen_mk.php");
        exit();
    } catch (PDOException $e) {
        error_log("DB Error: " . $e->getMessage());
        $_SESSION['loi'] = "Lỗi kết nối cơ sở dữ liệu.";
        header("Location: quen_mk.php");
        exit();
    }
} else {
    // Truy cập trực tiếp thì đẩy về
    header("Location: quen_mk.php");
    exit();
}
?>