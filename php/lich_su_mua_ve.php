<?php
// 1. Bắt đầu session và kiểm tra đăng nhập
session_start();

// Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
if (!isset($_SESSION['email'])) { // Giả sử bạn lưu email đăng nhập vào 'email'
    header("Location: dangnhap.php"); // Sửa tên file đăng nhập của bạn ở đây
    exit;
}

// 2. Kết nối CSDL
include 'connect_1.php';
$email_dang_nhap = $_SESSION['email'];

// 3. Hàm định dạng tiền
function format_currency_simple($amount) {
    return number_format($amount, 0, ',', '.');
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
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        header { padding: 20px; border-bottom: 1px solid #eee; }
        header h1 { margin: 0; color: #333; }
        .order-list { padding: 20px; }
        .order-item { border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; overflow: hidden; }
        .order-header { background: #f9f9f9; padding: 15px 20px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center; }
        .order-header h2 { margin: 0; font-size: 1.2em; color: #d9534f; }
        .order-header .status { font-weight: bold; padding: 5px 10px; border-radius: 5px; color: #fff; }
        .status-cho { background-color: #f0ad4e; } /* Cam (Chờ thanh toán) */
        .status-thanh-cong { background-color: #5cb85c; } /* Xanh (Đã thanh toán) */
        .order-body { padding: 20px; }
        .order-body p { margin: 0 0 10px 0; color: #555; }
        .order-body p strong { color: #333; min-width: 150px; display: inline-block; }
        .ticket-list { list-style: none; padding-left: 0; margin-top: 15px; border-top: 1px dashed #ccc; padding-top: 15px; }
        .ticket-list li { padding: 10px; background: #fafafa; border-radius: 5px; margin-bottom: 5px; }
        .no-orders { text-align: center; padding: 50px; }
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
                            <h2>Mã đơn hàng: <?php echo htmlspecialchars($maTT); ?></h2>
                            <?php 
                                // Đặt class CSS dựa trên trạng thái
                                $status_class = 'status-cho'; // Mặc định
                                if ($don['TrangThai'] == 'Đã thanh toán') {
                                    $status_class = 'status-thanh-cong';
                                }
                            ?>
                            <span class="status <?php echo $status_class; ?>">
                                <?php echo htmlspecialchars($don['TrangThai']); ?>
                            </span>
                        </div>
                        <div class="order-body">
                            <p><strong>Sự kiện:</strong> <?php echo htmlspecialchars($don['TenSuKien']); ?></p>
                            <p><strong>Tổng tiền:</strong> <?Mô hình 1: "Giả lập thanh toán" (Cách của bạn hiện tại)
?> VNĐ</p>
                            <p><strong>Ngày đặt:</strong> <?php echo date("d/m/Y H:i", strtotime($don['NgayTao'])); ?></p>
                            <p><strong>Hình thức:</strong> <?php echo htmlspecialchars($don['PhuongThuc']); ?></p>
                            
                            <strong>Các vé đã đặt:</strong>
                            <ul class="ticket-list">
                                <?php foreach ($don['Ve'] as $ve): ?>
                                    <li>
                                        Mã vé: <strong><?php echo htmlspecialchars($ve['MaVe']); ?></strong> 
                                        (Loại: <?php echo htmlspecialchars($ve['TenLoai']); ?>)
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