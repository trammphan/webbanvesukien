<?php
// 1. Bắt đầu session và kiểm tra đăng nhập
session_start();

// ĐỊNH NGHĨA THƯ MỤC GỐC CỦA BẠN (ví dụ: /webbanvesukien)
// Sửa '/webbanvesukien' nếu tên thư mục gốc của bạn khác
$BASE_URL = '/webbanvesukien'; 
$BASE_DIR = $_SERVER['DOCUMENT_ROOT'] . $BASE_URL . '/'; 

// Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
if (!isset($_COOKIE['email'])) {
    $login_page = $BASE_URL . '/dangnhap.php'; // ĐƯỜNG DẪN TUYỆT ĐỐI
    $current_page_url = $_SERVER['REQUEST_URI'];
    $login_url = $login_page . '?redirect=' . urlencode($current_page_url);
    header("Location: " . $login_url);
    exit;
}

// 2. Kết nối CSDL (SỬA ĐƯỜNG DẪN TUYỆT ĐỐI)
require_once  'connect_1.php'; 
$email_dang_nhap = $_COOKIE['email'] ?? '';

// 3. Hàm định dạng tiền
if (!function_exists('format_currency_simple')) {
    function format_currency_simple($amount) {
        return number_format($amount, 0, ',', '.');
    }
}

// 4. XỬ LÝ LỌC VÀ TRUY VẤN (Giữ nguyên logic PHP của bạn)
// (Đã dọn dẹp các ký tự rác ' ')
$sql = "SELECT 
            tt.MaTT, tt.SoTien, tt.TrangThai, tt.PhuongThucThanhToan, tt.NgayTao,
            v.MaVe, lv.TenLoai, sk.TenSK
        FROM ThanhToan tt
        JOIN ve v ON tt.MaTT = v.MaTT
        JOIN loaive lv ON v.MaLoai = lv.MaLoai
        JOIN sukien sk ON lv.MaSK = sk.MaSK";
$conditions = [];
$params = [];
$param_types = "";
$conditions[] = "tt.Email_KH = ?";
$params[] = $email_dang_nhap;
$param_types .= "s";
$filter_thangnam = $_GET['thangnam'] ?? '';
if (!empty($filter_thangnam)) {
    $conditions[] = "DATE_FORMAT(tt.NgayTao, '%Y-%m') = ?";
    $params[] = $filter_thangnam;
    $param_types .= "s"; 
}
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
$sql .= " ORDER BY tt.NgayTao DESC, tt.MaTT";
$stmt = $conn->prepare($sql);
if ($stmt === false) { die("Lỗi chuẩn bị truy vấn: " . $conn->error); }
if (!empty($params)) { $stmt->bind_param($param_types, ...$params); }
$stmt->execute();
$result = $stmt->get_result();
$don_hang = [];
while ($row = $result->fetch_assoc()) {
    $maTT = $row['MaTT'];
    if (!isset($don_hang[$maTT])) {
        $don_hang[$maTT] = [
            'SoTien' => $row['SoTien'],
            'TrangThai' => $row['TrangThai'],
            'NgayTao' => $row['NgayTao'],
            'PhuongThuc' => $row['PhuongThucThanhToan'],
            'TenSuKien' => $row['TenSK'], 
            'Ve' => [] 
        ];
    }
    $don_hang[$maTT]['Ve'][] = [
        'MaVe' => $row['MaVe'],
        'TenLoai' => $row['TenLoai']
    ];
}
$stmt->close();
$conn->close();

// 6. Gọi Header (SỬA ĐƯỜNG DẪN TUYỆT ĐỐI)
$additional_css = ['lichsu.css'];
$page_title = 'Lịch sử mua vé';
require_once 'header.php';

// 7. Bắt đầu nội dung trang
// (XÓA <html>, <head>, <style>, <body> thừa ở đây)
?>

<main class="main-content-area">
    
    <div class="container">
        
        <div class="back_nguoidung">
            <a href="#" onclick="history.back(); return false;">
                <i class="fa-solid fa-x" id="x"></i> 
            </a>
        </div>

        <header>
            <h1>Lịch sử mua vé của bạn</h1>
        </header>

        <form class="filter-form" method="GET" action="">
            <div>
                <label for="thangnam">Lọc theo tháng/năm:</label>
                <input type="month" id="thangnam" name="thangnam" value="<?php echo htmlspecialchars(isset($_GET['thangnam']) ? $_GET['thangnam'] : ''); ?>">
            </div>
            <button type="submit">Lọc</button>
        </form>

        <div class="order-list">
            <?php if (empty($don_hang)): ?>
                <div class="no-orders">
                    <?php if (!empty($filter_thangnam)): ?>
                        <p>Không tìm thấy đơn hàng nào phù hợp với điều kiện lọc.</p>
                    <?php else: ?>
                        <p>Bạn chưa có lịch sử mua vé nào.</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php foreach ($don_hang as $maTT => $don): ?>
                    <div class="order-item">
                        <div class="order-header"> <h2>Mã đơn: <?php echo htmlspecialchars($maTT); ?></h2>
                            <?php 
                                $status_class = 'status-cho'; // Mặc định
                                if ($don['TrangThai'] == 'Đã thanh toán') {
                                    $status_class = 'status-thanh-cong';
                                } else if ($don['TrangThai'] == 'Đã hủy') {
                                    $status_class = 'status-da-huy';
                                }
                            ?>
                            <span class="status <?php echo $status_class; ?>">
                                <?php echo htmlspecialchars($don['TrangThai']); ?>
                            </span>
                        </div>
                        <div class="order-body"> <p><strong>Sự kiện:</strong> <?php echo htmlspecialchars($don['TenSuKien']); ?></p>
                            <p><strong>Tổng tiền:</strong> <?php echo format_currency_simple($don['SoTien']); ?> VNĐ</p>
                            <p><strong>Ngày đặt:</strong> <?php echo date("d/m/Y H:i", strtotime($don['NgayTao'])); ?></p>
                            <p><strong>Hình thức:</strong> <?php echo htmlspecialchars($don['PhuongThuc']); ?></p>
                            
                            <div class="ticket-list-title">Các vé đã đặt:</div>
                            <ul class="ticket-list">
                                <?php foreach ($don['Ve'] as $ve): ?>
                                    <li>
                                        <span>Mã vé: <strong><?php echo htmlspecialchars($ve['MaVe']); ?></strong></span>
                                        <span class="ticket-type">(Loại: <?php echo htmlspecialchars($ve['TenLoai']); ?>)</span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div> </main> <?php
// (XÓA </html> thừa ở đây)

// 8. Gọi Footer (SỬA ĐƯỜNG DẪN TUYỆT ĐỐI)
require_once  'footer.php'; 
?>

<script src="../js/lichsu.js"></script>