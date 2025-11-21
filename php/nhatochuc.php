<?php
// Cấu hình CSDL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlysukien";

// Khởi tạo các biến trạng thái
$is_logged_in = false;
$user_info = null;
session_start();

// Redirect nếu chưa đăng nhập qua cookie
if (!isset($_COOKIE['email']) || empty($_COOKIE['email'])){
    $redirect_url = urlencode($_SERVER['REQUEST_URI']);
    header("Location: dangnhap.php?redirect=" . $redirect_url);
    exit; // Dừng chạy code
}

// 1. KIỂM TRA COOKIE ĐĂNG NHẬP
if (isset($_COOKIE['email'])) {
    $user_email = $_COOKIE['email'];
    $is_logged_in = true;

    // Kết nối CSDL
    $conn = new mysqli($servername, $username, $password, $dbname);

    if (!$conn->connect_error) {
        $sql = "SELECT user_name, email FROM nhatochuc WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user_info = $result->fetch_assoc();
        } else {
            // Xóa cookie nếu không tìm thấy người dùng
            setcookie("email", "", time() - 3600, "/");
            setcookie("user_name", "", time() - 3600, "/");
            $is_logged_in = false;
        }

        $stmt->close();
        // Giữ kết nối $conn mở để sử dụng cho phần thống kê bên dưới
    } else {
        $is_logged_in = false;
        // Nếu kết nối lỗi, cố gắng kết nối lại cho phần thống kê
        $conn = new mysqli($servername, $username, $password, $dbname);
    }
} else {
    // Nếu không có cookie, vẫn cố gắng kết nối CSDL cho phần hiển thị sự kiện
    $conn = new mysqli($servername, $username, $password, $dbname);
}

// Lấy danh sách sự kiện sắp tới
$events_upcoming = [];
if (!$conn->connect_error) {
    $sql_upcoming = "SELECT s.*, d.TenTinh 
                    FROM sukien s 
                    LEFT JOIN diadiem d ON s.MaDD = d.MaDD
                    WHERE s.Tgian >= CURDATE()
                    ORDER BY s.Tgian ASC";
    if ($result_upcoming = $conn->query($sql_upcoming)) {
        $events_upcoming = $result_upcoming->fetch_all(MYSQLI_ASSOC);
        $result_upcoming->free();
    }

    // Lấy danh sách sự kiện đã qua
    $events_past = [];
    $sql_past = "SELECT s.*, d.TenTinh 
                FROM sukien s 
                LEFT JOIN diadiem d ON s.MaDD = d.MaDD
                WHERE s.Tgian < CURDATE()
                ORDER BY s.Tgian DESC";
    if ($result_past = $conn->query($sql_past)) {
        $events_past = $result_past->fetch_all(MYSQLI_ASSOC);
        $result_past->free();
    }
}

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
GROUP BY tt.MaTT, tt.TenNguoiThanhToan, tt.Email_KH, tt.SDT, tt.SoTien, s.MaSK, s.TenSK
ORDER BY tt.NgayTao DESC";

$don_hang_theo_sk = []; // Lưu đơn hàng theo từng sự kiện
$ket_qua_don_hang = !$conn->connect_error ? $conn->query($sql_orders) : false;

if ($ket_qua_don_hang) {
    while ($row = $ket_qua_don_hang->fetch_assoc()) {
        $ma_sk = $row['MaSK'];
        
        if (!isset($don_hang_theo_sk[$ma_sk])) {
            $don_hang_theo_sk[$ma_sk] = [
                'TenSK' => $row['TenSK'],
                'don_hang' => []
            ];
        }
        
        $don_hang_theo_sk[$ma_sk]['don_hang'][] = [
            'ma_tt' => $row['MaTT'],
            'ten_khach_hang' => $row['ten_khach_hang'],
            'email' => $row['email'],
            'so_dien_thoai' => $row['so_dien_thoai'],
            'so_ve' => $row['so_ve'],
            'tong_tien' => $row['tong_tien'],
            'loai_ve' => $row['loai_ve']
        ];
    }
    $ket_qua_don_hang->free();
}
// Lấy các tham số từ URL
$view = $_GET['view'] ?? '';
$event_id_filter = isset($_GET['event_id']) ? (int)$_GET['event_id'] : null;
$selected_mask = ($view === 'revenue' && $event_id_filter) ? $event_id_filter : null;

