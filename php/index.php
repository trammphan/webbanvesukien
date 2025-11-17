<?php
include 'db_connect.php';

$additional_css = ['index.css']; 

function getMinPrice($conn, $MaSK) {
    $sql = "SELECT MIN(Gia) AS MinPrice FROM loaive WHERE MaSK = '$MaSK' ";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (is_numeric($row['MinPrice'])) {
            return number_format($row['MinPrice'], 0, ',', '.'); 
        }
    }
    return ''; 
}

function renderEventCards($conn, $sql_query, $title, $icon_class, $tag_logic_func, $section_id = '') { 
    $result_events = $conn->query($sql_query);
    ?>
    <section class="event-section" <?php echo !empty($section_id) ? "id=\"$section_id\"" : ""; ?>>
        <h2 class="section-title"><i class="<?php echo $icon_class; ?>"></i> <?php echo $title; ?></h2>
        
        <div class="event-carousel">
            <?php
            if ($result_events && $result_events->num_rows > 0) {
                while($event = $result_events->fetch_assoc()) {
                    $min_price = getMinPrice($conn, $event['MaSK']);
                    
                    $date_obj = isset($event['Tgian']) ? new DateTime($event['Tgian']) : null;
                    $formatted_date_time = $date_obj ? $date_obj->format('H:i d/m/Y') : 'Không rõ';                    
                    $tag = $tag_logic_func($event);
                    ?>
                    <div class="event-card">
                        <a href="chitietsk_1.php?MaSK=<?php echo $event['MaSK']; ?>">
                            <div class="card-image-wrapper">
                                <img src="<?php echo $event['img_sukien']; ?>" alt="<?php echo htmlspecialchars($event['TenSK']); ?>" class="card-image"> 
                                <span class="event-tag special-tag"><?php echo $tag; ?></span>
                            </div>
                            <div class="card-info">
                                <h3 class="event-name"><?php echo htmlspecialchars($event['TenSK']); ?></h3>
                                <p class="event-date">Từ <?php echo $formatted_date_time; ?></p>
                                <p class="event-price">
                                    <span class="price-value"><?php echo $min_price; ?></span> 
                                    <?php echo !empty($min_price) ? 'VND++' : ''; ?>
                                </p>
                            </div>
                        </a>
                    </div>
                    <?php
                }
            } else {
                echo "<p style='padding: 20px; text-align: center; color: white;'>Không tìm thấy sự kiện nào.</p>";
            }
            ?>
        </div>
    </section>
    <?php
}

$search_query = "";
$sql_search = "";

if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $raw_query = trim($_GET['q']);
    $safe_search_query = $conn->real_escape_string($raw_query);
    
    $sql_search = "
        SELECT 
            s.MaSK, s.TenSK, s.Tgian, s.img_sukien, s.MaLSK 
        FROM 
            sukien s
        JOIN 
            diadiem dd ON s.MaDD = dd.MaDD
        WHERE 
            s.TenSK LIKE '%$safe_search_query%' 
            OR dd.TenTinh LIKE '%$safe_search_query%'
        ORDER BY 
            s.Tgian ASC
        LIMIT 22;
    ";
    
    $search_query = $raw_query;
}

require_once 'header.php'; 

