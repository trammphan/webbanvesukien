<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chi tiết sự kiện</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="stylesheet" href="../index/footer-header.css">
        <link rel="stylesheet" href="chitietsk.css">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;700;900&family=Open+Sans:wght@300;400;500;600;700;800&family=Nunito:wght@300;400;500;600;700;800;900&family=Source+Sans+Pro:wght@300;400;600;700;900&display=swap" rel="stylesheet">
        <!--Link tham khảo: https://codepen.io/z-/pen/MJKNJZ, https://codepen.io/verpixelt/pen/AXBdKy-->
    </head>

    <body>
        <!-- Header -->
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
                        <a href="#signin" class="btn-signin">Đăng nhập</a>
                        <button class="btn-signup">Đăng ký</button>
                    </div>
                </div>
            </div>
        </header>

        <?php
            include '../sukien/connect.php';
            $result = $conn->query("SELECT * FROM sukien s JOIN diadiem d on s.MaDD = d.MaDD JOIN loaisk l on s.MaLSK = l.MaloaiSK");

            while($row = $result->fetch_assoc()){
        ?>
        <main>
            <!-- Hình vé -->
            <div class="widget --flex-column" data-type="ticket">
                <div class="top --flex-column">
                    <div class="bandname -bold"><?=htmlspecialchars($row['TenSK'])?></div>
                    <div class="tourname"><?=htmlspecialchars($row['TenLoaiSK'])?></div>
                    <img src=<?=htmlspecialchars($row['img_sukien'])?> alt=<?=htmlspecialchars($row['TenSK'])?> /> 
                    <!-- Hàm htmlspecialchars()chuyển đổi các ký tự đặc biệt thành dạng an toàn trong HTML, giúp ngăn chặn lỗi 
                     hiển thị và chống lại  XSS (Cross-site Scripting) -->

                    <div class="deetz --flex-row-j!sb">
                        <div class="event --flex-column">
                            <div class="date"><?=date("d/m/Y", strtotime($row['Tgian']))?></div>
                            <!-- Hàm strtotime() chuyển chuỗi ngày từ CSDL thành timestamp 
                             để hàm date() có thể xử lý-->
                            <div class="location -bold"><?=htmlspecialchars($row['TenTinh'])?></div>
                        </div>

                        <div class="price --flex-column">
                            <div class="label">Tầm giá</div>
                            <div class="cost -bold"><?=number_format($row['Gia'], 0, ',', '.')?>đ++</div>
                        </div>
                    </div>
                </div>

                <div class="rip"></div>

                <div class="bottom --flex-row-j!sb">
                    <a class="buy" href="#">BUY TICKET</a>
                </div>
            </div>

            <!-- Mô tả thông tin sự kiện -->
            <div class="description-box">
                <h2>MÔ TẢ SỰ KIỆN</h2>
                <p><?=htmlspecialchars($row['mota'])?></p>
            </div>
        </main>
        <?php
            }
        ?>
        
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
        </footer>
    </body>
</html>