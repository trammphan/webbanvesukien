
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJqLz0P2Kj2q69/7f/3gD+6dI/YkG8XzY5I/p1gE4g4j2o724T0p+L+6lD8X6oEw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
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
        <article class="dangky">
        <h2>Đăng Ký</h2>
        <form action="luuthongtin.php" method="post" id="form_dk">

              <div class="thongtin">
              <label for="user_name">
                <i class="fa-solid fa-book-open-reader"></i>
                <input
                  type="text"
                  id="user_name"
                  placeholder="Vui lòng nhập họ và tên"
                  name="user_name"
                />
              </label>
            </div>

            <div class="thongtin">
              <label for="gender">
                <i class="fa-solid fa-venus-mars"></i>
                <select name="gender" id="gender">
                  <option value="sample">Giới tính</option>
                  <option value="male">Nam</option>
                  <option value="female">Nữ</option>
                  <option value="other">Khác</option>
                </select>
              </label>
            </div>

            <div class="thongtin">
              <label for="birthday">
                <i class="fa-solid fa-cake-candles"></i>
                <input type="date" name="birthday" id="birthday" />
              </label>
            </div>

            <div class="thongtin">
              <label for="tel">
                <i class="fa-solid fa-square-phone"></i>
                <input
                  type="tel"
                  name="tel"
                  id="tel"
                  placeholder="Vui lòng nhập số điện thoại"
                />
              </label>
            </div>

            <div class="thongtin">
              <label for="address">
                <i class="fa-solid fa-location-dot"></i>
                <input
                  type="text"
                  name="address"
                  id="address"
                  placeholder="Vui lòng nhập địa chỉ liên lạc" 
                />
              </label>
            </div>

            <div class="thongtin">
              <label for="email">
                <i class="fa-solid fa-envelope"></i>
                <input
                  type="email"
                  id="email"
                  name="email"
                  placeholder="Vui lòng nhập email đăng nhập"
                />
              </label>
            </div>

            <div class="thongtin">
              <label for="password">
                <i class="fa-solid fa-lock"></i>
                <input
                  type="password"
                  name="password"
                  id="password"
                  placeholder="Vui lòng nhập mật khẩu"
                />
              </label>
            </div>

            </div>
        <div id="dang_ky">
            <input type="submit" name="submit" value="Đăng Ký" id="submit"/>
        </div>
        </form>
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