// Truy vấn thống kê doanh thu
$sql_stats = "SELECT 
                s.MaSK, 
                s.TenSK, 
                lv.TenLoai,
                lv.Gia,
                COUNT(v.MaVe) AS SoLuongVeDaBan
            FROM sukien s
            LEFT JOIN loaive lv ON lv.MaSK = s.MaSK
            LEFT JOIN ve v ON v.MaLoai = lv.MaLoai AND v.TrangThai = 'Đã bán'" . 
            ($event_id_filter ? " WHERE s.MaSK = $event_id_filter" : "") . "
            GROUP BY s.MaSK, lv.MaLoai, lv.TenLoai, lv.Gia
            ORDER BY s.TenSK, lv.TenLoai";

$result_stats = !$conn->connect_error ? $conn->query($sql_stats) : false;

// Khởi tạo các biến cần thiết
$revenue_rows = [];
$event_stats = [];
$stats_event_name = '';
$revenue_total = 0;

if ($result_stats) {
    while ($row = $result_stats->fetch_assoc()) {
        $current_event_id = $row['MaSK'];
        $ticket_type = [
            'TenLoai' => $row['TenLoai'],
            'Gia' => (float)$row['Gia'],
            'so_luong' => (int)$row['SoLuongVeDaBan'],
            'doanh_thu' => (int)$row['SoLuongVeDaBan'] * (float)$row['Gia']
        ];
        
        // Thêm thông tin vào mảng revenue_rows
        $revenue_rows[] = [
            'MaSK' => $current_event_id,
            'TenSK' => $row['TenSK'],
            'TenLoai' => $row['TenLoai'],
            'Gia' => $ticket_type['Gia'],
            'so_luong' => $ticket_type['so_luong'],
            'doanh_thu' => $ticket_type['doanh_thu']
        ];
        
        // Nhóm thống kê theo sự kiện
        if (!isset($event_stats[$current_event_id])) {
            $event_stats[$current_event_id] = [
                'TenSK' => $row['TenSK'],
                'tickets' => [],
                'total_revenue' => 0
            ];
        }
        
        $event_stats[$current_event_id]['tickets'][] = $ticket_type;
        $event_stats[$current_event_id]['total_revenue'] += $ticket_type['doanh_thu'];
        
        // Nếu đang xem chi tiết 1 sự kiện (dựa vào URL filter)
        if ($event_id_filter && $current_event_id == $event_id_filter) {
            $stats_event_name = $row['TenSK'];
            $revenue_total = $event_stats[$current_event_id]['total_revenue'];
        }
    }
    $result_stats->free();
}

