<?php
    // FIX 1: VẪN CẦN SESSION cho header.php và auth.php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Lấy thông tin auth (cần cho header.php)
    require_once __DIR__ . '/../php/auth.php';
    $user_table = $_SESSION['user_table'] ?? '';
?>
<?php
    // Giữ tiêu đề và CSS riêng của trang
    $page_title = 'Chi tiết sự kiện';
    $additional_css = ['chitietsk_1.css'];
    require_once 'header.php';
?>
<main>
                <?php
            include 'connect_1.php';
            if (isset($_GET['MaSK'])) {
                $maSK = $_GET['MaSK'];
                
                // ******** FIX 2: SỬA LỖI BẢO MẬT SQL INJECTION (BẮT BUỘC PHẢI CÓ) ********
                $stmt = $conn->prepare("SELECT l.TenLoaiSK, s.TenSK, s.img_sukien, s.Tgian, s.mota, d.TenTinh, MIN(lv.Gia) AS MinPrice
                                        FROM sukien s JOIN diadiem d on s.MaDD = d.MaDD 
                                        JOIN loaisk l on s.MaLSK = l.MaloaiSK
                                        JOIN loaive lv on lv.MaSK = s.MaSK
                                        WHERE s.MaSK = ?
                                        GROUP BY s.MaSK");
                $stmt->bind_param("s", $maSK);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                
                // ******** FIX 3: KIỂM TRA NẾU SỰ KIỆN KHÔNG TỒN TẠI (NÊN CÓ) ********
                if (!$row) {
                    echo "<p style='text-align: center; margin: 20px; font-size: 1.2rem;'>Không tìm thấy sự kiện này.</p>";
                    include 'footer.php'; // Thêm footer cho nhất quán
                    exit; 
                }

                // ******** FIX 2 (Tiếp theo): SỬA SQL INJECTION CHO TRUY VẤN LOẠI VÉ ********
                $stmt_ve = $conn->prepare("SELECT TenLoai, Gia
                                           FROM loaive
                                           WHERE MaSK = ?
                                           ORDER BY Gia DESC");
                $stmt_ve->bind_param("s", $maSK);
                $stmt_ve->execute();
                $ve_result = $stmt_ve->get_result();

            }
            else {
                echo "<p style='text-align: center; margin: 20px; font-size: 1.2rem;'>Không tìm thấy sự kiện.</p>";
                include 'footer.php';
                exit;
            }
        ?>


            <div class="cardWrap">
                <div class= "card">
                    <div class="cardLeft">
                        <h1><?=htmlspecialchars($row['TenLoaiSK'])?></h1>
                        <div class="title">
                            <h2><?=htmlspecialchars($row['TenSK'])?></h2>
                        </div>

                        <div class="name">
                            <span>Địa Điểm</span>
                            <h2><?=htmlspecialchars($row['TenTinh'])?></h2>
                        </div>

                        <div class="price">
                            <span>Giá vé</span>
                            <h2><?=is_numeric($row['MinPrice']) ? number_format($row['MinPrice'], 0, ',', '.') : $row['MinPrice']?>VND++</h2>
                        </div>

                        <div class="time">
                            <span>Thời gian</span>
                            <h2><?= "Từ: " . (new DateTime($row['Tgian']))->format('H:i, d/m/Y') ?></h2>
                        </div>
                    </div>

                    <div class="cardRight" style="background-image: url('<?=htmlspecialchars($row['img_sukien'])?>'); background-size: cover; background-position: center;">
                        <div class="button">
                            <?php

                            if (isset($_COOKIE['email']) && !empty($_COOKIE['email'])) {
                                // Nếu ĐÃ ĐĂNG NHẬP (dựa trên cookie): Trỏ đến trang mua vé
                                echo '<a class="buy" href="ticket_page.php?MaSK=' . htmlspecialchars($maSK) . '">MUA VÉ</a>';
                            } else {
                                // Nếu CHƯA ĐĂNG NHẬP: Trỏ đến trang đăng nhập

                                // 1. Xác định URL mục tiêu (trang mua vé)
                                $target_url = 'ticket_page.php?MaSK=' . htmlspecialchars($maSK);
                                
                                // 2. Tạo URL đăng nhập, đính kèm URL mục tiêu vào tham số 'redirect'
                                $login_url = 'dangnhap.php?redirect=' . urlencode($target_url);
                                
                                // 3. In nút "MUA VÉ" trỏ đến trang đăng nhập
                                echo '<a class="buy" href="' . $login_url . '" id="buy-ticket-link">MUA VÉ</a>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mota" >
                <h2 class= "tieude">Giới thiệu sự kiện</h2>
                <p class= "noidung"><?=htmlspecialchars($row['mota'])?></p>
                </div>
            <div class="chitietve"> <h2 class="tieude">Các loại vé</h2>
                <?php
                    // Đảm bảo $ve_result là đối tượng hợp lệ trước khi lặp
                    if ($ve_result !== FALSE && $ve_result->num_rows > 0) {
                        while ($ve_row = $ve_result->fetch_assoc()) {
                            echo '<div class="loaiVeItem">
                                    <h3 class="tenLoaiVe">'.htmlspecialchars($ve_row['TenLoai']).'</h3>
                                    <p class="giaVe">Giá vé: <strong>'.number_format($ve_row['Gia'], 0, ',', '.').' VND</strong></p>
                                </div>';    
                        }
                    } else {
                        echo "<p>Thông tin loại vé đang được cập nhật.</p>";
                    }
                ?>
            </div>
</main> 
<?php
require_once 'footer.php'; 
?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // === PHẦN 2: XỬ LÝ CHO NÚT "MUA VÉ" (TRÊN TRANG NÀY) ===
    var buyTicketLink = document.getElementById("buy-ticket-link");

    // Kiểm tra an toàn: Chỉ chạy nếu tìm thấy nút "Mua vé"
    if (buyTicketLink) {
        buyTicketLink.addEventListener("click", function(event) {
            var destinationUrl = this.href; 
            
            event.preventDefault(); // Ngăn chuyển trang

            // Hiển thị thông báo
            Swal.fire({
                title: 'Bạn chưa đăng nhập!',
                text: 'Vui lòng đăng nhập để mua vé. Đang chuyển trang...',
                icon: 'info',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            }).then(() => {
                // Sau 3 giây, chuyển trang
                window.location.href = destinationUrl;
            });
        });
    } 

});
</script>