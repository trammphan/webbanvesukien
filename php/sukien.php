<?php
    include 'connect_1.php';
    // Dùng lại style của trang chủ cho thẻ sự kiện
    $additional_css = ['index.css', 'sukien.css'];

    $conditions = [];
    if (!empty($_GET['diadiem'])) {
        $conditions[] = "MaDD = '" . $conn->real_escape_string($_GET['diadiem']) . "'";
    }
    if (!empty($_GET['loai_sukien'])) {
        $conditions[] = "MaLSK = '" . $conn->real_escape_string($_GET['loai_sukien']) . "'";
    }

    $sql = "SELECT s.MaSK, s.TenSK, s.img_sukien, s.Tgian, 
            MIN(lv.Gia) AS GiaThapNhat,
            CASE WHEN s.Tgian < NOW() THEN 1 ELSE 0 END AS DaDienRa
            FROM sukien s JOIN loaive lv ON s.MaSK = lv.MaSK";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    $sql .= " GROUP BY s.MaSK ORDER BY DaDienRa ASC, s.Tgian ASC";

    $result = $conn->query($sql);
?>
<?php
    // Set page title and render shared header
    $page_title = 'Sự kiện';
    require_once 'header.php'; 
?>
            <!-- Bộ lọc -->
                <!-- Thể loại -->
            <?php $diadiem = $_GET['diadiem'] ?? ''; 
                  $loai = $_GET['loai_sukien'] ?? '';
            ?>
            <nav class="category-bar">
                <div class="category-container">
                    <ul class="category-list">
                        <li><a href="sukien.php?loai_sukien=LSK03&diadiem=<?= urlencode($diadiem) ?>" class="category-item <?= $loai == 'LSK03' ? 'active' : '' ?>">Concert🔥</a></li>
                        <li><a href="sukien.php?loai_sukien=LSK02&diadiem=<?= urlencode($diadiem) ?>" class="category-item <?= $loai == 'LSK02' ? 'active' : '' ?>">Festival</a></li>
                        <li><a href="sukien.php?loai_sukien=LSK01&diadiem=<?= urlencode($diadiem) ?>" class="category-item <?= $loai == 'LSK01' ? 'active' : '' ?>">Liveshow</a></li>

                    </ul>
                </div>
            </nav>

            <form id="event-filter" class="filter-box">
                <input type="hidden" name="loai_sukien" value="<?= htmlspecialchars($loai) ?>">
                <button type="button" class="filter-toggle" onclick="toggleFilter()">
                    <i class="fa-solid fa-filter"></i>Địa điểm
                </button>

                <div id="filter-details" class="hidden">
                    <!-- Địa điểm -->
                    <div class="radio-group">
                        <?php
                            $selected_location = $_GET['diadiem'] ?? '';
                            $locations = [
                                ''    => 'Tất cả',
                                'HN'  => 'Hà Nội',
                                'DL'  => 'Đà Lạt',
                                'HY'  => 'Hưng Yên'
                            ];
                            
                            foreach ($locations as $code => $name) {
                                $checked = ($selected_location === $code) ? 'checked' : '';
                                echo "<label><input type='radio' name='diadiem' value='$code' $checked onchange='this.form.submit()'> $name</label>";
                            }
                        ?>
                    </div>
                </div>
            </form>
            
            <!-- Danh sách sự kiện-->
            <div id="event-list" class="grid-container">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                        $now = new DateTime();
                        $eventTime = new DateTime($row['Tgian']);

                        $status = '';
                        $statusClass = '';

                        if ($eventTime < $now) {
                            $status = 'Đã diễn ra';
                            $statusClass = 'status-past';
                        } elseif ($eventTime > $now && $eventTime->diff($now)->days <= 14) {
                            $status = 'Sắp diễn ra';
                            $statusClass = 'status-upcoming';
                        }
                    ?>
                <div class="event-card">
                    <a href="chitietsk_1.php?MaSK=<?=urlencode($row['MaSK']) ?>" data-mask="<?= htmlspecialchars($row['MaSK']) ?>" onclick="trackEvent(this)">
                        <div class="card-image-wrapper">
                            <img src="<?= htmlspecialchars($row['img_sukien']) ?>" alt="<?= htmlspecialchars($row['TenSK']) ?>" class="card-image" />
                            
                            <div class="card-badge-row">
                                <!-- Mua vé ngay mà không xem chi tiết -->
                                <?php
                                // Kiểm tra xem cookie 'email' (dấu hiệu đã đăng nhập) có tồn tại không
                                if (isset($_COOKIE['email']) && !empty($_COOKIE['email'])) {
                                    // Nếu ĐÃ ĐĂNG NHẬP: Trỏ đến trang mua vé
                                    echo '<a class="event-tag" href="ticket_page.php?MaSK=' . htmlspecialchars($row['MaSK']) . '">Mua vé ngay</a>';
                                } else {
                                    // Nếu CHƯA ĐĂNG NHẬP: Trỏ đến trang đăng nhập
                                    // Lấy URL hiện tại
                                    $current_page_url = $_SERVER['REQUEST_URI'];
                                    // Thêm URL này vào link đăng nhập để sau khi login thành công có thể quay lại
                                    $login_url = 'dangnhap.php?redirect=' . urlencode($current_page_url);
                                    echo '<a class="event-tag" href="' . $login_url . '">Mua vé ngay</a>';
                                }
                                ?>

                                <?php if ($status): ?> <!-- Hiển thị trạng thái sự kiện -->
                                    <div class="event-status <?= $statusClass ?>"><?= $status ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card-info">
                            <h3 class="event-name"><?= htmlspecialchars($row['TenSK']) ?></h3>
                            <p class="event-date"><?= "Từ: " . $eventTime->format('H:i, d/m/Y') ?></p>
                            <p class="event-price"><span class="price-value"><?= number_format($row['GiaThapNhat']) ?></span> VND++</p>
                        </div>
                    </a>
                </div>
                <?php endwhile; ?>
            </div>
<?php 
    $additional_footer_scripts = <<<HTML
        <script src="../js/sukien.js"></script>
    HTML;
    require_once 'footer.php'; 
?>