<?php
// Cấu hình CSDL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlysukien";

// Khởi tạo các biến trạng thái
$is_logged_in = false;
$user_info = null;


// 1. KIỂM TRA VÀ TRUY VẤN THÔNG TIN NẾU ĐÃ ĐĂNG NHẬP
if (isset($_COOKIE['email'])) {
    $user_email = $_COOKIE['email'];
    $is_logged_in = true;

    // Kết nối CSDL
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        // Nếu kết nối lỗi, coi như chưa đăng nhập hoặc có lỗi hệ thống
        $is_logged_in = false; 
    }

    if ($is_logged_in) {
        // Lấy thông tin người dùng an toàn hơn (Prepared Statement)
        $sql = "SELECT user_name,  tel, email FROM khachhang WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user_info = $result->fetch_assoc();
        } else {
            // Nếu email trong cookie không tồn tại trong DB, xóa cookie và đặt trạng thái chưa đăng nhập
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
// Load CSS của trang người dùng nếu cần
$additional_css = ['webstyle.css'];
require_once 'header.php';
?>
        <article class="khungdungchung">
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
                    <div class="logout" class="box_1">
                        <a href="dangxuat.php" class="w3-bar-item w3-button w3-padding" id="logout">
                            <i class="fa-solid fa-right-from-bracket"></i> 
                        </a>
                    </div>
                    <div class="update_info" class="box_1">
                        <a href="sua_thongtin.php"  id="update">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </div>
                </div>
              
              
            <?php else: ?>
              <div >
                <h3>⚠️ Bạn chưa đăng nhập.</h3>
                <p id="thongbao">Vui lòng đăng nhập để xem thông tin tài khoản.</p>
                <a href="dangnhap.php" class="go_login">
                  <i class="fa-solid fa-door-open" id="go_login"></i>
                </a>
              </div>
            <?php endif; ?>
            
            </fieldset>
        </article>
<?php require_once 'footer.php'; ?>