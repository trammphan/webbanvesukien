<?php
    include 'connect_1.php';

    $conditions = [];
    if (!empty($_GET['diadiem'])) {
        $conditions[] = "MaDD = '" . $conn->real_escape_string($_GET['diadiem']) . "'";
    }
    if (!empty($_GET['loai_sukien'])) {
        $conditions[] = "MaLSK = '" . $conn->real_escape_string($_GET['loai_sukien']) . "'";
    }

    $sql = "SELECT s.MaSK, s.TenSK, s.img_sukien, s.Tgian, MIN(sl.GiaVe) AS GiaThapNhat
            FROM sukien s JOIN sukien_loaive sl ON s.MaSK = sl.MaSK";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    $sql .= " GROUP BY s.MaSK";

    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sự kiện</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/footer.css">
        <link rel="stylesheet" href="../css/sukien.css">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;700;900&family=Open+Sans:wght@300;400;500;600;700;800&family=Nunito:wght@300;400;500;600;700;800;900&family=Source+Sans+Pro:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    </head>
    
    <body class="event" class="page-wrapper">
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

                <div class="header-actions">
                    <a href="dangnhap.php" class="btn-signin">Đăng nhập</a> 
                    <a href="dangky.php" class="btn-signup">Đăng ký</a> 
                </div>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <!-- Bộ lọc -->
            <form id="event-filter" class="filter-box">
                <button type="button" class="filter-toggle" onclick="toggleFilter()">
                    <i class="fa-solid fa-filter"></i>Bộ lọc
                </button>

                <div id="filter-details" style="display: none;">
                    <!-- Địa điểm -->
                    <label>Địa điểm:
                        <select class= 'filter-group' name="diadiem">
                            <option value="">-- Chọn địa điểm --</option>
                            <option value="HCM" <?= ($_GET['diadiem'] ?? '') == 'HCM' ? 'selected' : '' ?>>Hồ Chí Minh</option> <!-- Giữ lại lựa chọn của người dùng-->
                            <option value="HN" <?= ($_GET['diadiem'] ?? '') == 'HN' ? 'selected' : '' ?>>Hà Nội</option>
                            <option value="DL" <?= ($_GET['diadiem'] ?? '') == 'DL' ? 'selected' : '' ?>>Đà Lạt</option>
                            <option value="HY" <?= ($_GET['diadiem'] ?? '') == 'HY' ? 'selected' : '' ?>>Hưng Yên</option>
                        </select>
                    </label>
                    
                    <!-- Thể loại -->
                    <label>Thể loại:
                        <select class= 'filter-group' name="loai_sukien">
                            <option value="">-- Chọn thể loại --</option>
                            <option value="LSK03" <?= ($_GET['loai_sukien'] ?? '') == 'LSK03' ? 'selected' : '' ?>>Concert</option>
                            <option value="LSK02" <?= ($_GET['loai_sukien'] ?? '') == 'LSK02' ? 'selected' : '' ?>>Festival</option>
                            <option value="LSK01" <?= ($_GET['loai_sukien'] ?? '') == 'LSK01' ? 'selected' : '' ?>>Liveshow</option>
                        </select>
                    </label>

                    <!-- Nút -->
                    <div class="filter-buttons">
                        <button type="reset">Thiết lập lại</button>
                        <button type="submit">Áp dụng</button>
                    </div>
                </div>
            </form>
            
            <!-- Danh sách sự kiện-->
            <div id="event-list" class="grid-container">
                <?php while ($row = $result->fetch_assoc()): ?>
                <div class="event-card">
                    <img src="<?= $row['img_sukien'] ?>" alt="<?= $row['TenSK'] ?>" />
                    <h3><?= $row['TenSK'] ?></h3>
                    <p><strong>Từ:</strong> <?= number_format($row['GiaThapNhat']) ?> VND</p>
                    <p><strong>Thời gian:</strong> <?= $row['Tgian'] ?></p>
                    <a href="../php/chitietsk_1.php?MaSK=<?=urlencode($row['MaSK']) ?>" data-mask="<?= $row['MaSK'] ?>" onclick="trackEvent(this)">Xem chi tiết</a>
                </div>
                <?php endwhile; ?>
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

        <script src="../js/sukien.js"></script>
    </body>
</html>