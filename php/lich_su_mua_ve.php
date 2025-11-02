<?php
// 1. Bắt đầu session và kiểm tra đăng nhập
session_start();

// Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
if (!isset($_SESSION['email'])) { // Giả sử bạn lưu email đăng nhập vào 'email'
    // Cập nhật tên file đăng nhập chính xác của bạn
    // Giả sử file đăng nhập là dangnhap.php
    $login_page = 'dangnhap.php'; 
    
    // Lấy URL hiện tại để chuyển hướng lại sau khi đăng nhập
    $current_page_url = $_SERVER['REQUEST_URI'];
    $login_url = $login_page . '?redirect=' . urlencode($current_page_url);
    
    header("Location: " . $login_url);
    exit;
}

// 2. Kết nối CSDL
include 'connect_1.php'; // Đảm bảo file này tồn tại
$email_dang_nhap = $_SESSION['email'];

// 3. Hàm định dạng tiền
if (!function_exists('format_currency_simple')) {
    function format_currency_simple($amount) {
        return number_format($amount, 0, ',', '.');
    }
}

// 4. TRUY VẤN LỊCH SỬ ĐƠN HÀNG
// JOIN 4 bảng: ThanhToan (đơn hàng) -> ve (vé) -> loaive (tên vé) -> sukien (tên sự kiện)
$sql = "SELECT 
            tt.MaTT, 
            tt.SoTien, 
            tt.TrangThai,
            tt.PhuongThucThanhToan,
            tt.NgayTao,
            v.MaVe,
            lv.TenLoai,
            sk.TenSK
        FROM 
            ThanhToan tt
        JOIN 
            ve v ON tt.MaTT = v.MaTT
        JOIN 
            loaive lv ON v.MaLoai = lv.MaLoai
        JOIN 
            sukien sk ON lv.MaSK = sk.MaSK
        WHERE 
            tt.Email_KH = ?
        ORDER BY
            tt.NgayTao DESC, tt.MaTT"; // Sắp xếp theo đơn hàng mới nhất

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Lỗi chuẩn bị truy vấn: " . $conn->error);
}
$stmt->bind_param("s", $email_dang_nhap);
$stmt->execute();
$result = $stmt->get_result();

// Mảng để gom nhóm vé theo Mã Đơn Hàng (MaTT)
$don_hang = [];
while ($row = $result->fetch_assoc()) {
    $maTT = $row['MaTT'];
    
    // Nếu đơn hàng này chưa có trong mảng, thêm thông tin chính
    if (!isset($don_hang[$maTT])) {
        $don_hang[$maTT] = [
            'SoTien' => $row['SoTien'],
            'TrangThai' => $row['TrangThai'],
            'NgayTao' => $row['NgayTao'],
            'PhuongThuc' => $row['PhuongThucThanhToan'],
            'TenSuKien' => $row['TenSK'], // Lấy tên sự kiện
            'Ve' => [] // Mảng chứa các vé của đơn này
        ];
    }
    
    // Thêm vé vào đơn hàng
    $don_hang[$maTT]['Ve'][] = [
        'MaVe' => $row['MaVe'],
        'TenLoai' => $row['TenLoai']
    ];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử mua vé</title>
    <!-- Bạn có thể thêm link Google Fonts nếu muốn -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f4f7f6; 
            margin: 0; 
            padding: 20px; 
            color: #333;
        }
        .container { 
            max-width: 900px; 
            margin: auto; 
            background: #ffffff; 
            border-radius: 12px; 
            box-shadow: 0 6px 15px rgba(0,0,0,0.07);
            overflow: hidden; /* Đảm bảo bo góc hoạt động */
        }
        header { 
            padding: 25px 30px; 
            border-bottom: 2px solid #eee; 
            background: #fff;
        }
        header h1 { 
            margin: 0; 
            color: #2c3e50; 
            font-weight: 700;
        }
        .order-list { 
            padding: 20px; 
        }
        .order-item { 
            border: 1px solid #ddd; 
            border-radius: 10px; 
            margin-bottom: 25px; 
            overflow: hidden; /* Đảm bảo bo góc */
            transition: box-shadow 0.3s ease;
        }
        .order-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .order-header { 
            background: #fdfdfd; 
            padding: 15px 20px; 
            border-bottom: 1px solid #eee; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            flex-wrap: wrap; /* Cho responsive */
        }
        .order-header h2 { 
            margin: 0; 
            font-size: 1.2em; 
            color: #2980b9; /* Màu xanh dương */
            font-weight: 600;
        }
        .order-header .status { 
            font-weight: 600; 
            padding: 6px 12px; 
            border-radius: 20px; /* Bo tròn */
            color: #fff; 
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-cho { 
            background-color: #f0ad4e; /* Cam (Chờ thanh toán) */ 
        }
        .status-thanh-cong { 
            background-color: #27ae60; /* Xanh lá (Đã thanh toán) */ 
        }
        .status-da-huy { 
            background-color: #e74c3c; /* Đỏ (Đã hủy) */
        }
        .order-body { 
            padding: 20px; 
            background: #fff;
        }
        .order-body p { 
            margin: 0 0 12px 0; 
            color: #555; 
            font-size: 0.95em;
            line-height: 1.6;
        }
        .order-body p strong { 
            color: #333; 
            min-width: 120px; /* Căn chỉnh cột */
            display: inline-block; 
            font-weight: 600;
        }
        .ticket-list-title {
            font-weight: 600;
            color: #333;
            margin-top: 15px;
            margin-bottom: 10px;
            font-size: 0.95em;
        }
        .ticket-list { 
            list-style: none; 
            padding-left: 0; 
            margin-top: 10px; 
            border-top: 1px dashed #ccc; 
            padding-top: 15px; 
        }
        .ticket-list li { 
            padding: 12px; 
            background: #f9f9f9; 
            border-radius: 6px; 
            margin-bottom: 8px; 
            font-size: 0.9em;
            display: flex;
            justify-content: space-between;
        }
        .ticket-list li .ticket-type {
            color: #555;
        }
        .no-orders { 
            text-align: center; 
            padding: 60px 20px;
            color: #777;
        }
        .no-orders p {
            font-size: 1.1em;
            margin: 0;
        }
    </style>
</head>
<body>

    <div class="container">
        <header>
            <h1>Lịch sử mua vé của bạn</h1>
        </header>

        <div class="order-list">
            <?php if (empty($don_hang)): ?>
                <div class="no-orders">
                    <p>Bạn chưa có lịch sử mua vé nào.</p>
                </div>
            <?php else: ?>
                <?php foreach ($don_hang as $maTT => $don): ?>
                    <div class="order-item">
                        <div class="order-header">
                            <h2>Mã đơn: <?php echo htmlspecialchars($maTT); ?></h2>
                            <?php 
                                // Đặt class CSS dựa trên trạng thái
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
                        <div class="order-body">
                            <p><strong>Sự kiện:</strong> <?php echo htmlspecialchars($don['TenSuKien']); ?></p>
                            
                            <!-- DÒNG ĐÃ SỬA LỖI -->
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
    </div>

</body>
</html>
