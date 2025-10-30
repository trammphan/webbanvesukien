<?php
// --- PHẦN 1: LOGIC PHP (XỬ LÝ PHÍA SERVER) ---

// 1. Kết nối CSDL
include 'connect_1.php'; // Đảm bảo đường dẫn này chính xác

// 2. Lấy thông tin từ URL (từ seatmap.js)
$maSK = $_GET['MaSK'] ?? null;
$maLV = $_GET['zone'] ?? null; // 'zone' chính là Mã Loại Vé (MLV)
$selected_quantity = (int)($_GET['qty'] ?? 1);

// 3. Validation: Kiểm tra xem có đủ thông tin không
if (!$maSK || !$maLV || $selected_quantity <= 0) {
    echo "Lỗi: Thông tin đơn hàng không hợp lệ. Vui lòng thử lại.";
    exit;
}

// 4. Lấy thông tin sự kiện (Tên)
$stmt_event = $conn->prepare("SELECT TenSK FROM sukien WHERE MaSK = ?");
if ($stmt_event === false) {
    die("Lỗi sql sự kiện: " . $conn->error);
}
$stmt_event->bind_param("s", $maSK);
$stmt_event->execute();
$event_result = $stmt_event->get_result();
$event_name = "Thanh toán"; // Giá trị mặc định
if ($event_result->num_rows > 0) {
    $event_name = $event_result->fetch_assoc()['TenSK'];
}
$stmt_event->close();

