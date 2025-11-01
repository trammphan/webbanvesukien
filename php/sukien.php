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
            <?php
                require_once 'header.php'; 
            ?>
        <main>
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
                    <i class="fa-solid fa-filter"></i>Bộ lọc
                </button>

                <div id="filter-details" style="display: none;">
                    <!-- Địa điểm -->
                    <label>Địa điểm:
                        <select class= 'filter-group' name="diadiem">
                            <option value="">-- Tất cả --</option>
                            <option value="HCM" <?= ($_GET['diadiem'] ?? '') == 'HCM' ? 'selected' : '' ?>>Hồ Chí Minh</option> <!-- Giữ lại lựa chọn của người dùng-->
                            <option value="HN" <?= ($_GET['diadiem'] ?? '') == 'HN' ? 'selected' : '' ?>>Hà Nội</option>
                            <option value="DL" <?= ($_GET['diadiem'] ?? '') == 'DL' ? 'selected' : '' ?>>Đà Lạt</option>
                            <option value="HY" <?= ($_GET['diadiem'] ?? '') == 'HY' ? 'selected' : '' ?>>Hưng Yên</option>
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
        </main>

        <!-- Footer -->
            <?php
                require_once 'footer.php'; 
            ?>

        <script src="../js/sukien.js"></script>
    </body>
</html>