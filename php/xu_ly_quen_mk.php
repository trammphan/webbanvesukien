<?php
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh'); 

// 1. Load thư viện PHPMailer
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// 2. Kiểm tra nút bấm
$co_nut_bam = isset($_POST['submit']) || isset($_POST['btn_gui_yeu_cau']);

if ($co_nut_bam && isset($_POST['email'])) {
    
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    try {
        // 3. Kết nối Database (Code đã kiểm chứng hoạt động)
        if (file_exists('db_connect.php')) {
            require_once 'db_connect.php';
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
            $mail->SMTPDebug = 0; // TẮT DEBUG để không hiện code ra màn hình
            
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            
            // Thông tin đăng nhập (Đã test thành công)
            $mail->Username   = 'tramb2303785@student.ctu.edu.vn'; 
            $mail->Password   = 'uvuz fcsq wedw vdri'; // Mật khẩu ứng dụng

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            // Cấu hình người gửi/nhận
            $mail->setFrom('tramb2303785@student.ctu.edu.vn', 'Web Ban Ve Support');
            $mail->addAddress($email);

            // Link bấm vào để đặt lại mật khẩu
            $link = "http://localhost/webbanvesukien/php/dat_lai_mat_khau.php?token=" . $token;
            
            $mail->isHTML(true);
            $mail->Subject = 'Yeu cau dat lai mat khau'; // Không dấu để tránh lỗi tiêu đề
            
            // Nội dung HTML chuyên nghiệp
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 5px;'>
                    <h3 style='color: #0d6efd;'>Yêu cầu đặt lại mật khẩu</h3>
                    <p>Xin chào,</p>
                    <p>Hệ thống nhận được yêu cầu thay đổi mật khẩu cho tài khoản: <b>$email</b></p>
                    <p>Vui lòng bấm vào nút bên dưới để tạo mật khẩu mới (Link hết hạn sau 1 giờ):</p>
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='$link' style='background-color: #0d6efd; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;'>
                            ĐẶT LẠI MẬT KHẨU
                        </a>
                    </div>
                    <p style='font-size: 13px; color: #666;'>Hoặc copy đường dẫn sau: <br> <a href='$link'>$link</a></p>
                    <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
                    <p style='font-size: 12px; color: #999;'>Nếu bạn không yêu cầu thay đổi, vui lòng bỏ qua email này.</p>
                </div>
            ";
            $mail->AltBody = "Vui lòng copy link sau để đặt lại mật khẩu: $link";

            $mail->send();
        }

        // 7. Thông báo & Chuyển hướng (Redirect)
        $_SESSION['thong_bao'] = "Yêu cầu đã được gửi! Vui lòng kiểm tra hộp thư đến và mục Spam.";
        header("Location: quen_mk.php"); 
        exit();

    } catch (Exception $e) {
        // Lỗi gửi mail
        error_log("Mail Error: " . $e->getMessage()); 
        $_SESSION['loi'] = "Không thể gửi mail lúc này. Vui lòng thử lại sau.";
        header("Location: quen_mk.php");
        exit();
    } catch (PDOException $e) {
        // Lỗi Database
        error_log("DB Error: " . $e->getMessage());
        $_SESSION['loi'] = "Lỗi kết nối hệ thống.";
        header("Location: quen_mk.php");
        exit();
    }
} else {
    // Truy cập trực tiếp mà không bấm nút -> Đẩy về
    header("Location: quen_mk.php");
    exit();
}
?>