<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cấu hình CSDL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlysukien";

// Biến trạng thái
$is_logged_in = false;
$user_info = null;

// Hàm định dạng giới tính
function format_gender($gender_code) {
    switch ($gender_code) {
        case 'male': return 'Nam';
        case 'female': return 'Nữ';
        case 'other': return 'Khác';
        default: return 'Chưa xác định';
    }
}

// --- Bắt đầu kiểm tra cookie ---
$user_email = $_COOKIE['email'] ?? null;

if ($user_email) {
    // Kết nối CSDL
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("❌ Kết nối CSDL thất bại: " . $conn->connect_error);
    }

    // Kiểm tra xem email có tồn tại không
    $sql = "SELECT email, user_name, gender FROM nhanviensoatve WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("❌ Lỗi prepare: " . $conn->error);
    }

    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user_info = $result->fetch_assoc();
        $is_logged_in = true;
    } else {
        echo "<div style='color:red; text-align:center;'>❌ Không tìm thấy tài khoản nhân viên soát vé có email: <b>$user_email</b></div>";
        // Xóa cookie cũ
        setcookie("email", "", time() - 3600, "/");
        setcookie("user_name", "", time() - 3600, "/");
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<div style='color:red; text-align:center;'>⚠️ Bạn chưa đăng nhập. <a href='dangnhap.php'>Đăng nhập ngay</a></div>";
}
?>

<?php
// Load CSS của trang người dùng nếu cần
$additional_css = ['webstyle.css'];
// Giữ tiêu đề và assets head gốc
$page_title = 'Người dùng';
$additional_head = <<<HTML
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
HTML;
require_once 'header.php';
?>
    <main>
    <article class="khungdungchung">
        <div class="thongtinnhanvien">
            <fieldset class="">
                <div class="back_nguoidung"  onclick="history.back(); return false;">
                 <!-- <i class="fa-solid fa-x"></i>  -->
                <a href="#">
                         <i class="fa-solid fa-x" id="x"></i> 
                </a>
                </div>
                <h2>Thông tin tài khoản</h2>   
                <?php if ($is_logged_in && $user_info): ?>
                
                    <div class="thongtin">
                    <label>
                        <i class="fa-solid fa-envelope"></i>
                        <span>Email: <b><?php echo htmlspecialchars($user_info['email']); ?></b></span>
                    </label>
                </div>
                <div class="thongtin">
                    <label>
                        <i class="fa-solid fa-book-open-reader"></i>
                        <span>Họ và tên: <b><?php echo htmlspecialchars($user_info['user_name']); ?></b></span>
                    </label>
                </div>

                <div class="thongtin">
                    <label>
                        <i class="fa-solid fa-venus-mars"></i>
                        <span>Giới tính: <b><?php echo format_gender($user_info['gender']); ?></b></span>
                    </label>
                </div>
    
                    <div class="container_1">
                        <div class="logout" class="box_1">
                            <a href="dangxuat.php" class="w3-bar-item w3-button w3-padding" id="logout" data-bs-toggle="tooltip" title="Đăng xuất">
                                <i class="fa-solid fa-right-from-bracket"></i> 
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </fieldset>
        </div>
            

        <h2 class="nhanvien-title">Chào mừng Nhân Viên Soát Vé!</h2>
        <p class="chaomung_nv">Chào mừng bạn đến với trang quản lý nhân viên soát vé của Vibe4. Tại đây, bạn có thể dễ dàng quản lý và xác nhận vé sự kiện một cách nhanh chóng và hiệu quả.</p>

        <section id="duyet-section" class="nhanvien-section">
            <h3>Duyệt Vé Sự Kiện</h3>
            <p class="chaomung_nv">Tại đây bạn có thể quét và xác nhận vé của khách hàng một cách nhanh chóng.</p>
            <button id="btn_duyet" class="nhanvien-btn active">Duyệt Vé</button>
        </section>

        <section id="baocao-section" class="nhanvien-section ">
            <h3>Báo Cáo Công Việc</h3>
            <p class="chaomung_nv">Xem và tải về các báo cáo công việc hàng ngày của bạn.</p>
            <button id="btn_baocao" class="nhanvien-btn">Báo Cáo</button>
        </section>

        <section id="dieukhoan-section" class="nhanvien-section ">
            <h3>Điều Khoản và Chính Sách</h3>
            <p class="chaomung_nv">Đọc kỹ các điều khoản và chính sách liên quan đến công việc của bạn.</p>
            <button id="btn_dieukhoan" class="nhanvien-btn">Điều Khoản</button>
        </section>
    </article>
       
    </main>
 

<?php 
    $additional_footer_scripts = <<<HTML
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    HTML;
    require_once 'footer.php';
?>