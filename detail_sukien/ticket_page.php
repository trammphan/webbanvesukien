<?php
// --- PHẦN 1: DỮ LIỆU VÀ LOGIC PHP (SERVER-SIDE) ---

// 1. Kết nối CSDL và lấy MaSK từ URL
include '../sukien/connect.php'; // Đảm bảo đường dẫn này chính xác

if (!isset($_GET['MaSK']) || empty($_GET['MaSK'])) {
    echo "Lỗi: Không tìm thấy mã sự kiện.";
    exit;
}
$maSK = $_GET['MaSK'];

// 2. Lấy thông tin chi tiết sự kiện từ CSDL
// SỬA LỖI 1: Dùng Prepared Statements để chống SQL Injection
$stmt = $conn->prepare("SELECT s.TenSK, s.Tgian, d.TenTinh 
                       FROM sukien s 
                       JOIN diadiem d ON s.MaDD = d.MaDD 
                       WHERE s.MaSK = ?");
$stmt->bind_param("s", $maSK); // 's' nghĩa là $maSK là kiểu string
$stmt->execute();
$event_result = $stmt->get_result();

if ($event_result->num_rows == 0) {
    echo "Lỗi: Không tìm thấy sự kiện với mã " . htmlspecialchars($maSK);
    exit;
}
$event_details = $event_result->fetch_assoc();


// 3. Lấy thông tin các loại vé của sự kiện này từ CSDL
// SỬA LỖI 1 (SQL Injection) và SỬA LỖI 2 (Thêm v.MoTa)
$stmt = $conn->prepare("SELECT v.MLV, v.TenLV, sl.GiaVe, v.MoTa 
                       FROM sukien_loaive sl 
                       JOIN loaive v ON sl.MaLoaiVe = v.MLV 
                       WHERE sl.MaSK = ?");
$stmt->bind_param("s", $maSK);
$stmt->execute();
$tickets_result = $stmt->get_result(); // Đổi tên biến để tránh xung đột

// 4. Tạo mảng $ticket_types động
$ticket_types = [];
// SỬA LỖI 2: Lặp qua $tickets_result
while ($row = $tickets_result->fetch_assoc()) { 
    // Dùng MLV (ví dụ 'LV01') làm key
    $ticket_types[$row['MLV']] = [
        'name' => $row['TenLV'],
        'price' => $row['GiaVe'],
        'description' => $row['MoTa'] // Dòng này bây giờ đã hoạt động chính xác
    ];
}

// 5. Hàm trợ giúp (giữ nguyên)
function format_currency($amount) {
    return number_format($amount, 0, ',', '.') . ' ₫';
}
// --- KẾT THÚC PHẦN PHP ---
?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chọn vé: <?php echo htmlspecialchars($event_details['TenSK']); ?></title>
    <link rel="stylesheet" href="seatmap.css" />
    
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700;900&display=swap"
      rel="stylesheet"
    />

  </head>
  <body>
    <section id="tickets" class="seat-map-section">
      <h2 class="section-title">CHỌN KHU VỰC VÉ CỦA BẠN</h2>
      <div class="page-container">
      
        <div class="seat-map-container">
          <p>Bấm vào khu vực để chọn vé</p>
          <div class="seat-map">
            <?php foreach ($ticket_types as $id => $ticket): ?>
              <div class="zone seat-zone" id="<?php echo $id; ?>" data-id="<?php echo $id; ?>"
                data-description="<?php echo htmlspecialchars($ticket['description']); ?>">
                <?php echo htmlspecialchars($ticket['name']); ?>
              </div>
            <?php endforeach; ?>
          </div>

          <div id="ticket-description-container">
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
              <h3><?php echo htmlspecialchars($event_details['TenSK']); ?></h3>
              <p>🕒 <?php echo htmlspecialchars(date("d/m/Y", strtotime($event_details['Tgian']))); ?></p>
              <p>📍 <?php echo htmlspecialchars($event_details['TenTinh']); ?></p>
          </div>

          <div class="price-list">
              <h4>Giá vé</h4>
              <ul>
                  <?php foreach ($ticket_types as $id => $ticket): ?>
                      <li class="price-item" data-id="<?php echo $id; ?>">
                          <span class="ticket-name"><?php echo htmlspecialchars($ticket['name']); ?></span>
                          <span class="ticket-price"><?php echo format_currency($ticket['price']); ?></span>
                      </li>
                  <?php endforeach; ?>
              </ul>
          </div>
          
          </div>
      </div>
    </section>

    <script>
        // Dữ liệu này bây giờ đã bao gồm 'description'
        const ticketData = <?php echo json_encode($ticket_types); ?>;
    </script>
    
    <script src="seatmap.js"></script> 
  </body>
</html>