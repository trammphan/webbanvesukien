<?php
// 1. Bắt đầu session và kiểm tra đăng nhập
session_start();
if (!isset($_COOKIE['email']) || empty($_COOKIE['email'])){
    $redirect_url = urlencode($_SERVER['REQUEST_URI']);
    header("Location: dangnhap.php?redirect=" . $redirect_url);
    exit; // Dừng chạy code
}
// 2. Kết nối CSDL
require_once  'connect_1.php'; 
$email_dang_nhap = $_COOKIE['email'] ?? '';

// 3. Hàm định dạng tiền
if (!function_exists('format_currency_simple')) {
    function format_currency_simple($amount) {
        return number_format($amount, 0, ',', '.');
    }
}

// 4. XỬ LÝ TRUY VẤN
$sql = "SELECT tt.MaTT, tt.SoTien, tt.TrangThai, tt.PhuongThucThanhToan, tt.NgayTao,
        v.MaVe, lv.TenLoai, sk.TenSK, sk.Tgian
    FROM ThanhToan tt
    LEFT JOIN ve v ON tt.MaTT = v.MaTT
    LEFT JOIN loaive lv ON v.MaLoai = lv.MaLoai
    LEFT JOIN sukien sk ON lv.MaSK = sk.MaSK";

$conditions = [];
$params = [];
$param_types = "";

$conditions[] = "tt.Email_KH = ?";
$params[] = $email_dang_nhap;
$param_types .= "s";

/* --- ĐÃ XÓA BỎ LOGIC LỌC THEO THÁNG/NĂM --- */

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
            'ThoiGianSuKien' => $row['Tgian'], 
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

// 6. Gọi Header (ĐÃ SỬA DÙNG $BASE_DIR)
$additional_css = ['lichsu.css'];
$page_title = 'Lịch sử mua vé';
require_once  'header.php';
?>

<main class="main-content-area">
    <div class="container">
            <div class="back_nguoidung"  onclick="history.back(); return false;">
                <a href="#"><i class="fa-solid fa-x" id="x"></i> </a>
            </div>

        <header class="page-header">
            <h1>Lịch sử mua vé của bạn</h1>
        </header>

        <?php if (isset($_SESSION['message'])): ?>
            <div id="msg" class="alert alert-success">
                <?php echo $_SESSION['message']; ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <script>
            setTimeout(function(){
                var msg = document.getElementById('msg');
                if(msg){
                    msg.style.display = 'none';
                }
            }, 3000); // 10000 ms = 10 giây
        </script>

        <!-- ĐÃ XÓA BỎ FORM LỌC -->

        <div class="order-list">
            <?php if (empty($don_hang)): ?>
                <div class="no-orders">
                    <!-- ĐƠN GIẢN HÓA THÔNG BÁO -->
                    <p>Bạn chưa có lịch sử mua vé nào.</p>
                </div>
            <?php else: ?>
                <?php foreach ($don_hang as $maTT => $don): ?>
                    <div class="order-item">
                        <div class="order-header"> 
                            <!-- THAY ĐỔI: Nhóm Mã đơn và Ngày đặt -->
                            <div>
                                <h2>Mã đơn: <?php echo htmlspecialchars($maTT); ?></h2>
                                <!-- MỚI: Thêm ngày đặt vào header -->
                                <span class="order-date-header" style="font-size: 0.9em; color: #555; margin-top: 4px; display: block;">
                                    <h3>Ngày đặt: <?php echo date("d/m/Y H:i:s", strtotime($don['NgayTao'])); ?></h3>
                                </span>
                            </div>
                            <?php 
                                $status_class = 'status-cho'; 
                                if ($don['TrangThai'] == 'Đã thanh toán') {
                                    $status_class = 'status-thanh-cong';
                                } else if ($don['TrangThai'] == 'Đã hủy') {
                                    $status_class = 'status-da-huy';
                                }
                            ?>
                            <span class="status <?php echo $status_class; ?>">
                                <?php echo htmlspecialchars($don['TrangThai']); ?>
                            </span>

                            <div class="order-actions">
                                <?php 
                                    if ($don['TrangThai'] == 'Đã thanh toán') {
                                        $thoiGianThanhToan = new DateTime($don['NgayTao']);
                                        $hienTai = new DateTime();
                                        $khoangCach = $hienTai->getTimestamp() - $thoiGianThanhToan->getTimestamp();

                                        // Nếu <= 3600 giây (1 tiếng) thì cho phép hủy vé
                                        if ($khoangCach <= 3600) {
                                ?>
                                            <form method="post" action="hoanve.php">
                                                <input type="hidden" name="maTT" value="<?php echo htmlspecialchars($maTT); ?>">
                                                <button type="submit" class="btn-hoan-ve">Hoàn vé</button>
                                            </form>
                                <?php 
                                        }
                                    }
                                ?>
                            </div>

                        </div>
                        <div class="order-body"> 
                            <p><strong>Sự kiện:</strong> <?php echo htmlspecialchars($don['TenSuKien']); ?></p>
                            
                            <p><strong>Thời gian diễn ra:</strong> <?php echo date("d/m/Y H:i", strtotime($don['ThoiGianSuKien'])); ?></p>
                            
                            <p><strong>Tổng tiền:</strong> <?php echo format_currency_simple($don['SoTien']); ?> VNĐ</p>
                            <!-- ĐÃ XÓA DÒNG "NGÀY ĐẶT" Ở ĐÂY -->
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
    </div>
    
    <div id="refund-popup" class="popup-overlay" style="display:none;">
        <div class="popup-box">
            <p>Bạn có chắc muốn hoàn vé này không?<br>
            Chúng tôi sẽ liên hệ hoàn tiền trong 24h tiếp theo sau khi hoàn tất quá trình hoàn vé.</p>
            <div class="popup-actions">
                <button id="popup-confirm">Hoàn vé</button>
                <button id="popup-cancel">Hủy</button>
            </div>
        </div>
    </div>

</main> 

<?php
// Gọi footer dùng đường dẫn tuyệt đối (ĐÃ SỬA DÙNG $BASE_DIR)
require_once  'footer.php'; 
?>
<!-- Gọi JS dùng đường dẫn tuyệt đối (ĐÃ SỬA DÙNG $BASE_URL) -->
 <script>
        // Dữ liệu này bây giờ đã bao gồm 'description'
        const ticketData = <?php echo json_encode($ticket_types); ?>;

        const isUserLoggedIn = <?php echo isset($_COOKIE['email']) ? 'true' : 'false'; ?>;

        const userEmail = "<?php echo isset($_COOKIE['email']) ? htmlspecialchars($_COOKIE['email']) : ''; ?>";
    </script>
<script src="../js/lichsu.js"></script>