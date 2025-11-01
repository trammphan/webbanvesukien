<?php
// Bắt đầu phiên (cần thiết nếu trang khác vẫn dùng session)
session_start();

// 🔹 Kiểm tra cookie "email" thay vì session
if (!isset($_COOKIE["email"])) {
    header("Location: dangnhap.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlysukien";

// Kết nối CSDL
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$msg = ""; // Biến chứa thông báo

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_pass = md5($_POST["old_pass"]);
    $new_pass = $_POST["new_pass"];
    $confirm_pass = $_POST["confirm_pass"];
    $email = $_COOKIE["email"]; // 🔹 Lấy email từ cookie

    // Lấy mật khẩu cũ từ CSDL
    $sql = "SELECT password FROM khachhang WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_pass = $row["password"];

        // Kiểm tra mật khẩu cũ có khớp không
        if ($old_pass != $current_pass) {
            $msg = "❌ Mật khẩu cũ không đúng!";
        } elseif ($new_pass != $confirm_pass) {
            $msg = "❌ Mật khẩu mới và nhập lại không trùng khớp!";
        } elseif ($old_pass == md5($new_pass)) {
            $msg = "⚠️ Mật khẩu mới không được giống mật khẩu cũ!";
        } else {
            // Cập nhật mật khẩu mới (đã băm md5)
            $new_pass_md5 = md5($new_pass);
            $update_sql = "UPDATE khachhang SET password = '$new_pass_md5' WHERE email = '$email'";

            if ($conn->query($update_sql) === TRUE) {
                $msg = "✅ Đổi mật khẩu thành công!";
                header("Location: dangnhap.php");
            } else {
                $msg = "Lỗi khi cập nhật mật khẩu: " . $conn->error;
            }
        }
    } else {
        $msg = "❌ Không tìm thấy người dùng!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="../img/fav-icon.png" rel="icon" type="image/vnd.microsoft.icon">
    <title>Sửa thông tin</title>
    <!-- <link rel="icon" href="img/icon.jpg" title="logo" /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
   <link rel="stylesheet" href="../css/webstyle.css"/> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJqLz0P2Kj2q69/7f/3gD+6dI/YkG8XzY5I/p1gE4g4j2o724T0p+L+6lD8X6oEw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <script defer src="/scripts/web-layout.js"></script>
    <script defer src="/scripts/homepage.js"></script>
    <!--<link rel="stylesheet" href="index.css">-->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;700;900&family=Open+Sans:wght@300;400;500;600;700;800&family=Nunito:wght@300;400;500;600;700;800;900&family=Source+Sans+Pro:wght@300;400;600;700;900&display=swap" rel="stylesheet">
  </head>
<body  class="w3-container" >
     <header class="main-header">
        <div class="header-container">
            <div class="header-logo">
                <a href="trangchu.html" style="color: #ffffff; text-decoration: none; font-size: 24px; font-weight: bold;">Vibe4</a>
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

   <article class="khungdungchung">
        <h2 class="w3-text-blue">🔒 Đổi mật khẩu</h2>

        <form class="w3-container w3-card-4 w3-light-grey" method="POST" style="max-width:500px;">
         <div class="thongtin">
            <label for="password">
                <i class="fa-solid fa-keyboard"></i></label>
            <input type="password" name="old_pass" id="old_pass"
                     placeholder="Vui lòng nhập mật khẩu cũ" required>
          </div>
          <div class="thongtin">
            <label for="password">
                <i class="fa-solid fa-key"></i></label>
            <input type="password" name="new_pass" id="new_pass"
                placeholder="Vui lòng nhập mật khẩu mới" required>
          </div>
          <div class="thongtin">
            <label for="password">
                <i class="fa-solid fa-clone"></i></label>
            <input type="password" name="confirm_pass" id="confirm_pass" 
                placeholder="Vui lòng nhập lại mật khẩu mới" required>
          </div>

        <div class="container_1">
                    <div class="logout box_1">
                        <a href="nguoidung.php" class="w3-bar-item w3-button w3-padding" id="logout">
                            <i class="fa-solid fa-right-from-bracket"></i> 
                        </a>
                    </div>
                    <div class="box_1 update_info" >
                        <button  type="submit" id="change">
                            <i class="fa-solid fa-user-check" ></i>
                        </button>
                    </div>
        </div>
        
          </form>

        <?php if ($msg != "") echo "<p class='w3-text-red w3-margin-top'><b>$msg</b></p>"; ?>
    </article>
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
