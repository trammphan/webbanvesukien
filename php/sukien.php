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
                        <li class="tooltip"><a href="sukien.php?loai_sukien=LSK03&diadiem=<?= urlencode($diadiem) ?>" class="category-item <?= $loai == 'LSK03' ? 'active' : '' ?>">Concert🔥</a>
                            <span class="tooltiptext">Concert: buổi biểu diễn âm nhạc lớn, chuyên nghiệp, với sân khấu hoành tráng. Có thể là 
                                hòa nhạc cổ điển hoặc chương trình K-pop, US-UK.</span>
                        </li>
                        <li class="tooltip"><a href="sukien.php?loai_sukien=LSK02&diadiem=<?= urlencode($diadiem) ?>" class="category-item <?= $loai == 'LSK02' ? 'active' : '' ?>">Festival</a>
                            <span class="tooltiptext">Festival: sự kiện âm nhạc theo chủ đề, quy mô rộng, kết hợp các hoạt động khác như: ăn uống, nghệ thuật, thủ công 
                                và hoạt động cộng đồng.</span>
                        </li>
                        <li class="tooltip"><a href="sukien.php?loai_sukien=LSK01&diadiem=<?= urlencode($diadiem) ?>" class="category-item <?= $loai == 'LSK01' ? 'active' : '' ?>">Liveshow</a>
                            <span class="tooltiptext">Liveshow: buổi diễn riêng của nghệ sĩ hoặc nhóm nhạc, mang dấu ấn cá nhân, thường tổ chức tại không gian gần gũi để 
                                chia sẻ cảm xúc, câu chuyện cá nhân và giao lưu trực tiếp với khán giả.</span>
                        </li>

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
                                'HCM' => 'Hồ Chí Minh',
                                'HN'  => 'Hà Nội',
                                'DL'  => 'Đà Lạt',
                                'DN'  => 'Đà Nẵng',
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
                    <a href="chitietsk_1.php?MaSK=<?=urlencode($row['MaSK']) ?>" data-mask="<?= htmlspecialchars($row['MaSK']) ?>" onclick="handleTicketClick(event, this)">
                        <div class="card-image-wrapper">
                            <img src="<?= htmlspecialchars($row['img_sukien']) ?>" alt="<?= htmlspecialchars($row['TenSK']) ?>" class="card-image" />
                            
                            <?php
                                $now = new DateTime();
                                $eventTime = new DateTime($row['Tgian']);
                                $isPast = $eventTime < $now;
                                $eventId = htmlspecialchars($row['MaSK']);
                                $dataEnded = $isPast ? 'true' : 'false';
                            ?>

                            <div class="card-badge-row">
                                <!-- Mua vé ngay mà không xem chi tiết -->
                                <?php
                                if ($isPast) {
                                    // Nếu sự kiện đã kết thúc
                                    echo '<a class="event-tag" href="#" onclick="showEndedAlert(event)">Mua vé ngay</a>';
                                } else {
                                    if (isset($_COOKIE['email']) && !empty($_COOKIE['email'])) {
                                        // Đã đăng nhập và sự kiện chưa kết thúc → đi thẳng tới ticket_page
                                        echo '<a class="event-tag" href="ticket_page.php?MaSK=' . $eventId . '">Mua vé ngay</a>';
                                    } else {
                                        // Chưa đăng nhập → sau khi login sẽ redirect tới ticket_page
                                        $target_url = 'ticket_page.php?MaSK=' . $eventId;
                                        $login_url = 'dangnhap.php?redirect=' . urlencode($target_url);
                                        echo '<a class="event-tag" href="' . $login_url . '">Mua vé ngay</a>';
                                    }
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

            <div id="custom-alert" class="custom-alert hidden">
                <div class="custom-alert-box">
                    <p>Sự kiện đã kết thúc. Bạn không thể mua vé.</p>
                    <button onclick="closeCustomAlert()">Đã hiểu</button>
                </div>
            </div>

<?php 
    $additional_footer_scripts = <<<HTML
        <script src="../js/sukien.js"></script>
    HTML;
    require_once 'footer.php'; 
?>