// Đóng kết nối CSDL sau khi hoàn thành mọi truy vấn
if (!$conn->connect_error) {
    $conn->close();
}

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
                <span>Xem báo cáo</span>
            </button>
            <?php if ($is_logged_in && $user_info): ?>
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

    <article class="noidung hidden" id="taosk-section">
        <h2 class="noidung-title">TẠO SỰ KIỆN MỚI</h2>
        <i class="fa-solid fa-spinner"></i> Đang cập nhật...
    </article> 

    <article class="noidung hidden" id="qly-section">
        <h2 class="noidung-title">QUẢN LÝ SỰ KIỆN</h2>
        <div class="header">
            <div class="actions">
                <form class="searchbar" method="get" action="admin.php">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="q" placeholder="Danh sách các sự kiện bạn đã tạo." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
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
                                    } catch (Exception $e) {
                                        $time_str = $event['Tgian'];
                                    }
                                }
                                $location = $event['TenTinh'] ?? '';
                                
                                // Lấy thống kê cho sự kiện hiện tại
                                $event_id = $event['MaSK'] ?? 0;
                                $has_stats = isset($event_stats[$event_id]) && is_array($event_stats[$event_id]);
                                $event_data = $has_stats ? $event_stats[$event_id] : ['total_revenue' => 0, 'tickets' => []];
                            ?>
                                
                            <div class="qly-card" data-event-id="<?= $event['MaSK'] ?>">                                    
                                <div class="qly-card-thumb">
                                    <img src="<?= htmlspecialchars($event['img_sukien'] ?? '') ?>" alt="<?= htmlspecialchars($event['TenSK'] ?? '') ?>" />
                                </div>
                                <div class="qly-card-body">
                                    <div class="qly-card-title"><?= htmlspecialchars($event['TenSK'] ?? '') ?></div>
                                    <div class="qly-card-meta"><?= htmlspecialchars($time_str) ?><?= $location ? ' • ' . htmlspecialchars($location) : '' ?></div>
                                    
                                    <div class="event-stats">
                                        <div class="revenue-panel <?= ($view === 'revenue' && $selected_mask == $event['MaSK']) ? '' : 'hidden' ?>" 
                                            id="revenue-panel-<?= $event['MaSK'] ?>">                                          
                                            <div class="revenue-header">
                                                <!-- <h3 class="revenue-title">Doanh thu sự kiện</h3> -->
                                                <button type="button" class="revenue-close" data-event-id="<?= $event['MaSK'] ?>">&times;</button>
                                            </div>

                                            <div class="revenue-summary">
                                                <span class="label">Tổng doanh thu:</span>
                                                <span class="value"><?= number_format($event_data['total_revenue'], 0, ',', '.') ?>đ</span>
                                            </div>
                                            
                                            <?php if (!empty($event_data['tickets'])): ?>
                                            <!-- <h5 class="revenue-subtitle">Thống kê theo loại vé</h5> -->
                                            <table class="revenue-table">
                                                <thead>
                                                    <tr>
                                                        <th>Loại vé</th>
                                                        <th>Số lượng bán</th>
                                                        <th>Đơn giá</th>
                                                        <th>Doanh thu</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($event_data['tickets'] as $ticket): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($ticket['TenLoai'] ?? '') ?></td>
                                                            <td><?= $ticket['so_luong'] ?? 0 ?></td>
                                                            <td><?= isset($ticket['Gia']) ? number_format($ticket['Gia'], 0, ',', '.') . 'đ' : '0đ' ?></td>
                                                            <td><?= isset($ticket['doanh_thu']) ? number_format($ticket['doanh_thu'], 0, ',', '.') . 'đ' : '0đ' ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            <?php else: ?>
                                                <p class="no-tickets">Chưa có dữ liệu bán vé cho sự kiện này.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <?php 
                                    $ma_sk = $event['MaSK'];
                                    $don_hang = $don_hang_theo_sk[$ma_sk] ?? null;
                                    ?>
                                    <div class="orders-panel <?= ($view === 'orders' && $selected_mask == $event['MaSK']) ? '' : 'hidden' ?>" id="orders-panel-<?= $event['MaSK'] ?>">
                                        <div class="orders-header">
                                            <button type="button" class="orders-close" data-event-id="<?= $event['MaSK'] ?>">&times;</button>
                                        </div>
                                        
                                        

                                        <?php if (!empty($don_hang['don_hang'])): ?>
                                            <div class="table-responsive">
                                                <table class="revenue-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Mã TT</th>
                                                            <th>Khách hàng</th>
                                                            <th>Email</th>
                                                            <th>Số điện thoại</th>
                                                            <th>Số vé</th>
                                                            <th>Loại vé</th>
                                                            <th>Thành tiền</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($don_hang['don_hang'] as $don): ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($don['ma_tt'] ?? '') ?></td>
                                                                <td><?= htmlspecialchars($don['ten_khach_hang'] ?? 'Khách hàng') ?></td>
                                                                <td><?= htmlspecialchars($don['email'] ?? '') ?></td>
                                                                <td><?= htmlspecialchars($don['so_dien_thoai'] ?? '') ?></td>
                                                                <td><?= $don['so_ve'] ?? 0 ?></td>
                                                                <td><?= htmlspecialchars($don['loai_ve'] ?? '') ?></td>
                                                                <td><?= isset($don['tong_tien']) ? number_format($don['tong_tien'], 0, ',', '.') . 'đ' : '0đ' ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php else: ?>
                                            <p class="no-orders">Chưa có đơn hàng nào cho sự kiện này.</p>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="qly-card-actions">
                                        <button type="button" class="btn-qly btn-manage">
                                            Quản lý
                                        </button>
                                        <div class="manage-menu hidden">
                                            <button type="button" class="btn-qly btn-revenue" data-event-id="<?= $event['MaSK'] ?>">
                                                Doanh thu
                                            </button>
                                            <button type="button" class="btn-qly btn-orders" data-event-id="<?= $event['MaSK'] ?>">Đơn hàng</button>
                                        </div>
                                        <a href="chitietsk_1.php?MaSK=<?= urlencode($event['MaSK'] ?? '') ?>"
                                            class="btn-qly btn-update">
                                            Xem
                                        </a>
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
                                $time_str = '';
                                if (!empty($event['Tgian'])) {
                                    try {
                                        $dt = new DateTime($event['Tgian']);
                                        $time_str = $dt->format('d/m/Y H:i');
                                    } catch (Exception $e) {
                                        $time_str = $event['Tgian'];
                                    }
                                }
                                $location = $event['TenTinh'] ?? '';
                                
                                // Lấy thống kê cho sự kiện hiện tại
                                $event_id = $event['MaSK'] ?? 0;
                                $has_stats = isset($event_stats[$event_id]) && is_array($event_stats[$event_id]);
                                $event_data = $has_stats ? $event_stats[$event_id] : ['total_revenue' => 0, 'tickets' => []];
                            ?>
                                
                            <div class="qly-card" data-event-id="<?= $event['MaSK'] ?>">                                    
                                <div class="qly-card-thumb">
                                    <img src="<?= htmlspecialchars($event['img_sukien'] ?? '') ?>" alt="<?= htmlspecialchars($event['TenSK'] ?? '') ?>" />
                                </div>
                                <div class="qly-card-body">
                                    <div class="qly-card-title"><?= htmlspecialchars($event['TenSK'] ?? '') ?></div>
                                    <div class="qly-card-meta"><?= htmlspecialchars($time_str) ?><?= $location ? ' • ' . htmlspecialchars($location) : '' ?></div>
                                    
                                    <div class="event-stats">
                                        <div class="revenue-panel <?= ($view === 'revenue' && $selected_mask == $event['MaSK']) ? '' : 'hidden' ?>" 
                                            id="revenue-panel-<?= $event['MaSK'] ?>">                                          
                                            <div class="revenue-header">
                                                <button type="button" class="revenue-close" data-event-id="<?= $event['MaSK'] ?>">&times;</button>
                                            </div>

                                            <div class="revenue-summary">
                                                <span class="value"><?= number_format($event_data['total_revenue'], 0, ',', '.') ?>đ</span>
                                            </div>
                               
                                            
                                            <?php if (!empty($event_data['tickets'])): ?>
                                            <h5 class="revenue-subtitle">Thống kê theo loại vé</h5>
                                            <table class="revenue-table">
                                                <thead>
                                                    <tr>
                                                        <th>Loại vé</th>
                                                        <th>Số lượng bán</th>
                                                        <th>Đơn giá</th>
                                                        <th>Doanh thu</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($event_data['tickets'] as $ticket): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($ticket['TenLoai'] ?? '') ?></td>
                                                            <td><?= $ticket['so_luong'] ?? 0 ?></td>
                                                            <td><?= isset($ticket['Gia']) ? number_format($ticket['Gia'], 0, ',', '.') . 'đ' : '0đ' ?></td>
                                                            <td><?= isset($ticket['doanh_thu']) ? number_format($ticket['doanh_thu'], 0, ',', '.') . 'đ' : '0đ' ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            <?php else: ?>
                                                <p class="no-tickets">Chưa có dữ liệu bán vé cho sự kiện này.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <?php 
                                    $ma_sk = $event['MaSK'];
                                    $don_hang = $don_hang_theo_sk[$ma_sk] ?? null;
                                    ?>
                                    <div class="orders-panel <?= ($view === 'orders' && $selected_mask == $event['MaSK']) ? '' : 'hidden' ?>" id="orders-panel-<?= $event['MaSK'] ?>">
                                        <div class="orders-header">
                                            <button type="button" class="orders-close" data-event-id="<?= $event['MaSK'] ?>">&times;</button>
                                        </div>
                                        
                                        

                                        <?php if (!empty($don_hang['don_hang'])): ?>
                                            <div class="table-responsive">
                                                <table class="revenue-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Mã TT</th>
                                                            <th>Khách hàng</th>
                                                            <th>Email</th>
                                                            <th>Số điện thoại</th>
                                                            <th>Số vé</th>
                                                            <th>Loại vé</th>
                                                            <th>Thành tiền</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($don_hang['don_hang'] as $don): ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($don['ma_tt'] ?? '') ?></td>
                                                                <td><?= htmlspecialchars($don['ten_khach_hang'] ?? 'Khách hàng') ?></td>
                                                                <td><?= htmlspecialchars($don['email'] ?? '') ?></td>
                                                                <td><?= htmlspecialchars($don['so_dien_thoai'] ?? '') ?></td>
                                                                <td><?= $don['so_ve'] ?? 0 ?></td>
                                                                <td><?= htmlspecialchars($don['loai_ve'] ?? '') ?></td>
                                                                <td><?= isset($don['tong_tien']) ? number_format($don['tong_tien'], 0, ',', '.') . 'đ' : '0đ' ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php else: ?>
                                            <p class="no-orders">Chưa có đơn hàng nào cho sự kiện này.</p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="qly-card-actions">
                                        <button type="button" class="btn-qly btn-manage">
                                            Quản lý
                                        </button>
                                        <div class="manage-menu hidden">
                                            <button type="button" class="btn-qly btn-revenue" data-event-id="<?= $event['MaSK'] ?>">
                                                Doanh thu
                                            </button>
                                            <button type="button" class="btn-qly btn-orders" data-event-id="<?= $event['MaSK'] ?>">
                                                Đơn hàng</button>
                                        </div>
                                        <a href="chitietsk_1.php?MaSK=<?= urlencode($event['MaSK'] ?? '') ?>"
                                            class="btn-qly btn-update">
                                            Xem
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Hiện chưa có sự kiện sắp tới.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="qly-list qly-list-khac hidden">
                <p><i class="fa-solid fa-spinner"></i> Đang cập nhật thêm dữ liệu...</p>
            </div>
        </div>
    </article>

    <article class="noidung hidden" id="xembc-section">
        <h2 class="noidung-title">XEM BÁO CÁO</h2>
        <div class="report-table-wrapper">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>File</th>
                        <th>Ngày tạo</th>
                        <th>Người tạo</th>
                        <th>Trạng thái xử lý</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>baocao_doanhthu_gdragon_2025.xlsx</td>
                        <td>2025-11-09 10:15</td>
                        <td>ntc@ctu.edu.vn</td>
                        <td>Đã xử lý</td>
                        <td>
                            <button class="btn-qly btn-download-report">Tải xuống</button>
                        </td>
                    </tr>
                    <tr>
                        <td>baocao_khachhang_waterbomb.csv</td>
                        <td>2025-11-17 09:00</td>
                        <td>report@vibe4.vn</td>
                        <td>Đã xử lý</td>
                        <td>
                            <button class="btn-qly btn-download-report">Tải xuống</button>
                        </td>
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
                    <div class="modal-body" style="color:#000000;">
                        Tải xuống thành công.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </article>


  </main>
