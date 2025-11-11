<?php
   
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
<style>
    /* Phần CSS này đã được chỉnh sửa để giống với hình ảnh (nền trắng) 
      mà bạn đã cung cấp (image_e8cbf6.png)
    */
    
    /* Container chứa NỘI DUNG (văn bản + sơ đồ) */
    .collapsible-content {
        position: relative;
        overflow: hidden;
        /* THAY ĐỔI 1: Tăng chiều cao tối đa khi thu gọn */
        max-height: 200px; /* Tăng từ 120px lên 200px */
        transition: max-height 0.5s ease-out;
    }

    /* Lớp phủ mờ ở dưới đáy */
    .collapsible-content::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        /* THAY ĐỔI 2: Tăng chiều cao lớp phủ mờ */
        height: 75px; /* Tăng từ 50px lên 75px */
        background: linear-gradient(to bottom, transparent, white);
        pointer-events: none; 
        transition: opacity 0.3s ease;
    }
    
    /* Nút bấm mũi tên */
    .mota-toggle {
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        height: 1px;
        width: 100%;
        /* THAY ĐỔI 3: Xóa margin-top âm, thay bằng padding */
        margin-top: 0; 
        position: relative;
        z-index: 2;
    }

    /* THAY ĐỔI 4: Thay thế mũi tên tam giác bằng mũi tên CHỮ V (chevron) */
    .mota-toggle .arrow {
        border: solid #ffffffff; /* Màu mũi tên */
        border-width: 0 3px 3px 0; /* Chỉ vẽ 2 cạnh của hình vuông */
        display: inline-block;
        padding: 4px; /* Kích thước mũi tên */
        transform: rotate(45deg); /* Xoay 45 độ -> 'v' */
        -webkit-transform: rotate(45deg);
        transition: transform 0.3s ease;
    }

    /* --- Trạng thái KHI ĐÃ MỞ RỘNG --- */

    .collapsible-content.expanded {
        max-height: 2000px; 
        transition: max-height 0.5s ease-in;
    }

    .collapsible-content.expanded::after {
        opacity: 0; /* Ẩn lớp phủ mờ */
    }

    /* THAY ĐỔI 5: Xoay mũi tên (chevron) lên trên */
    .mota-toggle.expanded .arrow {
        transform: rotate(-135deg); /* Xoay ngược lại -> '^' */
        -webkit-transform: rotate(-135deg);
    }

    /* --- SỬA LỖI HÌNH ẢNH QUÁ LỚN (Giữ nguyên) --- */
    .sodo img {
        width: 100%; 
        max-width: 800px; 
        height: auto; 
        display: block; 
        margin: 0 auto; 
        border-radius: 8px;
    }

