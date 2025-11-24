<?php
session_start();

if (!isset($_SESSION['email'])) {
    if (isset($_COOKIE['email'])) {
        $_SESSION['email'] = $_COOKIE['email'];
    } else {
        header("Location: dangnhap.php");
        exit;
    }
}

$user_email = $_SESSION['email'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlysukien";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed");
}

$stmt = $conn->prepare("SELECT user_name, email FROM nhatochuc WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_info = $result->fetch_assoc();
} else {
    session_destroy();
    setcookie("email", "", time() - 3600, "/");
    header("Location: dangnhap.php");
    exit;
}
$stmt->close();

$events_upcoming = [];
$sql_upcoming = "SELECT s.*, d.TenTinh 
                FROM sukien s 
                LEFT JOIN diadiem d ON s.MaDD = d.MaDD
                WHERE s.email = ? AND s.Tgian >= CURDATE()
                ORDER BY s.Tgian ASC";
$stmt = $conn->prepare($sql_upcoming);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$events_upcoming = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$events_past = [];
$sql_past = "SELECT s.*, d.TenTinh 
            FROM sukien s 
            LEFT JOIN diadiem d ON s.MaDD = d.MaDD
            WHERE s.email = ? AND s.Tgian < CURDATE()
            ORDER BY s.Tgian DESC";
$stmt = $conn->prepare($sql_past);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$events_past = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$sql_orders = "SELECT 
    tt.MaTT, 
    tt.TenNguoiThanhToan as ten_khach_hang,
    tt.Email_KH as email,
    tt.SDT as so_dien_thoai,
    tt.SoTien as tong_tien,
    COUNT(v.MaVe) as so_ve,
    s.MaSK,
    s.TenSK,
    GROUP_CONCAT(DISTINCT lv.TenLoai SEPARATOR ', ') as loai_ve
FROM thanhtoan tt
JOIN ve v ON tt.MaTT = v.MaTT
JOIN loaive lv ON v.MaLoai = lv.MaLoai
JOIN sukien s ON lv.MaSK = s.MaSK
WHERE s.email = ? 
GROUP BY tt.MaTT, tt.TenNguoiThanhToan, tt.Email_KH, tt.SDT, tt.SoTien, s.MaSK, s.TenSK
ORDER BY tt.NgayTao DESC";

$don_hang_theo_sk = [];
$stmt = $conn->prepare($sql_orders);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$ket_qua_don_hang = $stmt->get_result();

while ($row = $ket_qua_don_hang->fetch_assoc()) {
    $ma_sk = $row['MaSK'];
    if (!isset($don_hang_theo_sk[$ma_sk])) {
        $don_hang_theo_sk[$ma_sk] = [
            'TenSK' => $row['TenSK'],
            'don_hang' => []
        ];
    }
    $don_hang_theo_sk[$ma_sk]['don_hang'][] = $row;
}
$stmt->close();

$sql_stats = "SELECT 
                s.MaSK, 
                s.TenSK, 
                lv.TenLoai,
                lv.Gia,
                COUNT(v.MaVe) AS SoLuongVeDaBan
            FROM sukien s
            LEFT JOIN loaive lv ON lv.MaSK = s.MaSK
            LEFT JOIN ve v ON v.MaLoai = lv.MaLoai AND v.TrangThai = 'Đã bán'
            WHERE s.email = ?
            GROUP BY s.MaSK, lv.MaLoai, lv.TenLoai, lv.Gia
            ORDER BY s.TenSK, lv.TenLoai";

$stmt = $conn->prepare($sql_stats);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result_stats = $stmt->get_result();

$event_stats = [];

while ($row = $result_stats->fetch_assoc()) {
    $current_event_id = $row['MaSK'];
    $doanh_thu = (int)$row['SoLuongVeDaBan'] * (float)$row['Gia'];
    
    $ticket_type = [
        'TenLoai' => $row['TenLoai'],
        'Gia' => (float)$row['Gia'],
        'so_luong' => (int)$row['SoLuongVeDaBan'],
        'doanh_thu' => $doanh_thu
    ];
    
    if (!isset($event_stats[$current_event_id])) {
        $event_stats[$current_event_id] = [
            'TenSK' => $row['TenSK'],
            'tickets' => [],
            'total_revenue' => 0
        ];
    }
    
    $event_stats[$current_event_id]['tickets'][] = $ticket_type;
    $event_stats[$current_event_id]['total_revenue'] += $doanh_thu;
}
$stmt->close();
$conn->close();

