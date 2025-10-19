<?php
// LUÔN LUÔN bắt đầu session ở dòng đầu tiên
session_start();

// Lấy thông tin đơn hàng từ Session
// Dùng '??' (toán tử null) để gán giá trị mặc định nếu session không tồn tại
$order_details = $_SESSION['order_details'] ?? null;
$customer_info = $_SESSION['customer_info'] ?? null;

// Nếu không có thông tin đơn hàng (ví dụ: người dùng vào thẳng trang này)
// thì chuyển họ về trang chủ
if (!$order_details || !$customer_info) {
    // header('Location: waterbomb_page.php'); // Chuyển về trang chọn vé
    // exit;
    // Tạm thời để test, chúng ta sẽ gán dữ liệu giả
    // Xóa/comment phần này khi chạy thật
    echo "<p style='color:red; text-align:center;'>ĐANG CHẠY TEST (Không có session) - Dùng dữ liệu giả.</p>";
    $order_details = [
        'ticket_name' => 'THE FACE 1',
        'quantity' => 2,
        'total_price' => 4000000,
        'order_id' => 'TEST_12345'
    ];
    $customer_info = [
        'name' => 'Nguyễn Văn A (Test)',
        'email' => 'test@example.com',
        'phone' => '0901234567'
    ];
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
// Việc này để tránh người dùng F5 (refresh) lại trang
// và thấy lại đơn hàng cũ
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
    <style>
      body {
        font-family: "Poppins", sans-serif;
        background-color: #121212;
        color: #eee;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        padding: 20px;
      }
      .container {
        background-color: #1e1e1e;
        border-radius: 10px;
        padding: 30px 40px;
        max-width: 600px;
        width: 100%;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        border-top: 5px solid #007bff;
      }
      h1 {
        color: #fff;
        text-align: center;
        margin-bottom: 15px;
      }
      p {
        font-size: 1.1em;
        line-height: 1.6;
        text-align: center;
        color: #ccc;
      }
      .order-summary {
        margin-top: 30px;
        border-top: 1px solid #444;
        padding-top: 20px;
      }
      .order-summary h2 {
        text-align: center;
        color: #fff;
        margin-bottom: 20px;
      }
      .order-item {
        display: flex;
        justify-content: space-between;
        font-size: 1em;
        padding: 12px 0;
        border-bottom: 1px solid #333;
      }
      .order-item span {
        color: #bbb;
      }
      .order-item strong {
        color: #fff;
        font-weight: 700;
      }
      .order-item:last-child {
        border-bottom: none;
      }
      .total {
        font-size: 1.3em;
        font-weight: 900;
        color: #fff;
      }
      .back-link {
        display: block;
        text-align: center;
        margin-top: 30px;
        color: #007bff;
        text-decoration: none;
        font-weight: 700;
      }
    </style>
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
      <a href="waterbomb_page.php" class="back-link">Quay về trang chủ</a>
    </div>
  </body>
</html>