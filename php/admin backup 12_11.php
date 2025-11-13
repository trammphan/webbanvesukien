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

    <main class="layout">
    <article class= "sidebar">
        <div class="back">
            <a href="#" onclick="history.back(); return false;">
              <i class="fa-regular fa-circle-left" id="x"></i> 
            </a>
        </div>
        <div class="brand">
                <span class="brand-dot"></span>
                <span class="brand-text">Quản trị viên</span>
        </div>
        <nav class="nav">
        <button class="nav-item active" id="btn-sukien">
            <i class="fa-regular fa-calendar-check"></i>
            <span>Quản lý sự kiện</span>
        </button>
        <button class="nav-item" id="btn-danhmuc">
            <i class="fa-regular fa-flag"></i>
            <span>Quản lý danh mục</span>
        </button>
        <button class="nav-item" id="btn-nguoidung">
            <i class="fa-solid fa-users"></i>
            <span>Quản lý người dùng</span>
        </button>
        <button class="nav-item" id="btn-ve">
            <i class="fa-solid fa-ticket"></i>
            <span>Quản lý vé</span>
        </button>
        <button class="nav-item" id="btn-thongke">
            <i class="fa-regular fa-file-lines"></i>
            <span>Thống kê</span>
        </button>
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

    <article class="noidung" id="sukien-section">
        <h2 class="noidung-title">QUẢN LÝ SỰ KIỆN</h2>
        <div class="header">
            <div class="actions">
                <form class="searchbar" method="get" action="admin.php">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="q" placeholder="Danh sách sự kiện và thao tác duyệt/sửa/xóa sẽ hiển thị ở đây." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
                    <button class="btn-search" type="submit">Tìm kiếm</button>
                </form>
            </div>
        </div>
        <i class="fa-solid fa-spinner"></i> Đang cập nhật...
    </article>
    <article class="noidung hidden" id="danhmuc-section">
        <h2 class="noidung-title">QUẢN LÝ DANH MỤC</h2>
        <div class="header">
            <div class="actions">
                <form class="searchbar" method="get" action="admin.php">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="q" placeholder="Thêm/sửa/xóa danh mục sự kiện." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
                    <button class="btn-search" type="submit">Tìm kiếm</button>
                </form>
            </div>
        </div>
        <i class="fa-solid fa-spinner"></i> Đang cập nhật...
    </article>

    <article class="noidung hidden" id="nguoidung-section">
        <h2 class="noidung-title">QUẢN LÝ NGƯỜI DÙNG</h2>
        <div class="header">
            <div class="actions">
                <form class="searchbar" method="get" action="admin.php">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="q" placeholder="Danh sách và quyền hạn người dùng." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
                    <button class="btn-search" type="submit">Tìm kiếm</button>
                </form>
            </div>
        </div>
        <i class="fa-solid fa-spinner"></i> Đang cập nhật...</i>
    </article>

    <article class="noidung hidden" id="ve-section">
        <h2 class="noidung-title">QUẢN LÝ VÉ</h2>
        <div class="header">
            <div class="actions">
                <form class="searchbar" method="get" action="admin.php">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="q" placeholder="Theo dõi vé, doanh thu từng sự kiện." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
                    <button class="btn-search" type="submit">Tìm kiếm</button>
                </form>
            </div>
        </div>
        <i class="fa-solid fa-spinner"></i> Đang cập nhật...</i>
    </article>

    <article class="noidung hidden" id="thongke-section">
        <h2 class="noidung-title">THỐNG KÊ</h2>
        <div class="header">
            <div class="actions">
                <form class="searchbar" method="get" action="admin.php">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="q" placeholder="Báo cáo tổng hợp theo thời gian." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
                    <button class="btn-search" type="submit">Tìm kiếm</button>
                </form>
            </div>
        </div>
        <i class="fa-solid fa-spinner"></i> Đang cập nhật...</i>
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
        const btnSukien = document.getElementById("btn-sukien");
        const btnDanhmuc = document.getElementById("btn-danhmuc");
        const btnNguoidung = document.getElementById("btn-nguoidung");
        const btnVe = document.getElementById("btn-ve");
        const btnThongke = document.getElementById("btn-thongke");

        const sectionSukien = document.getElementById("sukien-section");
        const sectionDanhmuc = document.getElementById("danhmuc-section");
        const sectionNguoidung = document.getElementById("nguoidung-section");
        const sectionVe = document.getElementById("ve-section");
        const sectionThongke = document.getElementById("thongke-section");

        function showSection(sectionToShow, clickedButton) {
            [sectionSukien, sectionDanhmuc, sectionNguoidung, sectionVe, sectionThongke]
              .forEach(sec => sec.classList.add("hidden"));
            sectionToShow.classList.remove("hidden");
            [btnSukien, btnDanhmuc, btnNguoidung, btnVe, btnThongke]
              .forEach(btn => btn.classList.remove("active"));
            clickedButton.classList.add("active");
        }

        btnSukien.addEventListener("click", () => showSection(sectionSukien, btnSukien));
        btnDanhmuc.addEventListener("click", () => showSection(sectionDanhmuc, btnDanhmuc));
        btnNguoidung.addEventListener("click", () => showSection(sectionNguoidung, btnNguoidung));
        btnVe.addEventListener("click", () => showSection(sectionVe, btnVe));
        btnThongke.addEventListener("click", () => showSection(sectionThongke, btnThongke));
        });
</script>