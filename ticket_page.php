<?php
// --- PHẦN 1: DỮ LIỆU VÀ LOGIC PHP (SERVER-SIDE) ---
// Khai báo một mảng kết hợp (associative array) để lưu trữ thông tin chi tiết của sự kiện.
$event_details = [
    'name' => 'WATERBOMB HO CHI MINH CITY 2025',
    'time' => '26.07.2025',
    'location' => 'SALA CITY'
];

// Khai báo một mảng kết hợp lồng nhau.
// Mỗi 'key' (ví dụ: 'the-heart-1') là một ID duy nhất cho loại vé.
// Mỗi 'value' là một mảng con chứa thông tin chi tiết của vé đó (tên, giá, màu sắc).
$ticket_types = [
    'the-heart-1' => ['name' => 'THE HEART 1', 'price' => 2500000, 'color' => '#b71c1c'],
    'the-heart-2' => ['name' => 'THE HEART 2', 'price' => 2500000, 'color' => '#b71c1c'],
    'the-face-1' => ['name' => 'THE FACE 1', 'price' => 2000000, 'color' => '#689f38'],
    'the-face-2' => ['name' => 'THE FACE 2', 'price' => 2000000, 'color' => '#689f38'],
    'the-energy-1' => ['name' => 'THE ENERGY 1', 'price' => 1800000, 'color' => '#fbc02d'],
    'the-energy-2' => ['name' => 'THE ENERGY 2', 'price' => 1800000, 'color' => '#fbc02d'],
    'the-voice-1' => ['name' => 'THE VOICE 1', 'price' => 1500000, 'color' => '#1976d2'],
    'the-voice-2' => ['name' => 'THE VOICE 2', 'price' => 1500000, 'color' => '#1976d2'],
    'the-style-1' => ['name' => 'THE STYLE 1', 'price' => 1000000, 'color' => '#795548'],
    'the-style-2' => ['name' => 'THE STYLE 2', 'price' => 1000000, 'color' => '#795548'],
    'the-move-1' => ['name' => 'THE MOVE 1', 'price' => 800000, 'color' => '#f57c00'],
    'the-move-2' => ['name' => 'THE MOVE 2', 'price' => 800000, 'color' => '#f57c00'],
    'all-rounder-1' => ['name' => 'ALL-ROUNDER 1', 'price' => 10000000, 'color' => '#d32f2f'],
    'all-rounder-2' => ['name' => 'ALL-ROUNDER 2', 'price' => 10000000, 'color' => '#d32f2f'],
];

// Định nghĩa một hàm trợ giúp (helper function) để định dạng số thành tiền tệ VNĐ.
// Ví dụ: 2500000 -> "2.500.000 ₫"
function format_currency($amount) {
    // number_format($number, $decimals, $decimal_separator, $thousands_separator)
    return number_format($amount, 0, ',', '.') . ' ₫';
}
// --- KẾT THÚC PHẦN PHP ---
?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Waterbomb Ho Chi Minh City 2025</title>
    <link rel="stylesheet" href="seatmap.css" />
    
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700;900&display=swap"
      rel="stylesheet"
    />

    <style>
      <?php foreach ($ticket_types as $id => $ticket): ?>
      /* Vòng lặp PHP này duyệt qua mảng $ticket_types.
        Với mỗi vé, nó tạo ra một quy tắc CSS.
        Ví dụ: cho vé 'the-heart-1', nó sẽ tạo ra:
        #the-heart-1 { background-color: #b71c1c; border-color: #b71c1c; }
        Điều này giúp gán màu cho các khu vực trên bản đồ một cách tự động.
      */
      #<?php echo $id; ?> { background-color: <?php echo $ticket['color']; ?>;
          border-color: <?php echo $ticket['color']; ?>; }
      <?php endforeach; ?>
    </style>
  </head>
  <body>
    <section id="tickets" class="seat-map-section">
      <h2 class="section-title">CHỌN KHU VỰC VÉ CỦA BẠN</h2>
      <div class="page-container">
      
        <div class="seat-map-container">
          <h3>Chọn khu vực</h3>
          <p>Bấm vào khu vực để chọn vé</p>
          <div class="seat-map">
              <div class="zone" id="stage">STAGE</div>
              <div class="zone" id="foh">FOH</div>
              
              <?php foreach ($ticket_types as $id => $ticket): ?>
                <div class="zone seat-zone" id="<?php echo $id; ?>" data-id="<?php echo $id; ?>">
                    <?php echo $ticket['name']; ?>
                </div>
              <?php endforeach; ?>
          </div>

          <div class="ticket-calculator" id="ticket-calculator">
              <div class="quantity-control">
                  <label>Số lượng:</label>
                  <div class="quantity-buttons">
                      <button type="button" class="quantity-btn" id="minus-btn">-</button>
                      <span id="quantity-display">1</span>
                      <button type="button" class="quantity-btn" id="plus-btn">+</button>
                  </div>
              </div>
              <div class="tier-total">
                  <span>TỔNG:</span>
                  <div>
                      <span id="total-price-display">0</span> VNĐ
                  </div>
              </div>
          </div>
          
          <button class="next-button" id="next-btn">Vui lòng chọn vé</button>
          </div>
          
      <div class="sidebar">
          <div class="event-info">
              <h3><?php echo htmlspecialchars($event_details['name']); ?></h3>
              <p>🕒 <?php echo htmlspecialchars($event_details['time']); ?></p>
              <p>📍 <?php echo htmlspecialchars($event_details['location']); ?></p>
          </div>

          <div class="price-list">
              <h4>Giá vé</h4>
              <ul>
                  <?php foreach ($ticket_types as $id => $ticket): ?>
                      <li class="price-item" data-id="<?php echo $id; ?>">
                          <span class="color-box" style="background-color: <?php echo $ticket['color']; ?>;"></span>
                          <span class="ticket-name"><?php echo htmlspecialchars($ticket['name']); ?></span>
                          <span class="ticket-price"><?php echo format_currency($ticket['price']); ?></span>
                      </li>
                  <?php endforeach; ?>
              </ul>
          </div>
          
          </div>
      </div>
    </section>

    <script src="script.js"></script>

    <script>
        const ticketData = <?php echo json_encode($ticket_types); ?>;
    </script>
    
    <script src="seatmap.js"></script>

  </body>
</html>