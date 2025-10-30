<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chi tiết sự kiện</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/footer.css">
        <link rel="stylesheet" href="../css/chitietsk_1.css">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;700;900&family=Open+Sans:wght@300;400;500;600;700;800&family=Nunito:wght@300;400;500;600;700;800;900&family=Source+Sans+Pro:wght@300;400;600;700;900&display=swap" rel="stylesheet">
        <!--Link tham khảo: https://codepen.io/verpixelt/pen/AXBdKy-->
    </head>

    <body>
        <!-- Header -->
        <header class="main-header">
            <div class="header-container">
                <div class="header-logo">
                    <a href="index.php" style="color: #ffffff; text-decoration: none; font-size: 24px; font-weight: bold;">Vibe4</a>
                </div>

                <div class="header-search">
                    <form action="index.php" method="get"> 
                        <input type="text" placeholder="Tìm kiếm sự kiện, địa điểm..." name="q" class="search-input" value="<?php echo htmlspecialchars($search_query ?? ''); ?>">
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

                <?php 
                    include __DIR__ . '/../php/header_actions.php'; 
                ?>
                    </div>
                </div>
            </div>
        </header>

        <?php
            include 'connect_1.php';
            if (isset($_GET['MaSK'])) {
            $maSK = $_GET['MaSK'];

            $result = $conn->query("SELECT * FROM sukien s JOIN diadiem d on s.MaDD = d.MaDD 
                                                            JOIN loaisk l on s.MaLSK = l.MaloaiSK
                                    WHERE s.MaSK = '$maSK'");
            $row = $result->fetch_assoc();
            
            $ve_result = $conn->query("SELECT TenLoai, Gia
                                    FROM loaive
                                    WHERE MaSK = '$maSK'
                                    ORDER BY Gia DESC");
            $row_1 = $ve_result->fetch_assoc();

             if ($ve_result->num_rows > 0) {
                $ve_result->data_seek(0); 
            }
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

                        <div class="price">
                            <span>Giá vé</span>
                            <h2><?=number_format($row_1['Gia'], 0, ',', '.')?>VND++</h2>
                        </div>

                        <div class="time">
                            <span>Thời gian</span>
                            <h2><?= "Từ: " . (new DateTime($row['Tgian']))->format('H:i, d/m/Y') ?></h2>
                        </div>
                    </div>

                    <div class="cardRight" style="background-image: url('<?=htmlspecialchars($row['img_sukien'])?>'); background-size: cover; background-position: center;">
                        <div class= "blurOverlay"></div>
                        
                                                <!-- *** THAY ĐỔI NÚT MUA VÉ *** -->
                        <div class="button">
                            <?php
                            // Kiểm tra xem cookie 'email' (dấu hiệu đã đăng nhập) có tồn tại không
                            if (isset($_COOKIE['email']) && !empty($_COOKIE['email'])) {
                                // Nếu ĐÃ ĐĂNG NHẬP: Trỏ đến trang mua vé
                                echo '<a class="buy" href="ticket_page.php?MaSK=' . htmlspecialchars($maSK) . '">MUA VÉ</a>';
                            } else {
                                // Nếu CHƯA ĐĂNG NHẬP: Trỏ đến trang đăng nhập
                                // Lấy URL hiện tại
                                $current_page_url = $_SERVER['REQUEST_URI'];
                                // Thêm URL này vào link đăng nhập để sau khi login thành công có thể quay lại
                                $login_url = 'dangnhap.php?redirect=' . urlencode($current_page_url);
                                echo '<a class="buy" href="' . $login_url . '">MUA VÉ</a>';
                            }
                            ?>
                        </div>
                        <!-- *** KẾT THÚC THAY ĐỔI *** -->
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
                                <h3 class="tenLoaiVe">'.htmlspecialchars($ve_row['TenLoai']).'</h3>
                                <p class="giaVe">Giá vé: <strong>'.number_format($ve_row['Gia'], 0, ',', '.').' VND</strong></p>
                            </div>';    
                    }
                ?>
            </div>
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