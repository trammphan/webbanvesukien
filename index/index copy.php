
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Phạm Huỳnh Ngân">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="./images/fav-icon.png" rel="icon" type="image/vnd.microsoft.icon">
    <title>Trang chủ</title>
        <link rel="stylesheet" href="../index/css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;700;900&family=Open+Sans:wght@300;400;500;600;700;800&family=Nunito:wght@300;400;500;600;700;800;900&family=Source+Sans+Pro:wght@300;400;600;700;900&display=swap" rel="stylesheet">
</head>

<body>
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
            <a href="../PTHT-Web/dangnhap.php" class="btn-signin">Đăng nhập</a> 
            <a href="../PTHT-Web/dangky.php" class="btn-signup">Đăng ký</a> 
        </div>
                </div>
            </div>
        </div>
    </header>
    <nav class="category-bar">
        <div class="category-container">
            <ul class="category-list">
                <li><a href="../sukien/sukien.html?loai_sukien=LSK03" class="category-item active">Concert🔥</a></li>
                <li><a href="../sukien/sukien.html?loai_sukien=LSK02" class="category-item">Festival</a></li>
                <li><a href="../sukien/sukien.html?loai_sukien=LSK01" class="category-item">Liveshow</a></li>
            </ul>
        </div>
    </nav>
    <section class="hero-banner">
        
        <div class="banner-logo">
            <span class="banner-logo-text">Vibe4</span>
        </div>

        <h1 class="banner-title">Khám Phá Thế Giới Sự Kiện Tuyệt Vời</h1>
        <p class="banner-subtitle">Mua vé hòa nhạc, concert và các lễ hội âm nhạc một cách dễ dàng và nhanh chóng.</p>
        <div class="banner-actions">
          <a href="#dat-ve" class="btn-book-now">Đặt vé ngay</a>
          <a href="../PTHT-Web/dangnhap.php" class="btn-banner-login">Đăng nhập</a>
        </div>
      </div>
    </section>

    <main>
        <!-- <section id="features" class="features-section">
            <div class="container text-center">
              <h2 class="section-title">The Most Trusted Platform for Digital Currency</h2>
              <p class="section-description">Discover powerful tools designed to help you securely buy, sell, and manage your crypto assets.</p>
              <div class="features-grid">
                <div class="feature-box">
                  <img src="assets/img/secure.svg" class="feature-icon bounce" alt="Secure Storage">
                  <h4>Secure Storage</h4>
                  <p>Your crypto is protected with advanced encryption and cold wallets.</p>
                </div>
                <div class="feature-box">
                  <img src="assets/img/free.svg" class="feature-icon bounce" alt="Free to Use">
                  <h4>Free to Use</h4>
                  <p>Track your portfolio and manage your account with no hidden fees.</p>
                </div>
                <div class="feature-box">
                  <img src="assets/img/realtime.svg" class="feature-icon bounce" alt="Real-Time Data">
                  <h4>Real-Time Price Data</h4>
                  <p>Get live updates from top global exchanges 24/7.</p>
                </div>
              </div>
            </div>
        </section> -->
        
        <section class="event-section special-events">
            <h2 class="section-title"><i class="fas fa-star"></i> Sự kiện Đặc biệt</h2>
            
            <div class="event-carousel">
                <div class="event-card">
                    <a href="#link-sk-dac-biet-1">
                        <div class="card-image-wrapper">
                            <img src="./images/event1.jfif" alt="Hòa nhạc EDM" class="card-image"> 
                            <span class="event-tag special-tag">VIP</span>
                        </div>
                        <div class="card-info">
                            <h3 class="event-name">[CAT&MOUSE] Đêm diễn Vũ Cát Tường</h3>
                            <p class="event-date">20:00, 24/12/2025</p>
                            <p class="event-price">Từ <span class="price-value">750.000</span> đ</p>
                        </div>
                    </a>
                </div>

                <div class="event-card">
                    <a href="#link-sk-dac-biet-2">
                        <div class="card-image-wrapper">
                            <img src="./images/event2.jpg" alt="Workshop độc quyền" class="card-image"> 
                            <span class="event-tag special-tag">Độc quyền</span>
                        </div>
                        <div class="card-info">
                            <h3 class="event-name">CINÉ x VŨ. - Bảo Tàng Của Nuối Tiếc Private Show</h3>
                            <p class="event-date">09:00, 15/11/2025</p>
                            <p class="event-price">Từ <span class="price-value">499.000</span> đ</p>
                        </div>
                    </a>
                </div>

                <div class="event-card">
                    <a href="#link-sk-dac-biet-3">
                        <div class="card-image-wrapper">
                            <img src="./images/event3.webp" alt="Chung kết thể thao" class="card-image"> 
                            <span class="event-tag special-tag">Chung kết</span>
                        </div>
                        <div class="card-info">
                            <h3 class="event-name">G-DRAGON 2025 WORLD TOUR</h3>
                            <p class="event-date">19:00, 01/10/2025</p>
                            <p class="event-price">Từ <span class="price-value">200.000</span> đ</p>
                        </div>
                    </a>
                </div>
                
                <div class="event-card">
                    <a href="#link-sk-dac-biet-4">
                        <div class="card-image-wrapper">
                            <img src="./images/event4.jfif" alt="Gala nhạc kịch" class="card-image"> 
                            <span class="event-tag special-tag">Gala</span>
                        </div>
                        <div class="card-info">
                            <h3 class="event-name">GOm Show Tháng 10 - Hà Nội</h3>
                            <p class="event-date">20:00, 18/12/2025</p>
                            <p class="event-price">Từ <span class="price-value">350.000</span> đ</p>
                        </div>
                    </a>
                </div>
                
                <div class="event-card">
                    <a href="#link-sk-dac-biet-5">
                        <div class="card-image-wrapper">
                            <img src="./images/event5.jpg" alt="Triển lãm nghệ thuật" class="card-image"> 
                            <span class="event-tag special-tag">Sắp diễn ra</span>
                        </div>
                        <div class="card-info">
                            <h3 class="event-name">Triển lãm Nghệ thuật: Sắc Màu Thời Gian</h3>
                            <p class="event-date">10:00, 05/11/2025</p>
                            <p class="event-price">Từ <span class="price-value">120.000</span> đ</p>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <section class="event-section trending-events">
            <h2 class="section-title"><i class="fas fa-fire"></i> Sự kiện Xu hướng</h2>
            <div class="event-carousel">
                <?php
                include 'db_connect.php';

                function getMinPrice($conn, $MaSK){
                    $sql = "SELECT MIN(GiaVe) AS MinPrice FROM sukien_loaive WHERE MaSK = '$MaSK' ";
                    $result = $conn->query($sql);
                    if ($result && $result->numrows>0) {
                        $row = $result->fetch_assoc();
                        return number_format($row['MinPrice'], 0, ',', '.');
                    }
                }  

                $sqp_events = " SELECT MaSK, TenSK, Tgian FROM sukien WHERE Tgian >= NOW() ORDER BY Tgian ASC LIMIT 5 "; //Lấy 5 sự kiện sắp tới (từ hiện tại trở về sau), gần nhất trước:
                $result_events = $conn->query($sql_events);

                if ($result_events->numrows > 0) {
                    while ($events = $reults_events->fetch_assoc()) {
                        $min_price = getMinPrice($conn, $events['MaSK']);
                        $date_obj = new DateTime($event['Tgian']);
                        $formatted_date = $date_obj->fornart('d/m/y');

                        $tag = '';
                        if ($event['MaLSK'] == 'LSK03'){
                            $tag = 'Concert🔥';
                        } elseif ($event['MaLSK'] == 'LSK02'){
                            $tag = 'Festival'
                        } else {
                            $tag = 'Liveshow';
                        }

                        ?>
                        <div class="event-card">
                            <a href="../sukien/chitiet.php?mask=<?php echo $event['MaSK']; ?>">
                                <div class="card-iamge-wrapper">
                                    <img src="<?php echo $event['img_sukien']; ?>" alt="<?php echo htmltrendingchars($event['TenSK']); ?>" class="card-iamge">
                                    <span class="event-tag"></span>
                                </div>
                        </div>

                    }
                }
                <div class="event-card">
                    <a href="#link-sk-xu-huong-1">
                        <div class="card-image-wrapper">
                            <img src="./images/event1.webp" alt="Concert thịnh hành" class="card-image"> 
                        </div>
                        <div class="card-info">
                            <h3 class="event-name">Đêm nhạc Acoustic: Tình Ca Mùa Đông</h3>
                            <p class="event-date">19:30, 08/01/2026</p>
                            <p class="event-price">Từ <span class="price-value">400.000</span> đ</p>
                        </div>
                    </a>
                </div>
                
                <div class="event-card">
                    <a href="#link-sk-xu-huong-2">
                        <div class="card-image-wrapper">
                            <img src="./images/event2.jpg" alt="Hội thảo hot" class="card-image"> 
                        </div>
                        <div class="card-info">
                            <h3 class="event-name">Hội thảo Tài Chính: Đầu tư Bền Vững 2026</h3>
                            <p class="event-date">14:00, 20/01/2026</p>
                            <p class="event-price">Từ <span class="price-value">300.000</span> đ</p>
                        </div>
                    </a>
                </div>
                
                <div class="event-card">
                    <a href="#link-sk-xu-huong-3">
                        <div class="card-image-wrapper">
                            <img src="./images/event3.webp" alt="Hài độc thoại" class="card-image"> 
                        </div>
                        <div class="card-info">
                            <h3 class="event-name">Stand-up Comedy: Cười Xuyên Đêm</h3>
                            <p class="event-date">21:00, 29/11/2025</p>
                            <p class="event-price">Từ <span class="price-value">250.000</span> đ</p>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <section class="event-section for-you-events">
            <h2 class="section-title"><i class="fas fa-user-circle"></i> Dành cho Bạn</h2>
            <div class="event-carousel">
                <div class="event-card">
                    <a href="#link-sk-for-you-1">
                        <div class="card-image-wrapper">
                            <img src="./images/event1.webp" alt="Gợi ý cá nhân" class="card-image"> 
                        </div>
                        <div class="card-info">
                            <h3 class="event-name">Phim Điện Ảnh: Kẻ Vô Danh</h3>
                            <p class="event-date">Các suất chiếu hàng ngày</p>
                            <p class="event-price">Từ <span class="price-value">150.000</span> đ</p>
                        </div>
                    </a>
                </div>

                <div class="event-card">
                    <a href="#link-sk-for-you-2">
                        <div class="card-image-wrapper">
                            <img src="./images/event2.jpg" alt="Gợi ý cá nhân" class="card-image"> 
                        </div>
                        <div class="card-info">
                            <h3 class="event-name">Voucher Ẩm Thực: Buffet Lẩu & Nướng</h3>
                            <p class="event-date">Hạn sử dụng: 31/03/2026</p>
                            <p class="event-price">Từ <span class="price-value">199.000</span> đ</p>
                        </div>
                    </a>
                </div>

                <div class="event-card">
                    <a href="#link-sk-for-you-3">
                        <div class="card-image-wrapper">
                            <img src="./images/event3.webp" alt="Gợi ý cá nhân" class="card-image"> 
                        </div>
                        <div class="card-info">
                            <h3 class="event-name">Lớp học vẽ: Kỹ thuật tranh sơn dầu</h3>
                            <p class="event-date">18:30, Thứ Tư hàng tuần</p>
                            <p class="event-price">Từ <span class="price-value">900.000</span> đ</p>
                        </div>
                    </a>
                </div>
            </div>
        </section> -->

        <div style="height: 50px;"></div> 
    </main>
    
    <footer>
        <div class="footer-container">
            
            <div class="footer-col footer-branding">
                <h3 class="footer-logo">Vibe4</h3>
                <p>Vibe4 – Nền tảng mua vé sự kiện đa dạng: liveshow, festival, concert và các hoạt động giải trí uy tín tại Việt Nam, lựa chọn hàng đầu cho những ai yêu thích văn hóa và giải trí.</p>
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
    </footer>
</body>
</html>