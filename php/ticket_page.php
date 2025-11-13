<?php
session_start();
// 1. Kết nối CSDL và lấy MaSK từ URL
include 'connect_1.php'; // Đảm bảo đường dẫn này chính xác

if (!isset($_GET['MaSK']) || empty($_GET['MaSK'])) {
    echo "Lỗi: Không tìm thấy mã sự kiện.";
    exit;
}
$maSK = $_GET['MaSK'];

// 2. Lấy thông tin chi tiết sự kiện từ CSDL
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
$stmt = $conn->prepare("
    SELECT 
        v.MaLoai, 
        v.TenLoai, 
        v.Gia, 
        v.MoTa, -- <!-- BỔ SUNG MỚI (1/2): Lấy cột MoTa -->
        COUNT(t.MaVe) AS SoVeConLai 
    FROM 
        loaive v 
    LEFT JOIN 
        ve t ON v.MaLoai = t.MaLoai AND t.MaTT IS NULL 
    WHERE 
        v.MaSK = ? 
    GROUP BY 
        v.MaLoai, v.TenLoai, v.Gia, v.MoTa -- <!-- BỔ SUNG MỚI (2/2): Thêm MoTa vào GROUP BY -->
");

$stmt->bind_param("s", $maSK); 
$stmt->execute();
$tickets_result = $stmt->get_result(); 

// 4. Tạo mảng $ticket_types động
$ticket_types = [];
while ($row = $tickets_result->fetch_assoc()) { 
    // Sử dụng đúng tên cột từ câu truy vấn đã sửa
    $ticket_types[$row['MaLoai']] = [
        'name' => $row['TenLoai'],
        'price' => $row['Gia'],
        // <!-- BỔ SUNG MỚI (3/3): Gán giá trị MoTa -->
        'description' => $row['MoTa'] ?? '', // Lấy mô tả, nếu không có thì để trống
        'remaining' => $row['SoVeConLai'] 
    ];
}

// 5. Hàm trợ giúp 
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
    <link rel="stylesheet" href="../css/ticket_page.css" />
    <link href="../img/fav-icon.png" rel="icon" type="image/vnd.microsoft.icon">
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
            <?php
                    // Kiểm tra xem vé còn hay hết
            $is_sold_out = ($ticket['remaining'] == 0);
            $sold_out_class = $is_sold_out ? 'sold-out' : '';
            ?>
    
            <div class="zone seat-zone <?php echo $sold_out_class; ?>" 
                id="<?php echo $id; ?>" 
                data-id="<?php echo $id; ?>"
                data-name="<?php echo htmlspecialchars($ticket['name']); ?>"
                data-remaining="<?php echo $ticket['remaining']; ?>">
        
                <?php echo htmlspecialchars($ticket['name']); ?>    
                <?php if ($is_sold_out): ?>
                    <span>(Hết vé)</span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <div id="ticket-description-container" style="display: none; margin-top: 20px;"></div>
        <div class="ticket-calculator" id="ticket-calculator">
              <div class="quantity-control">
                  <label>Số lượng:</label>
                  <div class="quantity-buttons">
                      <button type="button" class="quantity-btn" id="minus-btn">-</button>
                      <span id="quantity-display">0</span>
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
                      <li class="price-item <?php echo ($ticket['remaining'] == 0) ? 'sold-out' : ''; ?>" data-id="<?php echo $id; ?>">
                          <span class="ticket-name">
                            <?php echo htmlspecialchars($ticket['name']); ?>
                            <?php if ($ticket['remaining'] == 0): ?>
                                <span>(Hết vé)</span>
                            <?php endif; ?>
                          </span>
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

        const isUserLoggedIn = <?php echo isset($_COOKIE['email']) ? 'true' : 'false'; ?>;
    </script>
    <script src="../js/ticket_page.js"></script>
  </body>
</html>