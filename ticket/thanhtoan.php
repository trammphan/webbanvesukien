<?php
// --- PHẦN 1: LOGIC PHP (XỬ LÝ PHÍA SERVER) ---

// Bạn cần định nghĩa lại mảng giá vé này.
// (Trong dự án thực tế, bạn sẽ 'include' file này từ một file config
// hoặc truy vấn database để không phải lặp lại code)
// Mảng này hoạt động như một "cơ sở dữ liệu" tạm thời, lưu giá của từng loại vé.
$ticket_types = [
    'the-heart-1' => ['name' => 'THE HEART 1', 'price' => 2500000],
    'the-heart-2' => ['name' => 'THE HEART 2', 'price' => 2500000],
    'the-face-1' => ['name' => 'THE FACE 1', 'price' => 2000000],
    'the-face-2' => ['name' => 'THE FACE 2', 'price' => 2000000],
    'the-energy-1' => ['name' => 'THE ENERGY 1', 'price' => 1800000],
    'the-energy-2' => ['name' => 'THE ENERGY 2', 'price' => 1800000],
    'the-voice-1' => ['name' => 'THE VOICE 1', 'price' => 1500000],
    'the-voice-2' => ['name' => 'THE VOICE 2', 'price' => 1500000],
    'the-style-1' => ['name' => 'THE STYLE 1', 'price' => 1000000],
    'the-style-2' => ['name' => 'THE STYLE 2', 'price' => 1000000],
    'the-move-1' => ['name' => 'THE MOVE 1', 'price' => 800000],
    'the-move-2' => ['name' => 'THE MOVE 2', 'price' => 800000],
    'all-rounder-1' => ['name' => 'ALL-ROUNDER 1', 'price' => 10000000],
    'all-rounder-2' => ['name' => 'ALL-ROUNDER 2', 'price' => 10000000],
];

// Lấy thông tin từ URL.
// Biến siêu toàn cục (superglobal) $_GET dùng để đọc các tham số trên URL.
// Ví dụ: .../thanhtoan.php?zone=the-heart-1&qty=2
// $selected_zone_id sẽ là "the-heart-1"
// $selected_quantity sẽ là 2

// '?? null' là toán tử "null coalescing", nghĩa là:
// "Nếu $_GET['zone'] tồn tại, gán giá trị của nó. Nếu không, gán giá trị là null."
$selected_zone_id = $_GET['zone'] ?? null;
// (int) dùng để ép kiểu giá trị nhận được sang kiểu SỐ NGUYÊN. '?? 1' là giá trị mặc định nếu không có 'qty'.
$selected_quantity = (int)($_GET['qty'] ?? 1);

// Tìm thông tin vé, có dự phòng nếu không tìm thấy
// Đây là bước XÁC THỰC (VALIDATION) dữ liệu đầu vào.
// 1. $selected_zone_id có tồn tại không? (không phải null)
// 2. isset($ticket_types[$selected_zone_id]): ID vé này có tồn tại trong mảng $ticket_types không?
if ($selected_zone_id && isset($ticket_types[$selected_zone_id])) {
    // Nếu hợp lệ: Lấy thông tin chi tiết của vé.
    $ticket_info = $ticket_types[$selected_zone_id];
    $ticket_name = $ticket_info['name'];
    $base_price = $ticket_info['price']; // Giá 1 vé
} else {
    // Nếu KHÔNG hợp lệ (người dùng tự ý sửa URL):
    // Gán giá trị mặc định để thông báo lỗi, tránh làm sập trang.
    $ticket_name = "Không tìm thấy vé";
    $base_price = 0;
    $selected_quantity = 0;
}

// Tính tổng tiền ban đầu (PHP tính toán lần đầu tiên khi tải trang)
$total_price = $base_price * $selected_quantity;

// Hàm định dạng tiền (phiên bản đơn giản hơn, không có "₫")
function format_currency_simple($amount) {
    return number_format($amount, 0, ',', '.');
}

?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thanh toán - Waterbomb HCMC 2025</title>
    <link rel="stylesheet" href="thanhtoan.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700;900&display=swap"
      rel="stylesheet"
    />
    
    <script>
        // *** CẦU NỐI QUAN TRỌNG: PHP -> JAVASCRIPT ***
        // PHP "in" giá trị của biến $base_price (VD: 2500000) vào mã JavaScript.
        // Trình duyệt sẽ nhận được: const TICKET_BASE_PRICE = 2500000;
        // Tệp 'thanhtoan.js' sẽ sử dụng biến toàn cục (const) này để tính toán
        // tổng tiền mới khi người dùng bấm nút cộng/trừ mà không cần tải lại trang.
        const TICKET_BASE_PRICE = <?php echo $base_price; ?>;
    </script>
    
  </head>
  <body>
    <div class="container">
      <div class="checkout-panel">
        <div class="order-summary">
          <h2>Đơn hàng của bạn</h2>
          <div class="ticket-info">
            
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
          <form id="billing-form">
            <div class="form-group">
              <label for="name">Họ và tên</label>
              <input
                type="text"
                id="name"
                required placeholder="Nguyễn Văn A"
              />
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input
                type="email"
                id="email"
                required
                placeholder="email@example.com"
              />
            </div>
            <div class="form-group">
              <label for="phone">Số điện thoại</label>
              <input type="tel" id="phone" required placeholder="0123456789" />
            </div>

            <h3>Phương thức thanh toán</h3>
            <div class="payment-methods">
              <label
                ><input type="radio" name="payment" value="momo" checked /> Ví
                MoMo</label
              >
              <label
                ><input type="radio" name="payment" value="card" /> Thẻ Tín
                dụng/Ghi nợ</label
              >
              <label
                ><input type="radio" name="payment" value="bank" /> Chuyển khoản
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