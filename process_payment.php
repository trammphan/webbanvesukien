<?php
// Bắt đầu session
session_start();

// Đọc dữ liệu JSON được gửi từ thanhtoan.js
$data = json_decode(file_get_contents('php://input'), true);

// --- BẮT ĐẦU XỬ LÝ THANH TOÁN (GIẢ LẬP) ---
//
// Trong thực tế, đây là nơi bạn sẽ:
// 1. Gọi API của cổng thanh toán (MoMo, VNPay...) với $data['paymentMethod']
// 2. Nhận kết quả
// 3. Nếu thanh toán thành công, lưu đơn hàng vào DATABASE
// 4. Lấy order_id từ database
//
// Bây giờ chúng ta sẽ giả lập là nó thành công
//
$payment_successful = true; 
// --- KẾT THÚC XỬ LÝ THANH TOÁN (GIẢ LẬP) ---


if ($payment_successful && $data) {
    
    // Tạo một mã đơn hàng ngẫu nhiên (vì chưa có database)
    $order_id = rand(100000, 999999);

    // Lưu thông tin vé vào Session
    $_SESSION['order_details'] = [
        'ticket_name'   => $data['ticketName'],
        'quantity'      => $data['quantity'],
        'total_price'   => $data['totalPrice'],
        'order_id'      => $order_id
    ];

    // Lưu thông tin khách hàng vào Session
    $_SESSION['customer_info'] = [
        'name'  => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone']
    ];
    
    // Trả về tín hiệu thành công cho JavaScript
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);

} else {
    // Trả về tín hiệu thất bại cho JavaScript
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Thanh toán thất bại']);
}
?>