$page_title = 'Nhà tổ chức';
$additional_css = ['webstyle.css', 'nhatochuc.css'];
$additional_head = <<<HTML
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
HTML;
require_once 'header.php';
?>   
  <main class="layout">
    <article class="sidebar">
        <div class="back">
            <a href="#" onclick="history.back(); return false;">
              <i class="fa-regular fa-circle-left" id="x"></i> 
            </a>
        </div>
        <div class="brand">
            <span class="brand-dot"></span>
            <span class="brand-text">Nhà tổ chức</span>
        </div>
        <nav class="nav">
            <button class="nav-item active" id="btn-qly">
                <i class="fa-solid fa-list-check"></i>
                <span>Quản lý sự kiện</span>
            </button>

            <button class="nav-item" id="btn-xembc">
                <i class="fa-solid fa-chart-line"></i>
                <span>Thống kê</span>
            </button>
            <?php if ($user_info): ?>
                <label class="email_ntc">
                    <i class="fa-solid fa-envelope"></i>
                    <span>Email: <b><?= htmlspecialchars($user_info['email']) ?></b></span>
                </label>
                <div class="box_3">
                    <a href="dangxuat.php" class="w3-bar-item w3-button w3-padding" id="logout" data-bs-toggle="tooltip" title="Đăng xuất">
                        <i class="fa-solid fa-right-from-bracket"></i> 
                    </a>
                </div>
            <?php endif; ?>
        </nav>
    </article>

    <article class="noidung nhatochuc">
        <h2 class="noidung-title">Chào mừng Nhà tổ chức đến với Vibe4</h2>
        <p class="chaomung">
            Vibe4 là nền tảng giúp bạn tạo, quản lý và bán vé sự kiện một cách dễ dàng.
            Từ hội thảo, hòa nhạc đến các buổi gặp gỡ, chúng tôi mang đến công cụ mạnh mẽ
            để bạn kết nối khán giả, tăng doanh thu và lan tỏa thương hiệu của mình.
        </p>
    </article>

    <article class="noidung hidden" id="qly-section">
        <h2 class="noidung-title">QUẢN LÝ SỰ KIỆN</h2>
        <div class="header">
            <div class="actions">
                <form class="searchbar" method="get" action="">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="q" placeholder="Tìm kiếm sự kiện..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
                    <button class="btn-search" type="submit">Tìm kiếm</button>
                </form>
            </div>
            <a href="#" class="btn-create" id="btn-create-qly">
                <i class="fa-solid fa-calendar-plus"></i>
                <span>Tạo sự kiện</span>
            </a>
        </div>

        <div class="tabs">
            <button type="button" class="tab active" data-status="saptoi">Sắp tới</button>
            <button type="button" class="tab" data-status="daqua">Đã qua</button>
        </div>

        <div class="qly-events">
            <div class="qly-list qly-list-saptoi">
                <div class="qly-events-list">
                    <?php if (!empty($events_upcoming)): ?>
                        <?php foreach ($events_upcoming as $event): ?>
                            <?php
                                $dt = null;
                                $time_str = '';
                                if (!empty($event['Tgian'])) {
                                    try {
                                        $dt = new DateTime($event['Tgian']);
                                        $time_str = $dt->format('d/m/Y H:i');
                                    } catch (Exception $e) { $time_str = $event['Tgian']; }
                                }
                                $location = $event['TenTinh'] ?? '';
                                $event_id = $event['MaSK'];
                                $event_data = $event_stats[$event_id] ?? ['total_revenue' => 0, 'tickets' => []];
                            ?>
                                
                            <div class="qly-card" data-event-id="<?= $event_id ?>">                                    
                                <div class="qly-card-thumb">
                                    <img src="<?= htmlspecialchars($event['img_sukien'] ?? '') ?>" alt="<?= htmlspecialchars($event['TenSK'] ?? '') ?>" />
                                </div>
                                <div class="qly-card-body">
                                    <div class="qly-card-title"><?= htmlspecialchars($event['TenSK'] ?? '') ?></div>
                                    <div class="qly-card-meta"><?= htmlspecialchars($time_str) ?><?= $location ? ' • ' . htmlspecialchars($location) : '' ?></div>
                                    
                                    <div class="event-stats">
                                        <div class="revenue-panel hidden" id="revenue-panel-<?= $event_id ?>">                                          
                                            <div class="revenue-header">
                                                <button type="button" class="revenue-close" data-event-id="<?= $event_id ?>">&times;</button>
                                            </div>
                                            <div class="revenue-summary">
                                                <span class="label">Tổng doanh thu:</span>
                                                <span class="value"><?= number_format($event_data['total_revenue'], 0, ',', '.') ?>đ</span>
                                            </div>
                                            
                                            <?php if (!empty($event_data['tickets'])): ?>
                                            <div class="table-responsive">
                                                <table class="revenue-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Loại vé</th>
                                                            <th>SL bán</th>
                                                            <th>Đơn giá</th>
                                                            <th>Thành tiền</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($event_data['tickets'] as $ticket): ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($ticket['TenLoai']) ?></td>
                                                                <td><?= $ticket['so_luong'] ?></td>
                                                                <td><?= number_format($ticket['Gia'], 0, ',', '.') ?>đ</td>
                                                                <td><?= number_format($ticket['doanh_thu'], 0, ',', '.') ?>đ</td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <?php else: ?>
                                                <p class="no-tickets">Chưa có vé nào được bán.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <?php 
                                    $don_hang = $don_hang_theo_sk[$event_id] ?? null;
                                    ?>
                                    <div class="orders-panel hidden" id="orders-panel-<?= $event_id ?>">
                                        <div class="orders-header">
                                            <button type="button" class="orders-close" data-event-id="<?= $event_id ?>">&times;</button>
                                        </div>
                                        
                                        <?php if (!empty($don_hang['don_hang'])): ?>
                                            <div class="table-responsive">
                                                <table class="revenue-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Mã TT</th>
                                                            <th>Tên KH</th>
                                                            <th>Email</th>
                                                            <th>SĐT</th>
                                                            <th>Vé</th>
                                                            <th>Tiền</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($don_hang['don_hang'] as $don): ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($don['MaTT']) ?></td>
                                                                <td><?= htmlspecialchars($don['ten_khach_hang']) ?></td>
                                                                <td><?= htmlspecialchars($don['email']) ?></td>
                                                                <td><?= htmlspecialchars($don['so_dien_thoai']) ?></td>
                                                                <td><?= $don['so_ve'] ?></td>
                                                                <td><?= number_format($don['tong_tien'], 0, ',', '.') ?>đ</td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php else: ?>
                                            <p class="no-orders">Chưa có đơn hàng nào.</p>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="qly-card-actions">
                                        <button type="button" class="btn-qly btn-manage">Quản lý</button>
                                        <div class="manage-menu hidden">
                                            <button type="button" class="btn-qly btn-revenue" data-event-id="<?= $event_id ?>">Doanh thu</button>
                                            <button type="button" class="btn-qly btn-orders" data-event-id="<?= $event_id ?>">Đơn hàng</button>
                                        </div>
                                        <a href="chitietsk_1.php?MaSK=<?= urlencode($event_id) ?>" class="btn-qly btn-update">Xem</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Hiện chưa có sự kiện sắp tới.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="qly-list qly-list-daqua hidden">
                <div class="qly-events-list">
                    <?php if (!empty($events_past)): ?>
                        <?php foreach ($events_past as $event): ?>
                            <?php
                                $dt = null;
                                if (!empty($event['Tgian'])) {
                                    try { $dt = new DateTime($event['Tgian']); $time_str = $dt->format('d/m/Y H:i'); } 
                                    catch (Exception $e) { $time_str = $event['Tgian']; }
                                }
                                $location = $event['TenTinh'] ?? '';
                                $event_id = $event['MaSK'];
                                $event_data = $event_stats[$event_id] ?? ['total_revenue' => 0, 'tickets' => []];
                            ?>
                            <div class="qly-card" data-event-id="<?= $event_id ?>">                                    
                                <div class="qly-card-thumb">
                                    <img src="<?= htmlspecialchars($event['img_sukien'] ?? '') ?>" alt="<?= htmlspecialchars($event['TenSK'] ?? '') ?>" />
                                </div>
                                <div class="qly-card-body">
                                    <div class="qly-card-title"><?= htmlspecialchars($event['TenSK'] ?? '') ?></div>
                                    <div class="qly-card-meta"><?= htmlspecialchars($time_str) ?><?= $location ? ' • ' . htmlspecialchars($location) : '' ?></div>
                                    
                                    <div class="event-stats">
                                        <div class="revenue-panel hidden" id="revenue-panel-<?= $event_id ?>">                                          
                                            <div class="revenue-header">
                                                <button type="button" class="revenue-close" data-event-id="<?= $event_id ?>">&times;</button>
                                            </div>
                                            <div class="revenue-summary">
                                                <span class="label">Tổng doanh thu:</span>
                                                <span class="value"><?= number_format($event_data['total_revenue'], 0, ',', '.') ?>đ</span>
                                            </div>
                                            <?php if (!empty($event_data['tickets'])): ?>
                                            <div class="table-responsive">
                                                <table class="revenue-table">
                                                    <thead><tr><th>Loại vé</th><th>SL</th><th>Giá</th><th>Tổng</th></tr></thead>
                                                    <tbody>
                                                        <?php foreach ($event_data['tickets'] as $ticket): ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($ticket['TenLoai']) ?></td>
                                                                <td><?= $ticket['so_luong'] ?></td>
                                                                <td><?= number_format($ticket['Gia'], 0, ',', '.') ?>đ</td>
                                                                <td><?= number_format($ticket['doanh_thu'], 0, ',', '.') ?>đ</td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <?php else: ?> <p class="no-tickets">Chưa có vé bán.</p> <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php $don_hang = $don_hang_theo_sk[$event_id] ?? null; ?>
                                    <div class="orders-panel hidden" id="orders-panel-<?= $event_id ?>">
                                        <div class="orders-header">
                                            <button type="button" class="orders-close" data-event-id="<?= $event_id ?>">&times;</button>
                                        </div>
                                        <?php if (!empty($don_hang['don_hang'])): ?>
                                            <div class="table-responsive">
                                                <table class="revenue-table">
                                                    <thead><tr><th>Mã TT</th><th>KH</th><th>Email</th><th>Vé</th><th>Tiền</th></tr></thead>
                                                    <tbody>
                                                        <?php foreach ($don_hang['don_hang'] as $don): ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($don['MaTT']) ?></td>
                                                                <td><?= htmlspecialchars($don['ten_khach_hang']) ?></td>
                                                                <td><?= htmlspecialchars($don['email']) ?></td>
                                                                <td><?= $don['so_ve'] ?></td>
                                                                <td><?= number_format($don['tong_tien'], 0, ',', '.') ?>đ</td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php else: ?> <p class="no-orders">Chưa có đơn hàng.</p> <?php endif; ?>
                                    </div>
                                    
                                    <div class="qly-card-actions">
                                        <button type="button" class="btn-qly btn-manage">Quản lý</button>
                                        <div class="manage-menu hidden">
                                            <button type="button" class="btn-qly btn-revenue" data-event-id="<?= $event_id ?>">Doanh thu</button>
                                            <button type="button" class="btn-qly btn-orders" data-event-id="<?= $event_id ?>">Đơn hàng</button>
                                        </div>
                                        <a href="chitietsk_1.php?MaSK=<?= urlencode($event_id) ?>" class="btn-qly btn-update">Xem</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Không có sự kiện đã qua.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </article>

    <article class="noidung hidden" id="xembc-section">
        <h2 class="noidung-title">THỐNG KÊ</h2>
        <div class="report-table-wrapper">
            <table class="report-table">
                <thead>
                    <tr><th>File</th><th>Ngày tạo</th><th>Người tạo</th><th>Trạng thái</th><th>Thao tác</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="File">thongke_doanhthu_2025.xlsx</td>
                        <td data-label="Ngày tạo">2025-11-09 10:15</td>
                        <td data-label="Người tạo"><?= htmlspecialchars($user_email) ?></td>
                        <td data-label="Trạng thái">Đã xử lý</td>
                        <td><button class="btn-qly btn-download-report">Tải xuống</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="downloadSuccessModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background:#ffffff; color:#000000;">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color:#000000;">Thông báo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">Tải xuống thành công.</div>
                    <div class="modal-footer"><button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đóng</button></div>
                </div>
            </div>
        </div>
    </article>
  </main>

<?php 
  $additional_footer_scripts = <<<HTML
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/nhatochuc.js"></script>
  HTML;
  require_once 'footer.php';
?>
</body>
</html>