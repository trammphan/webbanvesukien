<?php
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh'); 

// 1. KẾT NỐI DATABASE
try {
    if (file_exists('db_connect.php')) require_once 'db_connect.php';
    elseif (file_exists('../db_connect.php')) require_once '../db_connect.php';
    
    if (!isset($pdo)) {
        $servername = "localhost";
        $username = "root"; 
        $password = "";     
        $dbname = "qlysukien"; 
        $pdo = new PDO("mysql:host=localhost;dbname=$dbname;charset=utf8mb4", $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }
} catch (PDOException $e) {
    die("Lỗi kết nối Database: " . $e->getMessage());
}

$error = '';
$success = '';

// 2. KIỂM TRA TOKEN
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    $stmt = $pdo->prepare("SELECT * FROM khachhang WHERE reset_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die('<div style="text-align:center; padding-top:50px;">
                <h2 style="color:red;">LIÊN KẾT KHÔNG HỢP LỆ!</h2>
                <p>Vui lòng <a href="quen_mk.php">gửi lại yêu cầu mới</a>.</p>
             </div>');
    } else {
        $now = date("Y-m-d H:i:s");
        if ($user['reset_token_expiry'] < $now) {
             die('<div style="text-align:center; padding-top:50px;">
                    <h2 style="color:red;">LIÊN KẾT ĐÃ HẾT HẠN!</h2>
                    <p>Vui lòng <a href="quen_mk.php">gửi lại yêu cầu mới</a>.</p>
                 </div>');
        }
    }
} else {
    header("Location: dangnhap.php");
    exit();
}

// 3. XỬ LÝ ĐỔI MẬT KHẨU
if (isset($_POST['btn_reset_pass'])) {
    $pass_new = $_POST['password_new'];
    $pass_confirm = $_POST['password_confirm'];

    if (strlen($pass_new) < 5) {
        $error = "Mật khẩu phải có ít nhất 5 ký tự.";
    } elseif ($pass_new !== $pass_confirm) {
        $error = "Mật khẩu nhập lại không khớp.";
    } else {
        
        $hashed_password = password_hash($pass_new, PASSWORD_DEFAULT); 
        $sql = "UPDATE khachhang SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE email = ?";
        $stmt_update = $pdo->prepare($sql);
        
        if ($stmt_update->execute([$hashed_password, $user['email']])) {
            $success = "Đổi mật khẩu thành công! Đang chuyển về trang đăng nhập...";
            header("refresh:3;url=dangnhap.php");
        } else {
            $error = "Lỗi hệ thống, không thể cập nhật.";
        }
    }
}

// --- GIAO DIỆN HTML ---
$additional_css = ['webstyle.css'];
$page_title = 'Đặt Lại Mật Khẩu';
$additional_head = <<<HTML
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
HTML;
?>
<?php require_once 'header.php'; ?>

<main>
    <article class="khungdungchung">
        <h2>ĐẶT LẠI MẬT KHẨU</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success text-center" role="alert">
                <?php echo $success; ?>
            </div>
        <?php else: ?>
            <p style="text-align: center; margin-bottom: 20px; color: #555;">Vui lòng nhập mật khẩu mới (sẽ được mã hóa bảo mật).</p>

            <form method="POST" action="">
                <div class="thongtin">
                    <label for="password_new">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password_new" name="password_new" placeholder="Mật khẩu mới" required />
                    </label>
                </div>
                <div class="thongtin">
                    <label for="password_confirm">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password_confirm" name="password_confirm" placeholder="Nhập lại mật khẩu mới" required />
                    </label>
                </div>

                <div class="dang_nhap">
                    <input type="submit" name="btn_reset_pass" value="Lưu mật khẩu" id="login"/>
                </div>
            </form>
        <?php endif; ?>

    </article>
</main>

<?php 
    $additional_footer_scripts = <<<HTML
        <script src="/scripts/web-layout.js"></script>
        <script src="/scripts/homepage.js"></script>
    HTML;
    require_once 'footer.php'; 
?>