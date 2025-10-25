<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cấu hình CSDL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlkhachhang";

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


<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng ký</title>
    <!-- <link rel="icon" href="img/icon.jpg" title="logo" /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
   <link rel="stylesheet" href="webstyle.css"/> 

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
                <a href="#" style="color: #ffffff; text-decoration: none; font-size: 24px; font-weight: bold;">Vibe4</a>
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

                <div class="header-actions">
                    <a href="dangnhap.php" class="btn-signin">Đăng nhập</a>
                    <a href="dangky.php" class="btn-signin">Đăng ký</a>
                    <a href="nguoidung.php" class="btn-signin">
                        <i class="fas fa-user-circle"></i></a>
                </div>
            </div>
        </div>
    </header>

    <main>
    <article class="nhanvien">
            
            <fieldset class="thongtinnhanvien">
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
            
            </fieldset>
            <?php endif; ?>

        <h2 class="nhanvien-title">Chào mừng Nhân Viên Soát Vé!</h2>
        <p class="chaomung">Chào mừng bạn đến với trang quản lý nhân viên soát vé của Vibe4. Tại đây, bạn có thể dễ dàng quản lý và xác nhận vé sự kiện một cách nhanh chóng và hiệu quả.</p>

        <section id="duyet-section" class="nhanvien-section">
            <h3>Duyệt Vé Sự Kiện</h3>
            <p class="chaomung">Tại đây bạn có thể quét và xác nhận vé của khách hàng một cách nhanh chóng.</p>
            <button id="btn_duyet" class="nhanvien-btn active">Duyệt Vé</button>
        </section>

        <section id="baocao-section" class="nhanvien-section ">
            <h3>Báo Cáo Công Việc</h3>
            <p class="chaomung">Xem và tải về các báo cáo công việc hàng ngày của bạn.</p>
            <button id="btn_baocao" class="nhanvien-btn">Báo Cáo</button>
        </section>

        <section id="dieukhoan-section" class="nhanvien-section ">
            <h3>Điều Khoản và Chính Sách</h3>
            <p class="chaomung">Đọc kỹ các điều khoản và chính sách liên quan đến công việc của bạn.</p>
            <button id="btn_dieukhoan" class="nhanvien-btn">Điều Khoản</button>
        </section>
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
    

</body>
</html>


<!-- Footer Section-->