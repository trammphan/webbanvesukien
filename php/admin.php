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
?>

<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="../img/fav-icon.png" rel="icon" type="image/vnd.microsoft.icon">
    <title>Quản trị viên</title>
    <!-- <link rel="icon" href="img/icon.jpg" title="logo" /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
   <link rel="stylesheet" href="../css/webstyle.css"/> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script defer src="/scripts/web-layout.js"></script>
    <script defer src="/scripts/homepage.js"></script>
    <!--<link rel="stylesheet" href="index.css">-->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;700;900&family=Open+Sans:wght@300;400;500;600;700;800&family=Nunito:wght@300;400;500;600;700;800;900&family=Source+Sans+Pro:wght@300;400;600;700;900&display=swap" rel="stylesheet">
  </head>
<body   >
     <header class="main-header">
        <div class="header-container">
            <div class="header-logo">
                <a href="index.php" style="color: #ffffff; text-decoration: none; font-size: 24px; font-weight: bold;">Vibe4</a>
            </div>

            <div class="header-search">
                <form action="/search" method="get">
                    <input type="text" placeholder="Tìm kiếm sự kiện, địa điểm..." name="q" class="search-input">
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            
            <div class="header-right">
                <nav class="header-nav">
                    <ul>
                        <li><a href="#taosukien">Tạo sự kiện</a></li>
                        <li><a href="#vecuatoi">Vé của tôi</a></li>
                    </ul>
                </nav>
                
                <?php include __DIR__ . '/header_actions.php'; ?>
            </div>
        </div>
    </header>

    <main>
    <article class= "thanh_doc">
        <p class="quantrivien"> QUẢN TRỊ VIÊN</p>
        <button class="thanh_doc_1" id="btn-duyet">
            <i class="fa-solid fa-user-shield"></i>
            <span>Duyệt sự kiện</span>      </button>
        <button class="thanh_doc_2" id="btn-baocao">
            <i class="fa-solid fa-user-shield"></i>
            <span>Quản lý báo cáo</span>    </button>
        <button class="thanh_doc_3" id="btn-dieukhoan">
            <i class="fa-solid fa-user-shield"></i>
            <span>Điều khoản </span>        </button>
        <?php if ($is_logged_in && $user_info): ?>
            <label class="email_qtv">
                <i class="fa-solid fa-envelope"></i>
                <span>Email: <b><?php echo htmlspecialchars($user_info['email']); ?></b></span>
            </label>
    
            <div >
                <div class="logout box_1">
                    <a href="dangxuat.php" class="w3-bar-item w3-button w3-padding" id="logout">
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
    </article>

    <article class="noidung hidden dieukhoan" id="dieukhoan-section">
        <h2>Điều khoản sử dụng</h2>
        <p>Các điều khoản và quy định dành cho quản trị viên.</p>
    </article>


       
    </main>
 
   <footer>
        <div class="footer-container">
            
            <div class="footer-col footer-branding">
                <h3 class="footer-logo">Vibe4</h3>
                <p>Nền tảng mua vé sự kiện đa dạng: hòa nhạc, hội thảo, thể thao, phim, kịch và voucher uy tín tại Việt Nam.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-dribbble"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Liên kết Hữu ích</h4>
                <ul class="footer-links">
                    <li><a href="#home">Trang chủ</a></li>
                    <li><a href="#taosukien">Tạo sự kiện</a></li>
                    <li><a href="#vecuatoi">Vé của tôi</a></li>
                    <li><a href="#faq">FAQ</a></li>
                    <li><a href="#contact">Liên hệ</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Liên hệ</h4>
                <ul class="footer-contact">
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <span>+123 456 789</span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span>support@vibe4.com</span>
                    </li>
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Khu II Đại học Cần Thơ, Đường 3/2, P Ninh Kiều, TP Cần Thơ</span>
                    </li>
                </ul>
            </div>

            <div class="footer-col footer-action">
                <h4 class="action-title">Tham gia cùng chúng tôi</h4>
                <button class="btn-download">Tải ứng dụng ngay</button>
            </div>

        </div>
        
        <div class="footer-bottom">
            <p>@2025 - All Rights Reserved by Vibe4 Platform • Phát triển bởi Nhóm 1-CT299-Phát Triển Hệ Thống Web</p>
        </div>

       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> 
    </footer>             
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

</body>
</html>


<!-- Footer Section-->