<?php
// Cấu hình CSDL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlysukien";

// Khởi tạo các biến trạng thái
$is_logged_in = false;
$user_info = null;

session_start();
if (!isset($_COOKIE['email']) || empty($_COOKIE['email'])){
    $redirect_url = urlencode($_SERVER['REQUEST_URI']);
    header("Location: dangnhap.php?redirect=" . $redirect_url);
    exit; 
}
if (isset($_COOKIE['email'])) {
    $user_email = $_COOKIE['email'];
    $is_logged_in = true;

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        $is_logged_in = false; 
    }

    if ($is_logged_in) {
        $sql = "SELECT user_name,  tel, email FROM khachhang WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user_info = $result->fetch_assoc();
        } else {
            setcookie("email", "", time() - 3600, "/"); 
            setcookie("user_name", "", time() - 3600, "/");
            $is_logged_in = false;
        }

        $stmt->close();
        $conn->close();
    }
}

?>
<?php
$additional_css = ['webstyle.css'];
$page_title = 'Người dùng';
$additional_head = <<<HTML
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
HTML;
require_once 'header.php';
?>
<main>
        <article class="khungdungchung">
            <div class="back_nguoidung"  onclick="history.back(); return false;">
                <a href="#">
                         <i class="fa-solid fa-x" id="x"></i> 
                </a>
                </div>
            <h2>Thông tin tài khoản</h2>
            <fieldset>
            <?php if ($is_logged_in && $user_info): ?>
              <div class="thongtin">
                <label>
                    <i class="fa-solid fa-book-open-reader"></i>
                    <span>Họ và tên: <b><?php echo htmlspecialchars($user_info['user_name']); ?></b></span>
                </label>
              </div>

              <div class="thongtin">
                <label>
                    <i class="fa-solid fa-square-phone"></i>
                    <span>Điện thoại: <b><?php echo htmlspecialchars($user_info['tel']); ?></b></span>
                </label>
              </div>

              <div class="thongtin">
                <label>
                    <i class="fa-solid fa-envelope"></i>
                    <span>Email: <b><?php echo htmlspecialchars($user_info['email']); ?></b></span>
                </label>
              </div>
                <div class="container_1">
                    <div class="logout box_1">
                        <a href="dangxuat.php" class="w3-bar-item w3-button w3-padding" id="logout"  data-bs-toggle="tooltip" title="Đăng xuất">
                            <i class="fa-solid fa-right-from-bracket"></i> 
                        </a>
                    </div>
                    <div class="update_info box_1">
                        <a href="sua_thongtin.php"  id="update" data-bs-toggle="tooltip" title="Sửa thông tin">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </div>
                </div>
              
              
            <?php else: ?>
              <div >
                <h3>⚠️ Bạn chưa đăng nhập.</h3>
                <p id="thongbao">Vui lòng đăng nhập để xem thông tin tài khoản.</p>
                <a href="dangnhap.php" class="go_login" data-bs-toggle="tooltip" title="Quay lại trang đăng nhập">
                  <i class="fa-solid fa-door-open" id="go_login"></i>
                </a>
              </div>
            <?php endif; ?>
            
            </fieldset>
        </article>
</main>
<?php 
    $additional_footer_scripts = <<<HTML
        <script defer src="/scripts/web-layout.js"></script>
        <script defer src="/scripts/homepage.js"></script>
    HTML;
    require_once 'footer.php'; 
?>