if (empty($search_query)) {
?>
<main>
    <nav class="category-bar">
        <div class="category-container">
            <ul class="category-list">
                <li class="tooltip"><a href="sukien.php?loai_sukien=LSK03" class="category-item">Concert🔥</a>
                    <span class="tooltiptext">Concert là một buổi biểu diễn âm nhạc trực tiếp, chuyên nghiệp, quy mô lớn, tập trung vào trải nghiệm 
                                sân khấu hoành tráng, nơi nghệ sĩ hoặc ban nhạc trình diễn trước khán giả. Nó có thể là một buổi hòa nhạc cổ điển, hoặc một 
                                chương trình âm nhạc đại chúng như K-pop, US-UK.</span>
                </li>
                <li class="tooltip"><a href="sukien.php?loai_sukien=LSK02" class="category-item">Festival</a>
                    <span class="tooltiptext">Festival là sự kiện âm nhạc thu hút đông đảo khán giả, thường diễn ra theo chủ đề với quy mô rộng lớn như 
                                công viên, quảng trường, sân vận động...., thường kết hợp với các hoạt động khác như ăn uống, triển lãm nghệ thuật, đồ 
                                thủ công, khu vui chơi, hoạt động xã hội.</span>
                </li>
                <li class="tooltip"><a href="sukien.php?loai_sukien=LSK01" class="category-item">Liveshow</a>
                    <span class="tooltiptext">Liveshow âm nhạc là buổi biểu diễn trực tiếp của một nghệ sĩ hoặc nhóm nhạc, thường được tổ chức để thể hiện 
                                dấu ấn cá nhân, kỷ niệm sự nghiệp, hoặc ra mắt sản phẩm âm nhạc mới. Liveshow thường diễn ra trong không gian gần gũi như 
                                nhà hát, phòng trà, hoặc sân khấu nhỏ - nơi mà người nghệ sĩ chia sẻ cảm xúc, câu chuyện cá nhân, giao lưu trực tiếp với khán giả.</span>
                </li>
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
            <a href="#sukien-gan-day" class="btn-banner">Khám phá ngay</a>
        </div>
    </section>

    <!-- Nguồn tham khảo: https://codepen.io/studiojvla/pen/qVbQqW -->
    <div class="slider">
        <?php include 'connect_1.php';
        $result = $conn->query("SELECT MaSK, TenSK, img_sukien 
                FROM sukien
                WHERE Tgian >= DATE_ADD(CURDATE(), INTERVAL 1 DAY) -- Lấy sự kiện có thời gian bắt đầu trong phạm vi từ mai - 14 ngày tiếp theo đổ lại --
                    AND Tgian <= DATE_ADD(CURDATE(), INTERVAL 14 DAY)
                ORDER BY Tgian ASC");
        ?>
        <div class="slide-track">
            <?php
                $events= [];
                if ($result !== FALSE && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) { 
                        $events[] = $row;
                    }

                // Lặp 2 lần để tạo hiệu ứng quay vòng
                for ($i = 0; $i < 2; $i++) {
                    foreach ($events as $up_comming) {
            ?>
            <div class="slide">
                <a href="chitietsk_1.php?MaSK=<?=urlencode($up_comming['MaSK']) ?>" data-mask="<?= htmlspecialchars($up_comming['MaSK']) ?>" onclick="trackEvent(this)">
                    <img src="<?= htmlspecialchars($up_comming['img_sukien']) ?>" alt="<?= htmlspecialchars($up_comming['TenSK']) ?>"/>
                </a>
            </div>
            <?php 
                    }
                }
            }
            ?>
        </div>
    </div>
</main>
<?php
}
?> 
        
        <?php
        $tag_default = function($event) {
            if ($event['MaLSK'] == 'LSK03') {
                return 'Concert🔥';
            } elseif ($event['MaLSK'] == 'LSK02') {
                return 'Festival';
            }
            return 'Liveshow';
        };

        if (!empty($search_query)) {
            $tag_search = function($event) { 
                if ($event['MaLSK'] == 'LSK03') {
                    return 'Concert🔥';
                } elseif ($event['MaLSK'] == 'LSK02') {
                    return 'Festival';
                }
                return 'Liveshow';
            };
            renderEventCards($conn, $sql_search, "Kết quả tìm kiếm cho: \"$search_query\"", 'fas fa-search', $tag_search, 'search-results-top');

        } else {
            
            $sql_special = "SELECT MaSK, TenSK, Tgian, img_sukien, MaLSK 
                            FROM sukien 
                            WHERE Tgian >= CURDATE() 
                            ORDER BY Tgian 
                            ASC LIMIT 8";
            renderEventCards($conn, $sql_special, 'Sự kiện Gần đây', 'fas fa-star', $tag_default, 'sukien-gan-day'); 

            $sql_trending = "SELECT s.MaSK, s.TenSK, s.Tgian, s.img_sukien, s.MaLSK, s.luot_truycap
                            FROM sukien s
                            JOIN loaive lv ON s.MaSK = lv.MaSK
                            WHERE Tgian >= CURDATE()
                            GROUP BY s.MaSK
                            ORDER BY s.luot_truycap DESC
                            LIMIT 8";

            $tag_trending = function($event) { return 'HOT 👑'; };
            renderEventCards($conn, $sql_trending, 'Sự kiện Xu hướng', 'fas fa-fire', $tag_trending);

            $sql_foryou = "SELECT MaSK, TenSK, Tgian, img_sukien, MaLSK 
                            FROM sukien 
                            WHERE Tgian >= CURDATE() 
                            ORDER BY RAND() 
                            LIMIT 8";

            $tag_foryou = function($event) { return 'Gợi ý'; };

            renderEventCards($conn, $sql_foryou, 'Dành cho Bạn', 'fas fa-user-circle', $tag_foryou);
        }
        
        $conn->close();
        ?>

<?php
    require_once 'footer.php'; 
?>
<script src="../js/sukien.js"></script>
<script src="../js/index.js"></script>