<?php 
  $additional_footer_scripts = <<<HTML
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
HTML;
  require_once 'footer.php';
?>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const btns = {
        qly: document.getElementById("btn-qly"),
        xembc: document.getElementById("btn-xembc")
    };

    const sections = {
        nhatochuc: document.querySelector(".nhatochuc"),
        taosk: document.getElementById("taosk-section"),
        qly: document.getElementById("qly-section"),
        xembc: document.getElementById("xembc-section")
    };

    // Khởi tạo trạng thái ban đầu (hiển thị trang Quản lý Sự kiện nếu có event_id trong URL để hiển thị Doanh thu)
    Object.values(sections).forEach(sec => sec.classList.add("hidden"));
    if (new URLSearchParams(window.location.search).has('event_id')) {
        sections.qly.classList.remove("hidden");
        btns.qly.classList.add("active");
        sections.nhatochuc.classList.add("hidden");
    } else {
        sections.nhatochuc.classList.remove("hidden");
        btns.qly.classList.remove("active"); // Mặc định không active nút nào khi ở trang chào mừng
    }


    // Hàm chuyển section
    function showSection(name) {
        // Ẩn toàn bộ section
        Object.values(sections).forEach(sec => sec.classList.add("hidden"));
        // Hiện section tương ứng
        sections[name].classList.remove("hidden");

        // Làm nổi bật nút đang chọn
        Object.values(btns).forEach(btn => btn && btn.classList.remove("active"));
        if (btns[name]) {
            btns[name].classList.add("active");
        }
    }

    // Gán sự kiện click cho mỗi nút Sidebar
    Object.keys(btns).forEach(name => {
        const btn = btns[name];
        if (!btn) return;
        btn.addEventListener("click", () => showSection(name));
    });

    // Nút tạo sự kiện trong phần QUẢN LÝ SỰ KIỆN
    const btnCreateQly = document.getElementById("btn-create-qly");
    if (btnCreateQly) {
        btnCreateQly.addEventListener("click", (e) => {
            e.preventDefault();
            showSection("taosk");
        });
    }

    // Xử lý Tabs Sắp tới / Đã qua
    const qlyTabs = document.querySelectorAll('#qly-section .tabs .tab');
    const qlyLists = {
        saptoi: document.querySelector('.qly-list-saptoi'),
        daqua: document.querySelector('.qly-list-daqua'),
        // Các tab khác sử dụng chung .qly-list-khac
        choduyet: document.querySelector('.qly-list-khac'),
        nhap: document.querySelector('.qly-list-khac')
    };

    qlyTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            qlyTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const status = tab.dataset.status;
            Object.values(qlyLists).forEach(list => list && list.classList.add('hidden'));
            if (qlyLists[status]) {
                qlyLists[status].classList.remove('hidden');
            }
        });
    });

    // Xử lý nút Quản lý: toggle menu con (Doanh thu / Đơn hàng)
    document.querySelectorAll('.qly-card .btn-manage').forEach(btn => {
        btn.addEventListener('click', () => {
            const card = btn.closest('.qly-card');
            const menu = card?.querySelector('.manage-menu');
            if (!menu) return;

            // Ẩn tất cả các menu con khác trước khi hiển thị menu của card hiện tại
            document.querySelectorAll('.manage-menu').forEach(m => {
                if (m !== menu) {
                    m.classList.add('hidden');
                }
            });
            menu.classList.toggle('hidden');
        });
    });
    
    // Xử lý nút Đơn hàng mở panel
    document.querySelectorAll('.btn-orders').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const eventId = btn.getAttribute('data-event-id');
            if (!eventId) return;

            // Ẩn tất cả các panel đơn hàng và doanh thu
            document.querySelectorAll('.orders-panel, .revenue-panel').forEach(panel => {
                panel.classList.add('hidden');
            });
            
            // Hiển thị panel đơn hàng tương ứng
            const panelToShow = document.getElementById(`orders-panel-${eventId}`);
            if (panelToShow) {
                // Ẩn menu con khi mở panel
                document.querySelectorAll('.manage-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
                
                // Hiển thị panel
                panelToShow.classList.remove('hidden');
            }
        });
    });

    // Xử lý nút đóng panel đơn hàng
    document.querySelectorAll('.orders-close').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const eventId = btn.getAttribute('data-event-id');
            if (!eventId) return;
            
            const panel = document.getElementById(`orders-panel-${eventId}`);
            if (panel) {
                panel.classList.add('hidden');
            }
        });
    });

    // Xử lý nút Doanh thu mở panel
    document.querySelectorAll('.btn-revenue').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const eventId = btn.getAttribute('data-event-id');
            if (!eventId) return;

            // Ẩn tất cả các panel
            document.querySelectorAll('.revenue-panel, .orders-panel').forEach(panel => {
                panel.classList.add('hidden');
            });
            
            // Hiển thị panel doanh thu tương ứng
            const panelToShow = document.getElementById(`revenue-panel-${eventId}`);
            if (panelToShow) {
                // Ẩn menu con
                btn.closest('.manage-menu')?.classList.add('hidden');
                // Hiển thị panel
                panelToShow.classList.remove('hidden');
            }
        });
    });

    // Xử lý nút Đóng panel Doanh thu
    document.querySelectorAll('.revenue-close').forEach(closeBtn => {
        closeBtn.addEventListener('click', () => {
            closeBtn.closest('.revenue-panel').classList.add('hidden');
        });
    });

    // Thông báo tải xuống thành công cho phần Xem báo cáo
    const downloadButtons = document.querySelectorAll('.btn-download-report');
    const downloadModalElement = document.getElementById('downloadSuccessModal');
    if (downloadButtons.length && downloadModalElement && window.bootstrap) {
        const downloadModal = new bootstrap.Modal(downloadModalElement);
        downloadButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                downloadModal.show();
                
                // Mô phỏng tải xuống sau 1.5 giây
                setTimeout(() => {
                    downloadModal.hide();
                    // Thêm logic tải xuống thực tế ở đây nếu cần
                    // window.location.href = btn.getAttribute('data-download-url');
                }, 1500);
            });
        });
    }

    // Xử lý Tìm kiếm (lọc tại chỗ bằng JS)
    const qlySearchForm = document.querySelector('#qly-section .searchbar');
    if (qlySearchForm) {
        const qlyInput  = qlySearchForm.querySelector('input[name="q"]');
        const qlyCards  = document.querySelectorAll('#qly-section .qly-events-list .qly-card');

        const applyQlyFilter = () => {
            if (!qlyInput) return;
            const q = qlyInput.value.trim().toLowerCase();
            
            // Chỉ lọc các sự kiện trong tab ĐANG HIỂN THỊ
            document.querySelectorAll('#qly-section .qly-events-list:not(.hidden) .qly-card').forEach(card => {
                const titleEl = card.querySelector('.qly-card-title');
                const title = (titleEl?.textContent || '').toLowerCase();
                if (!q || title.includes(q)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        };

        // Gán sự kiện cho input và button
        if (qlyInput) {
            // Lọc khi gõ (tùy chọn) hoặc chỉ khi nhấn Enter/Search
             qlyInput.addEventListener('input', applyQlyFilter);
        }
        
        qlySearchForm.addEventListener('submit', (e) => {
            e.preventDefault(); // Ngăn chặn form submit
            applyQlyFilter();
        });
    }
    
    // Xử lý phần tạo loại vé (Đang cập nhật)
    const soloaive = document.getElementById("soloaive");
    const khungloaive = document.getElementById("khungloaive");
    if (soloaive && khungloaive) {
        soloaive.addEventListener("change", function() {
            const soLuong = parseInt(this.value);
            khungloaive.innerHTML = ""; // Xóa nội dung cũ

            if (soLuong > 0 && soLuong <= 10) { // Giới hạn số lượng
                for (let i = 1; i <= soLuong; i++) {
                    const div = document.createElement("div");
                    div.className = "ve-item";
                    div.innerHTML = `
                        <input type="text" placeholder="Tên loại vé ${i}">
                        <input type="number" min="0" placeholder="Số lượng vé ${i}">
                    `;
                    khungloaive.appendChild(div);
                }
            } else if (soLuong > 10) {
                 khungloaive.innerHTML = "<p>Tối đa 10 loại vé.</p>";
            }
        });
    }
});
</script>
<script src="vi.js"></script>

</body>
</html>