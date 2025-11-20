<?php
session_start();

if (!isset($_COOKIE['email']) || empty($_COOKIE['email'])){
    $redirect_url = urlencode($_SERVER['REQUEST_URI']);
    header("Location: dangnhap.php?redirect=" . $redirect_url);
    exit; // Dừng chạy code
}

include 'connect_1.php';

date_default_timezone_set('Asia/Ho_Chi_Minh');
$email = $_COOKIE['email'];
$maTT = $_POST['maTT'] ?? null;

if ($maTT) {
    $conn->autocommit(FALSE);
    $conn->begin_transaction();

    try {
        // Lấy thông tin đơn hàng
        $stmt = $conn->prepare("SELECT MaTT, TrangThai, NgayTao 
                                FROM ThanhToan 
                                WHERE MaTT = ? AND Email_KH = ?");
        $stmt->bind_param("ss", $maTT, $email);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        if (!$order) throw new Exception("Không tìm thấy đơn hàng.");

        $now = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
        $thoiGianThanhToan = DateTime::createFromFormat('Y-m-d H:i:s', $order['NgayTao'], new DateTimeZone('Asia/Ho_Chi_Minh'));
        $diffSeconds = $now->getTimestamp() - $thoiGianThanhToan->getTimestamp();

        // Kiểm tra trạng thái và thời gian (<= 1 tiếng)
        if ($order['TrangThai'] != 'Đã thanh toán' || $diffSeconds > 3600) {
            throw new Exception("Đơn hàng không hợp lệ để hoàn (chỉ trong 1 tiếng sau thanh toán).");
        }

        // Update tất cả vé thuộc đơn hàng thành "Còn trống"
        $stmt = $conn->prepare("UPDATE ve SET TrangThai = 'chưa thanh toán', MaTT = NULL WHERE MaTT = ?");
        $stmt->bind_param("s", $maTT);
        $stmt->execute();
        $stmt->close();

        // Update trạng thái đơn hàng
        $stmt = $conn->prepare("UPDATE ThanhToan SET TrangThai = 'Đã hoàn vé' WHERE MaTT = ?");
        $stmt->bind_param("s", $maTT);
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        $_SESSION['message'] = "Hoàn vé thành công. Chúng tôi sẽ liên hệ hoàn tiền trong 24h.";
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['message'] = "Lỗi hoàn đơn hàng: " . $e->getMessage();
    } finally {
        $conn->autocommit(TRUE);
        $conn->close();
    }

    // Quay lại trang lịch sử
    header("Location: lich_su_mua_ve.php");
    exit;
}
?>
