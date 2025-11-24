<?php
include 'db_connect.php';

$additional_css = ['index.css'];

function renderEventCards($conn, $sql_query, $title, $icon_class, $tag_logic_func, $section_id = '') {
    $result_events = $conn->query($sql_query);
    ?>
    <section class="event-section" <?php echo !empty($section_id) ? "id=\"$section_id\"" : ""; ?>>
        <h2 class="section-title"><i class="<?php echo $icon_class; ?>"></i> <?php echo $title; ?></h2>

        <div class="event-carousel">
            <?php
            if ($result_events && $result_events->num_rows > 0) {
                while($event = $result_events->fetch_assoc()) {
                    $min_price_formatted = '';
                    if (isset($event['MinPrice']) && is_numeric($event['MinPrice'])) {
                           $min_price_formatted = number_format($event['MinPrice'], 0, ',', '.');
                    }

                    $date_obj = isset($event['Tgian']) ? new DateTime($event['Tgian']) : null;
                    $formatted_date_time = $date_obj ? $date_obj->format('H:i d/m/Y') : 'Không rõ';
                    $tag = $tag_logic_func($event);
                    ?>
                    <div class="event-card">
                        <a href="chitietsk_1.php?MaSK=<?php echo $event['MaSK']; ?>" data-mask="<?php echo htmlspecialchars($event['MaSK']); ?>" onclick="trackEvent(this)">
                            <div class="card-image-wrapper">
                                <img src="<?php echo htmlspecialchars($event['img_sukien']); ?>" alt="<?php echo htmlspecialchars($event['TenSK']); ?>" class="card-image">
                                <span class="event-tag special-tag"><?php echo $tag; ?></span>
                            </div>
                            <div class="card-info">
                                <h3 class="event-name"><?php echo htmlspecialchars($event['TenSK']); ?></h3>
                                <p class="event-date">Từ <?php echo $formatted_date_time; ?></p>
                                <p class="event-price">
                                    <span class="price-value"><?php echo $min_price_formatted; ?></span>
                                    <?php echo !empty($min_price_formatted) ? 'VND++' : ''; ?>
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

$search_query_safe = "";
$is_searching = isset($_GET['q']) && !empty(trim($_GET['q']));

require_once 'header.php';

if (!$is_searching) {
?>
<main>
    <nav class="category-bar">
        <div class="category-container">
            <ul class="category-list">
                <li class="tooltip"><a href="sukien.php?loai_sukien=LSK03" class="category-item">Concert🔥</a>
                    <span class="tooltiptext">Concert: buổi biểu diễn âm nhạc lớn...</span>
                </li>
                <li class="tooltip"><a href="sukien.php?loai_sukien=LSK02" class="category-item">Festival</a>
                    <span class="tooltiptext">Festival: sự kiện âm nhạc theo chủ đề...</span>
                </li>
                <li class="tooltip"><a href="sukien.php?loai_sukien=LSK01" class="category-item">Liveshow</a>
                    <span class="tooltiptext">Liveshow: buổi diễn riêng của nghệ sĩ...</span>
                </li>
            </ul>
        </div>
    </nav>
    <section class="hero-banner">
        <div class="banner-logo"><span class="banner-logo-text">Vibe4</span></div>
        <h1 class="banner-title">Khám Phá Thế Giới Sự Kiện Tuyệt Vời</h1>
        <p class="banner-subtitle">Mua vé hòa nhạc, concert và các lễ hội âm nhạc một cách dễ dàng và nhanh chóng.</p>
        <div class="banner-actions"><a href="#sukien-gan-day" class="btn-banner">Khám phá ngay</a></div>
    </section>

    <div class="slider">
        <?php
        $sql_slider = "SELECT MaSK, TenSK, img_sukien
                         FROM sukien
                         WHERE Tgian >= DATE_ADD(CURDATE(), INTERVAL 1 DAY)
                           AND Tgian <= DATE_ADD(CURDATE(), INTERVAL 14 DAY)
                         ORDER BY Tgian ASC";
        $result_slider = $conn->query($sql_slider);
        ?>
        <div class="slide-track">
            <?php
            $slider_events = [];
            if ($result_slider !== FALSE && $result_slider->num_rows > 0) {
                while ($row = $result_slider->fetch_assoc()) {
                    $slider_events[] = $row;
                }
                for ($i = 0; $i < 2; $i++) {
                    foreach ($slider_events as $up_comming) {
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

    <div class="space"></div>
    
</main>
<?php
}
?>

        <?php
        $tag_logic_common = function($event) {
            if ($event['MaLSK'] == 'LSK03') return 'Concert🔥';
            if ($event['MaLSK'] == 'LSK02') return 'Festival';
            return 'Liveshow';
        };

        if ($is_searching) {
            $raw_query = trim($_GET['q']);
            $search_term = "%" . $raw_query . "%";

            $sql_search_prepared = "
                SELECT s.MaSK, s.TenSK, s.Tgian, s.img_sukien, s.MaLSK, MIN(lv.Gia) AS MinPrice
                FROM sukien s
                JOIN diadiem dd ON s.MaDD = dd.MaDD
                LEFT JOIN loaive lv ON s.MaSK = lv.MaSK
                WHERE s.TenSK LIKE ? OR dd.TenTinh LIKE ?
                GROUP BY s.MaSK
                ORDER BY s.Tgian ASC
                LIMIT 22
            ";

            $stmt = $conn->prepare($sql_search_prepared);
            $stmt->bind_param("ss", $search_term, $search_term);
            $stmt->execute();
            $result_search = $stmt->get_result();

            ?>
            <section class="event-section" id="search-results-top">
                <h2 class="section-title"><i class="fas fa-search"></i> Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($raw_query); ?>"</h2>
                <div class="event-carousel">
                <?php
                if ($result_search && $result_search->num_rows > 0) {
                    while($event = $result_search->fetch_assoc()) {
                        $min_price_formatted = (isset($event['MinPrice']) && is_numeric($event['MinPrice'])) ? number_format($event['MinPrice'], 0, ',', '.') : '';
                        $date_obj = isset($event['Tgian']) ? new DateTime($event['Tgian']) : null;
                        $formatted_date_time = $date_obj ? $date_obj->format('H:i d/m/Y') : 'Không rõ';
                        $tag = $tag_logic_common($event);
                        ?>
                        <div class="event-card">
                            <a href="chitietsk_1.php?MaSK=<?php echo $event['MaSK']; ?>">
                                <div class="card-image-wrapper">
                                    <img src="<?php echo htmlspecialchars($event['img_sukien']); ?>" alt="<?php echo htmlspecialchars($event['TenSK']); ?>" class="card-image">
                                    <span class="event-tag special-tag"><?php echo $tag; ?></span>
                                </div>
                                <div class="card-info">
                                    <h3 class="event-name"><?php echo htmlspecialchars($event['TenSK']); ?></h3>
                                    <p class="event-date">Từ <?php echo $formatted_date_time; ?></p>
                                    <p class="event-price">
                                        <span class="price-value"><?php echo $min_price_formatted; ?></span>
                                        <?php echo !empty($min_price_formatted) ? 'VND++' : ''; ?>
                                    </p>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p style='padding: 20px; text-align: center; color: white;'>Không tìm thấy sự kiện nào.</p>";
                }
                $stmt->close();
                ?>
                </div>
            </section>
            <?php

        } else {

            $sql_special = "SELECT s.MaSK, s.TenSK, s.Tgian, s.img_sukien, s.MaLSK, MIN(lv.Gia) AS MinPrice
                             FROM sukien s
                             LEFT JOIN loaive lv ON s.MaSK = lv.MaSK
                             WHERE s.Tgian >= CURDATE()
                             GROUP BY s.MaSK
                             ORDER BY s.Tgian ASC LIMIT 8";
            renderEventCards($conn, $sql_special, 'Sự kiện Gần đây', 'fas fa-star', $tag_logic_common, 'sukien-gan-day');

            $sql_trending = "SELECT s.MaSK, s.TenSK, s.Tgian, s.img_sukien, s.MaLSK, s.luot_truycap, MIN(lv.Gia) AS MinPrice
                             FROM sukien s
                             JOIN loaive lv ON s.MaSK = lv.MaSK
                             WHERE s.Tgian >= CURDATE()
                             GROUP BY s.MaSK
                             ORDER BY s.luot_truycap DESC
                             LIMIT 8";
            $tag_trending = function($event) { return 'HOT 👑'; };
            renderEventCards($conn, $sql_trending, 'Sự kiện Xu hướng', 'fas fa-fire', $tag_trending);

            $sql_foryou = "SELECT s.MaSK, s.TenSK, s.Tgian, s.img_sukien, s.MaLSK, MIN(lv.Gia) AS MinPrice
                           FROM sukien s
                           LEFT JOIN loaive lv ON s.MaSK = lv.MaSK
                           WHERE s.Tgian >= CURDATE()
                           GROUP BY s.MaSK
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