// 5. Lấy thông tin vé (Tên vé, GIÁ VÉ)
$stmt_ticket = $conn->prepare("SELECT TenLoai, Gia
                                FROM loaive
                                WHERE MaSK = ? AND Maloai = ?");
if ($stmt_ticket === false) {
    die("Lỗi sql vé: " . $conn->error);
    exit;
}
$stmt_ticket->bind_param("ss", $maSK, $maLV);
$stmt_ticket->execute();
$ticket_result = $stmt_ticket->get_result();


// --- PHẦN 6: XỬ LÝ KẾT QUẢ (ĐÃ SỬA LOGIC) ---
$so_luong_con_lai = 0; // Đặt giá trị mặc định

if ($ticket_result->num_rows > 0) {
    // Nếu tìm thấy vé hợp lệ:
    $ticket_info = $ticket_result->fetch_assoc();
    $ticket_name = $ticket_info['TenLoai'];
    $base_price = (int)$ticket_info['Gia']; // LẤY GIÁ TỪ CSDL
    $stmt_ticket->close(); // Đóng câu lệnh đầu tiên

    // --- TRUY VẤN MỚI ĐỂ ĐẾM SỐ VÉ CÒN LẠI (maTT IS NULL) ---
    $stmt_count = $conn->prepare("SELECT COUNT(MaVe) AS SoLuongConLai 
                                    FROM ve 
                                    WHERE MaLoai = ? AND maTT IS NULL");
    if ($stmt_count === false) {
        die("Lỗi sql đếm vé: " . $conn->error);
    }
    $stmt_count->bind_param("s", $maLV); // $maLV chính là MaLoai
    $stmt_count->execute();
    $count_result = $stmt_count->get_result();
    
    if ($count_result->num_rows > 0) {
        $so_luong_con_lai = (int)$count_result->fetch_assoc()['SoLuongConLai'];
    }
    $stmt_count->close();

} else {
    // Nếu KHÔNG hợp lệ (người dùng tự ý sửa URL):
    $ticket_name = "Vé không hợp lệ";
    $base_price = 0;
    $selected_quantity = 0;
    $so_luong_con_lai = 0; // Vẫn là 0
    $stmt_ticket->close(); // Đóng câu lệnh (dù thất bại)
}

// *** MỚI: Ràng buộc số lượng $selected_quantity (giống logic JS) ***
// Đảm bảo số lượng từ URL không lớn hơn số lượng còn lại
if ($selected_quantity > $so_luong_con_lai) {
    $selected_quantity = $so_luong_con_lai;
}
// Đảm bảo số lượng là 1 nếu còn vé (tránh trường hợp = 0)
if ($selected_quantity === 0 && $so_luong_con_lai > 0) {
     $selected_quantity = 1;
}

$conn->close(); // Đóng kết nối CSDL sau khi hoàn tất truy vấn

// 7. Tính tổng tiền (sau khi đã ràng buộc số lượng)
$total_price = $base_price * $selected_quantity;

// 8. Hàm định dạng tiền
function format_currency_simple($amount) {
    return number_format($amount, 0, ',', '.');
}
// --- KẾT THÚC PHẦN PHP ---
?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thanh toán - <?php echo htmlspecialchars($event_name); ?></title>
    <link href="../img/fav-icon.png" rel="icon" type="image/vnd.microsoft.icon">
    <link rel="stylesheet" href="../css/thanhtoan.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700;900&display=swap"
      rel="stylesheet"
    />
<script>
 // *** CẦU NỐI QUAN TRỌNG: PHP -> JAVASCRIPT ***
const TICKET_BASE_PRICE = <?php echo $base_price; ?>;

// --- DÒNG NÀY BÂY GIỜ ĐÃ CHẠY ĐÚNG (vì $so_luong_con_lai đã được gán giá trị) ---
const MAX_AVAILABLE_TICKETS = <?php echo $so_luong_con_lai; ?>;
</script>
  </head>
  <body>
    <div class="container">
      <div class="checkout-panel">
        <div class="order-summary">
          <h2>ĐƠN HÀNG CỦA BẠN</h2>
          <div class="ticket-info">
            
            <p>Sự kiện: <strong><?php echo htmlspecialchars($event_name); ?></strong></p>

            <p>Loại vé: <strong id="ticket-name"><?php echo htmlspecialchars($ticket_name); ?></strong></p>
            
            <p>Giá vé: <span id="ticket-price"><?php echo format_currency_simple($base_price); ?></span> VNĐ</p>
          
          </div>

          <div class="ticket-calculator" id="ticket-calculator">
              <div class="quantity-control">
                  <label>Số lượng:</label>
                  <div class="quantity-buttons">
                      <button type="button" class="quantity-btn" id="minus-btn">-</button>
                      <!-- Số 0 này sẽ được JS cập nhật ngay lập tức -->
                      <span id="quantity-display">0</span> 
                      <button type="button" class="quantity-btn" id="plus-btn">+</button>
                  </div>
              </div>
              <!-- <div class="tier-total">
                  <span>TỔNG:</span>
                  <div>
                      
                      <span id="total-price-display">0</span> VNĐ 
                  </div>
              </div> -->
          </div>

          <div class="total-price">
            <h3>TỔNG CỘNG</h3>
            <!-- Giá trị này được PHP tính đúng ban đầu -->
            <p><span id="total-price"><?php echo format_currency_simple($total_price); ?></span> VNĐ</p>
          
          </div>
        </div>

        <div class="payment-form">
          <h2>Thông tin thanh toán</h2>
          <form id="billing-form" action="xuly_thanhtoan.php" method="POST">
            
            <input type="hidden" name="maSK" value="<?php echo htmlspecialchars($maSK); ?>">
            <input type="hidden" name="maLV" value="<?php echo htmlspecialchars($maLV); ?>">
            
            <!-- Giá trị này đã được PHP ràng buộc -->
            <input type="hidden" name="quantity" id="hidden-quantity" value="<?php echo $selected_quantity; ?>">
            <input type="hidden" name="total_price" id="hidden-total" value="<?php echo $total_price; ?>">


            <div class="form-group" autocomplete="off">
              <label for="name">Họ và tên</label>
              <input
                type="text"
                id="name"
                name="customer_name"
                required placeholder="Nguyễn Văn A"
              />
            </div>
            <div id="name-error" style="color: #D9534F; margin-bottom: 15px; font-size: 0.9em; text-align: left;"></div>
            <div class="form-group" autocomplete="off">
              <label for="email">Email</label>
              <input
                type="email"
                id="email"
                name="customer_email"
                required
                placeholder="email@example.com"
              />
            </div>
            <div id="email-error" style="color: #D9534F; margin-bottom: 15px; font-size: 0.9em; text-align: left;"></div>
            <div class="form-group" autocomplete="off">
              <label for="phone">Số điện thoại</label>
              <input type="tel" id="phone" name="customer_phone" required placeholder="0123456789" />
            </div>
            <div id="phone-error" style="color: #D9534F; margin-bottom: 15px; font-size: 0.9em; text-align: left;"></div>
<h3>Phương thức thanh toán</h3>
<div class="payment-methods">

    <!-- Lựa chọn 1: Ví MoMo -->
    <!-- QUAN TRỌNG: <input> ở ngoài và có id -->
    <input type="radio" name="payment_method" value="momo" id="pay-momo" checked />
    <!-- <label> dùng "for" để liên kết với id -->
    <label for="pay-momo">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" style="width: 20px; height: 20px; fill: currentColor; margin-right: 10px;">
            <path d="M21 18v2a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v2h-2V5H5v14h14v-1h2zM12 7a5 5 0 0 1 0 10 5 5 0 0 1 0-10zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
        </svg>
        Ví MoMo
    </label>

    <!-- Lựa chọn 2: Thẻ Tín dụng -->
    <input type="radio" name="payment_method" value="card" id="pay-card" />
    <label for="pay-card">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" style="width: 20px; height: 20px; fill: currentColor; margin-right: 10px;">
            <path d="M22 10v10a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h18a1 1 0 0 1 1 1v2h-2V5H4v14h16v-9h2zm0-4H2v2h20V6zM4 12h8v2H4v-2z"/>
        </svg>
        Thẻ Tín dụng/Ghi nợ
    </label>

    <!-- Lựa chọn 3: Chuyển khoản -->
    <input type="radio" name="payment_method" value="bank" id="pay-bank" />
    <label for="pay-bank">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" style="width: 20px; height: 20px; fill: currentColor; margin-right: 10px;">
            <path d="M2 20h20v2H2v-2zm2-8h2v7H4v-7zm5 0h2v7H9v-7zm4 0h2v7h-2v-7zm5 0h2v7h-2v-7zM2 7l10-5 10 5v2H2V7z"/>
        </svg>
        Chuyển khoản Ngân hàng
    </label>
</div>

            <button type="submit" class="submit-button">
              Xác nhận Thanh toán
            </button>
          </form>
        </div>
      </div>
    </div>
    
    <script src="../js/thanhtoan.js"></script>
    
    
  </body>
</html>

