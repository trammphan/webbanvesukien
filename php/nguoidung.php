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

<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="../img/fav-icon.png" rel="icon" type="image/vnd.microsoft.icon">
    <title>Người dùng</title>
    <!-- <link rel="icon" href="img/icon.jpg" title="logo" /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
   <link rel="stylesheet" href="../css/webstyle.css"/> 

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
        <article class="khungdungchung">
            <div class="back_nguoidung">
                 <!-- <i class="fa-solid fa-x"></i>  -->
                <a href="#" onclick="history.back(); return false;">
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
       <script scr="vi.js">
    </footer>             

</body>
</html>