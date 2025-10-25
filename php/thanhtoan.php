<?php
// --- PHẦN 1: LOGIC PHP (XỬ LÝ PHÍA SERVER) ---

// 1. Kết nối CSDL
include '../sukien/connect.php'; // Đảm bảo đường dẫn này chính xác

// 2. Lấy thông tin từ URL (từ seatmap.js)
// Ví dụ: .../thanhtoan.php?MaSK=SK001&zone=LV01&qty=2
$maSK = $_GET['MaSK'] ?? null;
$maLV = $_GET['zone'] ?? null; // 'zone' chính là Mã Loại Vé (MLV)
$selected_quantity = (int)($_GET['qty'] ?? 1);

// 3. Validation: Kiểm tra xem có đủ thông tin không
if (!$maSK || !$maLV || $selected_quantity <= 0) {
    echo "Lỗi: Thông tin đơn hàng không hợp lệ. Vui lòng thử lại.";
    exit;
}

// 4. Lấy thông tin sự kiện (Tên) - Dùng Prepared Statement
$stmt_event = $conn->prepare("SELECT TenSK FROM sukien WHERE MaSK = ?");
$stmt_event->bind_param("s", $maSK);
$stmt_event->execute();
$event_result = $stmt_event->get_result();
$event_name = "Thanh toán"; // Giá trị mặc định
if ($event_result->num_rows > 0) {
    $event_name = $event_result->fetch_assoc()['TenSK'];
}

// 5. Lấy thông tin vé (Tên vé, GIÁ VÉ) - Dùng Prepared Statement
// *** Đây là bước xác thực giá quan trọng nhất ***
// Ta truy vấn CSDL để lấy giá VÉ (GiaVe) dựa trên
// cả MÃ SỰ KIỆN (MaSK) và MÃ LOẠI VÉ (MaLoaiVe)
$stmt_ticket = $conn->prepare("SELECT v.TenLV, sl.GiaVe 
                             FROM sukien_loaive sl 
                             JOIN loaive v ON sl.MaLoaiVe = v.MLV 
                             WHERE sl.MaSK = ? AND sl.MaLoaiVe = ?");
$stmt_ticket->bind_param("ss", $maSK, $maLV);
$stmt_ticket->execute();
$ticket_result = $stmt_ticket->get_result();

// 6. Xử lý kết quả
if ($ticket_result->num_rows > 0) {
    // Nếu tìm thấy vé hợp lệ:
    $ticket_info = $ticket_result->fetch_assoc();
    $ticket_name = $ticket_info['TenLV'];
    $base_price = (int)$ticket_info['GiaVe']; // LẤY GIÁ TỪ CSDL
} else {
    // Nếu KHÔNG hợp lệ (người dùng tự ý sửa URL):
    $ticket_name = "Vé không hợp lệ";
    $base_price = 0;
    $selected_quantity = 0;
}

// 7. Tính tổng tiền
$total_price = $base_price * $selected_quantity;

// 8. Hàm định dạng tiền (giữ nguyên)
function format_currency_simple($amount) {
    return number_format($amount, 0, ',', '.');
}

?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thanh toán - <?php echo htmlspecialchars($event_name); ?></title>
    
    <link rel="stylesheet" href="thanhtoan.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700;900&display=swap"
      rel="stylesheet"
    />
    
    <script>
        // *** CẦU NỐI QUAN TRỌNG: PHP -> JAVASCRIPT ***
        // Giá trị $base_price bây giờ được lấy an toàn từ CSDL
        // Tệp 'thanhtoan.js' sẽ dùng hằng số này để tính toán lại
        const TICKET_BASE_PRICE = <?php echo $base_price; ?>;
    </script>
    
  </head>
  <body>
    <div class="container">
      <div class="checkout-panel">
        <div class="order-summary">
          <h2>Đơn hàng của bạn</h2>
          <div class="ticket-info">
            
            <p>Sự kiện: <strong><?php echo htmlspecialchars($event_name); ?></strong></p>

            <p>Loại vé: <strong id="ticket-name"><?php echo htmlspecialchars($ticket_name); ?></strong></p>
            
            <p>Giá vé: <span id="ticket-price"><?php echo format_currency_simple($base_price); ?></span> VNĐ</p>
          
          </div>

          <div class="quantity-selector">
            <label>Số lượng:</label>
            <div class="quantity-buttons-checkout">
              <button
                type="button"
                class="quantity-btn-checkout minus-btn-checkout"
              >
                -
              </button>
              
              <span
                class="quantity-display-checkout"
                id="quantity-display-checkout"
                ><?php echo $selected_quantity; ?></span
              >
              
              <button
                type="button"
                class="quantity-btn-checkout plus-btn-checkout"
              >
                +
              </button>
            </div>
          </div>

          <div class="total-price">
            <h3>TỔNG CỘNG</h3>
            
            <p><span id="total-price"><?php echo format_currency_simple($total_price); ?></span> VNĐ</p>
          
          </div>
        </div>

        <div class="payment-form">
          <h2>Thông tin thanh toán</h2>
          <form id="billing-form" action="xuly_thanhtoan.php" method="POST">
            
            <input type="hidden" name="maSK" value="<?php echo htmlspecialchars($maSK); ?>">
            <input type="hidden" name="maLV" value="<?php echo htmlspecialchars($maLV); ?>">
            <input type="hidden" name="quantity" id="hidden-quantity" value="<?php echo $selected_quantity; ?>">
            <input type="hidden" name="total_price" id="hidden-total" value="<?php echo $total_price; ?>">


            <div class="form-group">
              <label for="name">Họ và tên</label>
              <input
                type="text"
                id="name"
                name="customer_name"
                required placeholder="Nguyễn Văn A"
              />
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input
                type="email"
                id="email"
                name="customer_email"
                required
                placeholder="email@example.com"
              />
            </div>
            <div class="form-group">
              <label for="phone">Số điện thoại</label>
              <input type="tel" id="phone" name="customer_phone" required placeholder="0123456789" />
            </div>

            <h3>Phương thức thanh toán</h3>
            <div class="payment-methods">
              <label
                ><input type="radio" name="payment_method" value="momo" checked /> Ví
                MoMo</label
              >
              <label
                ><input type="radio" name="payment_method" value="card" /> Thẻ Tín
                dụng/Ghi nợ</label
              >
              <label
                ><input type="radio" name="payment_method" value="bank" /> Chuyển khoản
                Ngân hàng</label
              >
            </div>

            <button type="submit" class="submit-button">
              Xác nhận Thanh toán
            </button>
          </form>
        </div>
      </div>
    </div>
    
    <script src="thanhtoan.js"></script>
    
  </body>
</html>