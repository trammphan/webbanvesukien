<?php
// LUÔN LUÔN bắt đầu session ở dòng đầu tiên
session_start();

// Lấy thông tin đơn hàng từ Session
$order_details = $_SESSION['order_details'] ?? null;
$customer_info = $_SESSION['customer_info'] ?? null;

// SỬA LỖI: Bỏ dữ liệu test, KÍCH HOẠT chuyển hướng
if (!$order_details || !$customer_info) {
    // Nếu không có thông tin (người dùng F5 hoặc vào thẳng trang)
    // chuyển họ về trang chủ (hoặc trang chọn vé)
    header('Location: index.php'); 
    exit;
}

// Lấy thông tin ra các biến riêng lẻ
$ticket_name = $order_details['ticket_name'];
$quantity = $order_details['quantity'];
$total_price = $order_details['total_price'];
$order_id = $order_details['order_id'];

$customer_name = $customer_info['name'];
$customer_email = $customer_info['email'];
$customer_phone = $customer_info['phone'];

// Quan trọng: Xóa session sau khi đã lấy thông tin
unset($_SESSION['order_details']);
unset($_SESSION['customer_info']);

?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cảm ơn đã đặt vé!</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700;900&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="cam-on.css" />
  </head>
  <body>
    <div class="container">
      <h1>Thanh toán thành công! 🎉</h1>
      <p>
        Cảm ơn <strong><?php echo htmlspecialchars($customer_name); ?></strong>!
        Đơn hàng của bạn đã được xác nhận.
      </p>
      <p>
        Thông tin vé chi tiết đã được gửi đến email
        <strong><?php echo htmlspecialchars($customer_email); ?></strong>.
      </p>

      <div class="order-summary">
        <h2>Thông tin đơn hàng</h2>
        <div class="order-item">
          <span>Mã đơn hàng:</span>
          <strong>#<?php echo htmlspecialchars($order_id); ?></strong>
        </div>
        <div class="order-item">
          <span>Loại vé:</span>
          <strong><?php echo htmlspecialchars($ticket_name); ?></strong>
        </div>
        <div class="order-item">
          <span>Số lượng:</span>
          <strong><?php echo $quantity; ?></strong>
        </div>
        <div class="order-item">
          <span>Số điện thoại:</span>
          <strong><?php echo htmlspecialchars($customer_phone); ?></strong>
        </div>
        <div class="order-item total">
          <span>TỔNG CỘNG:</span>
          <strong><?php echo number_format($total_price, 0, ',', '.'); ?> VNĐ</strong>
        </div>
      </div>
      <a href="webbanvesukien/index/trangchu.html" class="back-link">Quay về trang chủ</a>
    </div>
  </body>
</html>