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
        // // Lấy thông tin người dùng an toàn hơn (Prepared Statement)
         $sql = "SELECT user_name, email FROM quantrivien WHERE email = ?";
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

// Thiết lập tiêu đề và assets trang, dùng header/footer chung
$page_title = 'Quản trị viên';
$additional_css = ['webstyle.css'];
$additional_head = <<<HTML
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
HTML;
require_once 'header.php';
?>

    <main>
    <article class= "thanh_doc">
        <div class="back_nguoidung"  onclick="history.back(); return false;">
                 <!-- <i class="fa-solid fa-x"></i>  -->
                <a href="#">
                         <i class="fa-solid fa-x" id="x"></i> 
                </a>
                </div>
        <p class="tieude"> QUẢN TRỊ VIÊN</p>
        <button class="congviec" id="btn-duyet">
            <i class="fa-solid fa-user-shield"></i>
            <span>Duyệt sự kiện</span>      </button>
        <button class="congviec" id="btn-baocao">
            <i class="fa-solid fa-user-shield"></i>
            <span>Quản lý báo cáo</span>    </button>
        <button class="congviec" id="btn-dieukhoan">
            <i class="fa-solid fa-user-shield"></i>
            <span>Điều khoản </span>        </button>
        <?php if ($is_logged_in && $user_info): ?>
            <label class="email_qtv">
                <i class="fa-solid fa-envelope"></i>
                <span>Email: <b><?php echo htmlspecialchars($user_info['email']); ?></b></span>
            </label>
    
            <div >
                <div class="box_3">
                    <a href="dangxuat.php" class="w3-bar-item w3-button w3-padding" id="logout" data-bs-toggle="tooltip" title="Đăng xuất">
                        <i class="fa-solid fa-right-from-bracket"></i> 
                    </a>
                </div>           
            </div>
        <?php endif; ?>
    </article>

    <article class=" noidung duyetsukien" id="duyet-section">
        <h2>Duyệt sự kiện</h2>
        <p>Danh sách sự kiện chờ duyệt sẽ hiển thị ở đây.</p>
         
    </article>
    <article class="noidung hidden baocao" id="baocao-section">
        <h2>Quản lý báo cáo</h2>
        <p>Danh sách các báo cáo người dùng gửi về hệ thống...</p>
         <i class="fa-solid fa-spinner"></i> Đang cập nhật...
    </article>

    <article class="noidung hidden dieukhoan" id="dieukhoan-section">
        <h2>Điều khoản sử dụng</h2>
        <p>Các điều khoản và quy định dành cho quản trị viên.</p>
         <i class="fa-solid fa-spinner"></i> Đang cập nhật...
    </article>


       
    </main>
<?php 
    $additional_footer_scripts = <<<HTML
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script defer src="/scripts/web-layout.js"></script>
        <script defer src="/scripts/homepage.js"></script>
    HTML;
    require_once 'footer.php';
?>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
        const btnDuyet = document.getElementById("btn-duyet");
        const btnBaocao = document.getElementById("btn-baocao");
        const btnDieukhoan = document.getElementById("btn-dieukhoan");

        const sectionDuyet = document.getElementById("duyet-section");
        const sectionBaocao = document.getElementById("baocao-section");
        const sectionDieukhoan = document.getElementById("dieukhoan-section");

        function showSection(sectionToShow, clickedButton) {
            // Ẩn tất cả
            [sectionDuyet, sectionBaocao, sectionDieukhoan].forEach(sec => sec.classList.add("hidden"));
            // Hiện phần được chọn
            sectionToShow.classList.remove("hidden");

            // Cập nhật nút đang chọn
            [btnDuyet, btnBaocao, btnDieukhoan].forEach(btn => btn.classList.remove("active"));
            clickedButton.classList.add("active");
        }

        btnDuyet.addEventListener("click", () => showSection(sectionDuyet, btnDuyet));
        btnBaocao.addEventListener("click", () => showSection(sectionBaocao, btnBaocao));
        btnDieukhoan.addEventListener("click", () => showSection(sectionDieukhoan, btnDieukhoan));
        });
</script>