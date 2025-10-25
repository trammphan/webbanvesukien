<?php
// Lưu ý: Đảm bảo file db_connect.php đã được cấu hình đúng 
// và database 'qlysukien' đã được tạo và import dữ liệu.

// Nhúng file kết nối CSDL (Giả định file nằm cùng cấp hoặc đường dẫn đã đúng)
include 'db_connect.php'; 

/**
 * Hàm lấy giá vé thấp nhất cho một sự kiện từ bảng sukien_loaive.
 * @param mysqli $conn Đối tượng kết nối MySQLi.
 * @param string $MaSK Mã sự kiện.
 * @return string Giá vé thấp nhất đã được định dạng (hoặc chuỗi rỗng nếu không có giá).
 */
function getMinPrice($conn, $MaSK) {
    // Sử dụng Prepared Statement là cách an toàn nhất, nhưng tôi giữ cấu trúc truy vấn trực tiếp của bạn
    $sql = "SELECT MIN(GiaVe) AS MinPrice FROM sukien_loaive WHERE MaSK = '$MaSK' ";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (is_numeric($row['MinPrice'])) {
            // Chỉ format khi giá là số hợp lệ
            return number_format($row['MinPrice'], 0, ',', '.'); 
        }
    }
    // Trả về chuỗi rỗng
    return ''; 
}

/**
 * Hàm hiển thị một section sự kiện với tính năng cuộn ngang.
 */
function renderEventCards($conn, $sql_query, $title, $icon_class, $tag_logic_func) {
    $result_events = $conn->query($sql_query);
    ?>
    <section class="event-section <?php echo strtolower(str_replace(' ', '-', $title)); ?>">
        <h2 class="section-title"><i class="<?php echo $icon_class; ?>"></i> <?php echo $title; ?></h2>
        
        <div class="event-carousel">
            <?php
            if ($result_events && $result_events->num_rows > 0) {
                while($event = $result_events->fetch_assoc()) {
                    // Lấy giá thấp nhất (sẽ là "" nếu không tìm thấy giá)
                    $min_price = getMinPrice($conn, $event['MaSK']);
                    
                    $date_obj = new DateTime($event['Tgian']);
                    $formatted_date = $date_obj->format('d/m/Y'); 
                    $tag = $tag_logic_func($event);
                    ?>
                    <div class="event-card">
                        <a href="../sukien/chitiet.php?mask=<?php echo $event['MaSK']; ?>">
                            <div class="card-image-wrapper">
                                <img src="<?php echo $event['img_sukien']; ?>" alt="<?php echo htmlspecialchars($event['TenSK']); ?>" class="card-image"> 
                                <span class="event-tag special-tag"><?php echo $tag; ?></span>
                            </div>
                            <div class="card-info">
                                <h3 class="event-name"><?php echo htmlspecialchars($event['TenSK']); ?></h3>
                                <p class="event-date">20:00, <?php echo $formatted_date; ?></p>
                                <p class="event-price">
                                    <?php echo !empty($min_price) ? 'Từ' : ''; ?> 
                                    <span class="price-value"><?php echo $min_price; ?></span> 
                                    <?php echo !empty($min_price) ? 'đ' : ''; ?>
                                </p>
                            </div>
                        </a>
                    </div>
                    <?php
                }
            } else {
                echo "<p style='padding: 20px; text-align: center; color: white;'>Hiện chưa có sự kiện nào trong danh mục này.</p>";
            }
            ?>
        </div>
    </section>
    <?php
}
?>

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
                <a href="../index/index.php" style="color: #ffffff; text-decoration: none; font-size: 24px; font-weight: bold;">Vibe4</a>
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
        
        <?php
        // --- LOGIC CHO CÁC PHẦN ---

        // #1: Sự kiện Đặc biệt (Lấy 8 sự kiện sắp tới sớm nhất)
        $sql_special = "SELECT MaSK, TenSK, Tgian, img_sukien, MaLSK FROM sukien WHERE Tgian >= CURDATE() ORDER BY Tgian ASC LIMIT 8";
        $tag_special = function($event) {
            if ($event['MaLSK'] == 'LSK03') {
                return 'Concert🔥';
            } elseif ($event['MaLSK'] == 'LSK02') {
                return 'Festival';
            }
            return 'Liveshow';
        };

        renderEventCards($conn, $sql_special, 'Sự kiện Đặc biệt', 'fas fa-star', $tag_special);

        // ---

        // #2: Sự kiện Xu hướng (Lấy 8 sự kiện có giá cao nhất)
        $sql_trending = "SELECT s.MaSK, s.TenSK, s.Tgian, s.img_sukien, s.MaLSK, MAX(sl.GiaVe) AS MaxPrice
                        FROM sukien s
                        JOIN sukien_loaive sl ON s.MaSK = sl.MaSK
                        GROUP BY s.MaSK
                        ORDER BY MaxPrice DESC
                        LIMIT 8";

        $tag_trending = function($event) {
            return 'HOT 👑';
        };
        renderEventCards($conn, $sql_trending, 'Sự kiện Xu hướng', 'fas fa-fire', $tag_trending);

        // ---

        // #3: Dành cho Bạn (Lấy 8 sự kiện ngẫu nhiên sắp tới)
        $sql_foryou = "SELECT MaSK, TenSK, Tgian, img_sukien, MaLSK FROM sukien WHERE Tgian >= CURDATE() ORDER BY RAND() LIMIT 8";

        $tag_foryou = function($event) {
            return 'Gợi ý';
        };

        renderEventCards($conn, $sql_foryou, 'Dành cho Bạn', 'fas fa-user-circle', $tag_foryou);

        // ---

        // 4. Đóng kết nối
        $conn->close();
        ?>
        
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