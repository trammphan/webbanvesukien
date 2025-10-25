<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chi tiết sự kiện</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="stylesheet" href="../index/footer-header.css">
        <link rel="stylesheet" href="chitietsk_1.css">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;700;900&family=Open+Sans:wght@300;400;500;600;700;800&family=Nunito:wght@300;400;500;600;700;800;900&family=Source+Sans+Pro:wght@300;400;600;700;900&display=swap" rel="stylesheet">
        <!--Link tham khảo: https://codepen.io/verpixelt/pen/AXBdKy-->
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
            if (isset($_GET['MaSK'])) {
            $maSK = $_GET['MaSK'];

            $result = $conn->query("SELECT * FROM sukien s JOIN diadiem d on s.MaDD = d.MaDD 
                                                            JOIN loaisk l on s.MaLSK = l.MaloaiSK
                                    WHERE s.MaSK = '$maSK'");
            $row = $result->fetch_assoc();
            
            $ve_result = $conn->query("SELECT v.TenLV, v.MoTa, sl.GiaVe
                                    FROM sukien_loaive sl 
                                    JOIN loaive v ON sl.MaLoaiVe = v.MLV
                                    WHERE sl.MaSK = '$maSK'
                                    ORDER BY sl.GiaVe DESC");
            } 
            else {
                echo "<p>Không tìm thấy sự kiện.</p>";
                exit;
            }
        ?>
        <main>
            <!-- Hình vé -->
            <div class="cardWrap">
                <div class= "card">
                    <div class="cardLeft">
                        <h1><?=htmlspecialchars($row['TenLoaiSK'])?></h1>
                        <div class="title">
                            <h2><?=htmlspecialchars($row['TenSK'])?></h2>
                        </div>

                        <div class="name">
                            <span>Địa Điểm</span>
                            <h2><?=htmlspecialchars($row['TenTinh'])?></h2>
                        </div>

                        <div class="seat">
                            <span>Giá vé</span>
                            <h2><?=number_format($row['Gia'], 0, ',', '.')?>VND++</h2>
                        </div>

                        <div class="time">
                            <span>Thời gian</span>
                            <h2><?=date("d/m/Y", strtotime($row['Tgian']))?></h2>
                        </div>
                    </div>

                    <div class="cardRight" style="background-image: url('<?=htmlspecialchars($row['img_sukien'])?>'); background-size: cover; background-position: center;">
                        <div class= "blurOverlay"></div>
                        
                        <div class="button">
                            <a class="buy" href="ticket_page.php?MaSK=<?=htmlspecialchars($maSK)?>">MUA VÉ</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mô tả sự kiện -->
            <div class="mota" >
                <h2 class= "tieude">Giới thiệu sự kiện</h2>
                <p class= "noidung"><?=htmlspecialchars($row['mota'])?></p>

                <!-- <span class= "loaive">Các loại vé: <b><?=htmlspecialchars($loaive['Danhsachve'])?></b>
                </span> -->
            </div>
            <!-- Chi tiết các loại vé -->
            <div class="chitietve"> <h2 class="tieude">Các loại vé</h2>
                <?php
                    while ($ve_row = $ve_result->fetch_assoc()) {
                        echo '<div class="loaiVeItem">
                                <h3 class="tenLoaiVe">'.htmlspecialchars($ve_row['TenLV']).'</h3>
                                <p class="moTaVe">'.htmlspecialchars($ve_row['MoTa']).'</p>
                                <p class="giaVe">Giá vé: <strong>'.number_format($ve_row['GiaVe'], 0, ',', '.').' VND</strong></p>
                            </div>';    
                    }
                ?>
            </div>
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
        </footer>
    </body>
</html>