<?php
    // FIX 1: KHỞI ĐỘNG SESSION ĐỂ ĐỌC THÔNG TIN ĐĂNG NHẬP
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Lấy thông tin auth (giống hệt header.php)
    require_once __DIR__ . '/../php/auth.php';
    $user_table = $_SESSION['user_table'] ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chi tiết sự kiện</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" href="../css/header.css">
        <link href="../img/fav-icon.png" rel="icon" type="image/vnd.microsoft.icon">
        <link rel="stylesheet" href="../css/footer.css">
        <link rel="stylesheet" href="../css/chitietsk_1.css">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;700;900&family=Open+Sans:wght@300;400;500;600;700;800&family=Nunito:wght@300;400;500;600;700;800;900&family=Source+Sans+Pro:wght@300;400;600;700;900&display=swap" rel="stylesheet">
        </head>

    <body>
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
                           <!-- FIX 2: ĐỒNG BỘ HEADER VỚI header.php -->
                           <?php if (is_logged_in()): ?>
                                
                                <?php // Chỉ 'nhatochuc' mới thấy "Tạo sự kiện" ?>
                                <?php if ($user_table === 'nhatochuc'): ?>
                                    <li><a href="tao_su_kien.php">Tạo sự kiện</a></li>
                                <?php endif; ?>

                                <?php // Chỉ 'khachhang' mới thấy "Vé của tôi" ?>
                                <?php if ($user_table === 'khachhang'): ?>
                                    <li><a href="lich_su_mua_ve.php">Vé của tôi</a></li>
                                <?php endif; ?>
                                
                                <?php // Thêm liên kết cho Admin và NV Soát Vé (nếu cần) ?>
                                 <?php if ($user_table === 'quantrivien'): ?>
                                    <li><a href="admin.php">Trang Admin</a></li>
                                <?php endif; ?>
                                 <?php if ($user_table === 'nhanviensoatve'): ?>
                                    <li><a href="nhanvien.php">Trang Soát Vé</a></li>
                                <?php endif; ?>
                                
                            <?php endif; ?>
                        </ul>
                    </nav>

                    <?php 
                      // FIX 3: SỬ DỤNG file header_actions.php CHUẨN
                      // Nó sẽ tự động hiển thị "Đăng nhập" hoặc "Tên User/Đăng xuất"
                      if (file_exists(__DIR__ . '/../php/header_actions.php')) {
                          include __DIR__ . '/../php/header_actions.php';
                      } else {
                          // Fallback nếu không tìm thấy file
                          echo '<div class="header-actions">
                                    <a href="dangnhap.php" class="btn-login">Đăng nhập</a>
                                    <a href="dangky.php" class="btn-signup">Đăng ký</a>
                                </div>';
                      }
                    ?>
                
                </div>
            </div>
        </header>

        <?php
            include 'connect_1.php';
            if (isset($_GET['MaSK'])) {
                $maSK = $_GET['MaSK'];
                
                $result = $conn->query("SELECT l.TenLoaiSK, s.TenSK, s.img_sukien, s.Tgian, s.mota, d.TenTinh, MIN(lv.Gia) AS MinPrice
                                        FROM sukien s JOIN diadiem d on s.MaDD = d.MaDD 
                                                    JOIN loaisk l on s.MaLSK = l.MaloaiSK
                                                    JOIN loaive lv on lv.MaSK = s.MaSK
                                        WHERE s.MaSK = '$maSK'
                                        GROUP BY s.MaSK");
                
                $row = $result->fetch_assoc();
                
                $ve_result = $conn->query("SELECT TenLoai, Gia
                                        FROM loaive
                                        WHERE MaSK = '$maSK'
                                        ORDER BY Gia DESC");
            }
            else {
                echo "<p>Không tìm thấy sự kiện.</p>";
                exit;
            }
        ?>
        <main>
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
                            <h2><?=is_numeric($row['MinPrice']) ? number_format($row['MinPrice'], 0, ',', '.') : $row['MinPrice']?>VND++</h2>
                        </div>

                        <div class="time">
                            <span>Thời gian</span>
                            <h2><?= "Từ: " . (new DateTime($row['Tgian']))->format('H:i, d/m/Y') ?></h2>
                        </div>
                    </div>

                    <div class="cardRight" style="background-image: url('<?=htmlspecialchars($row['img_sukien'])?>'); background-size: cover; background-position: center;">
                        <div class= "blurOverlay"></div>
                        
                        <div class="button">
                            <?php
                            // ******** FIX 4: ĐÂY LÀ THAY ĐỔI QUAN TRỌNG ********
                            // Kiểm tra xem SESSION 'email' (dấu hiệu đã đăng nhập) có tồn tại không
                            if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
                                // Nếu ĐÃ ĐĂNG NHẬP: Trỏ đến trang mua vé
                                echo '<a class="buy" href="ticket_page.php?MaSK=' . htmlspecialchars($maSK) . '">MUA VÉ</a>';
                            } else {
                                // Nếu CHƯA ĐĂNG NHẬP: Trỏ đến trang đăng nhập

                                // 1. Xác định URL mục tiêu (trang mua vé)
                                $target_url = 'ticket_page.php?MaSK=' . htmlspecialchars($maSK);
                                
                                // 2. Tạo URL đăng nhập, đính kèm URL mục tiêu vào tham số 'redirect'
                                $login_url = 'dangnhap.php?redirect=' . urlencode($target_url);
                                
                                // 3. In nút "MUA VÉ" trỏ đến trang đăng nhập
                                echo '<a class="buy" href="' . $login_url . '">MUA VÉ</a>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mota" >
                <h2 class= "tieude">Giới thiệu sự kiện</h2>
                <p class= "noidung"><?=htmlspecialchars($row['mota'])?></p>
                </div>
            <div class="chitietve"> <h2 class="tieude">Các loại vé</h2>
                <?php
                    // Đảm bảo $ve_result là đối tượng hợp lệ trước khi lặp
                    if ($ve_result !== FALSE && $ve_result->num_rows > 0) {
                        while ($ve_row = $ve_result->fetch_assoc()) {
                            echo '<div class="loaiVeItem">
                                    <h3 class="tenLoaiVe">'.htmlspecialchars($ve_row['TenLoai']).'</h3>
                                    <p class="giaVe">Giá vé: <strong>'.number_format($ve_row['Gia'], 0, ',', '.').' VND</strong></p>
                                </div>';    
                        }
                    } else {
                        echo "<p>Thông tin loại vé đang được cập nhật.</p>";
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