</style>
<main>
                <?php
            include 'connect_1.php';
            if (isset($_GET['MaSK'])) {
                $maSK = $_GET['MaSK'];
                
                // ******** FIX 2: SỬA LỖI BẢO MẬT SQL INJECTION (BẮT BUỘC PHẢI CÓ) ********
                // <!-- BỔ SUNG MỚI (1/3): Thêm 's.img_sodo' vào câu SELECT -->
                $stmt = $conn->prepare("SELECT l.TenLoaiSK, s.TenSK, s.img_sukien, s.Tgian, s.mota, d.TenTinh, MIN(lv.Gia) AS MinPrice, s.img_sodo
                                        FROM sukien s JOIN diadiem d on s.MaDD = d.MaDD 
                                        JOIN loaisk l on s.MaLSK = l.MaloaiSK
                                        JOIN loaive lv on lv.MaSK = s.MaSK
                                        WHERE s.MaSK = ?
                                        GROUP BY s.MaSK, s.img_sodo"); // <!-- BỔ SUNG MỚI (2/3): Thêm 's.img_sodo' vào GROUP BY -->
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
                $stmt_ve = $conn->prepare("SELECT TenLoai, Gia, MoTa
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
            <!-- BỔ SUNG MỚI (2/3): Sửa lại cấu trúc HTML của phần .mota -->
<div class="mota" >
                <!-- 1. Bọc MỌI THỨ (text + sơ đồ) trong .collapsible-content -->
                <div class="collapsible-content" id="mota-container">
                    
                    <!-- Phần Giới thiệu sự kiện -->
                    <h2 class= "tieude">Giới thiệu sự kiện</h2>
                    <p class= "noidung"><?=nl2br(htmlspecialchars($row['mota']))?></p>
                    
                    <!-- Phần Sơ đồ chỗ ngồi (đã được chuyển vào bên trong) -->
                    <?php if (!empty($row['img_sodo'])): // Chỉ hiển thị nếu có ảnh sơ đồ ?>
                    <div class="sodo" style="margin-top: 20px;"> <!-- Thêm chút khoảng cách -->
                        <h2 class="tieude">Sơ đồ chỗ ngồi</h2>
                        <img src="<?=htmlspecialchars($row['img_sodo'])?>" alt="Sơ đồ chỗ ngồi của <?php echo htmlspecialchars($row['TenSK']); ?>">
                    </div>
                    <?php endif; ?>

                </div>
                <!-- 2. Thêm nút bấm mũi tên (nằm bên ngoài .collapsible-content) -->
                <div class="mota-toggle" id="mota-toggle-btn">
                    <div class="arrow"></div>
                </div>
            </div>
            <!-- KẾT THÚC BỔ SUNG MỚI (2/3) -->
            <div class="chitietve"> <h2 class="tieude">Các loại vé</h2>
                <?php
                    // Đảm bảo $ve_result là đối tượng hợp lệ trước khi lặp
                    if ($ve_result !== FALSE && $ve_result->num_rows > 0) {
                        while ($ve_row = $ve_result->fetch_assoc()) {
                            echo '<div class="loaiVeItem">
                                    <h3 class="tenLoaiVe">'.htmlspecialchars($ve_row['TenLoai']).'</h3>
                                    <p class="giaVe">Giá vé: <strong>'.number_format($ve_row['Gia'], 0, ',', '.').' VND</strong></p>';
                            
                            // <!-- BỔ SUNG MỚI (2/2): Hiển thị mô tả (dưới dạng gạch đầu dòng) -->
                            if (!empty($ve_row['MoTa'])) {
                                // Tách chuỗi mô tả bằng ký tự '|'
                                $moTaTungDong = explode('|', $ve_row['MoTa']);
                                
                                echo '<ul class="moTaVe">'; // Bắt đầu danh sách
                                foreach ($moTaTungDong as $dong) {
                                    // Hiển thị từng dòng mô tả dưới dạng <li>
                                    echo '<li>' . htmlspecialchars(trim($dong)) . '</li>';
                                }
                                echo '</ul>'; // Kết thúc danh sách
                            }
                            // <!-- KẾT THÚC BỔ SUNG MỚI -->

                            echo '</div>';    
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

    // <!-- SỬA LỖI (3/3): Chuyển logic Ẩn/Hiện sang 'window.onload' -->
    // 'DOMContentLoaded' chạy khi HTML tải xong, nhưng 'load' chạy khi
    // CẢ HÌNH ẢNH (img_sodo) tải xong. Điều này đảm bảo 'scrollHeight'
    // có giá trị chính xác.
});

window.addEventListener("load", function() {
    var motaContainer = document.getElementById("mota-container");
    var motaToggleButton = document.getElementById("mota-toggle-btn");

    if (motaContainer && motaToggleButton) {
        // Kiểm tra xem nội dung có bị tràn (cần ẩn) hay không
        // Nếu nội dung ngắn, không cần hiển thị nút
        if (motaContainer.scrollHeight <= 120) { // 120px là max-height trong CSS
            motaToggleButton.style.display = 'none';
            motaContainer.style.maxHeight = 'none'; // Hiển thị đầy đủ
            motaContainer.classList.add('expanded'); // Thêm class để ẩn lớp phủ
        } else {
            // Thêm sự kiện click
            motaToggleButton.addEventListener("click", function() {
                motaContainer.classList.toggle("expanded");
                motaToggleButton.classList.toggle("expanded");
            });
        }
    }
});
// <!-- KẾT THÚC SỬA LỖI (3/3) -->

</script>