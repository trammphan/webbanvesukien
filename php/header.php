<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="../img/fav-icon.png" rel="icon" type="image/vnd.microsoft.icon">
    <title><?php echo htmlspecialchars($page_title ?? 'Vibe4'); ?></title>
    <?php if (isset($additional_head)) { echo $additional_head; } ?>
    <link rel="stylesheet" href="../css/header.css">
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css_file): ?>
            <link rel="stylesheet" href="../css/<?php echo $css_file; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/v4-shims.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700;800&family=Nunito:wght@300;400;500;600;700;800;900&family=Source+Sans+Pro:wght@300;400;600;700;900&display=swap" rel="stylesheet">
<link
  rel="stylesheet"
  href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0"
/>
<link rel="stylesheet" href="../css/chatbot.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>
</head>

<body>
    <header class="main-header">
        <div class="header-container">
            <div class="header-logo">
                <a href="index.php" style="color: #ffffff; text-decoration: none; font-size: 24px; font-weight: bold;">Vibe4</a>
            </div>

            <div class="header-search">
                <form action="index.php" method="get"> 
                    <input type="text" placeholder="Tìm kiếm sự kiện, địa điểm..." name="q" class="search-input" value="<?php echo htmlspecialchars($search_query ?? ''); ?>">
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <div class="menu-toggle" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </div>
            
            <div class="header-right">
                <nav class="header-nav">
                    <ul>
                        <!-- <li><a href="#taosukien">Tạo sự kiện</a></li>   -->
                        <?php
                        $is_logged_in = isset($_COOKIE['email']);

                         if ($is_logged_in) {
                        $ticket_link = "lich_su_mua_ve.php";
                        } else {
                            $redirect_url = urlencode("lich_su_mua_ve.php");
                            $ticket_link = "dangnhap.php?redirect=" . $redirect_url;
                        }
                        ?>
                        <li><a href="<?php echo $ticket_link; ?>" id="ticket-link">Vé của tôi</a></li>
                    </ul>
                </nav>

            <?php 
                require_once __DIR__ . '/auth.php';
                include __DIR__ . '/../php/header_actions.php'; 
            ?>
                </div>
            </div>
        </div>
        <div class="mobile-menu" id="mobileMenu">
            <ul>
                <li><a href="#taosukien" onclick="toggleMobileMenu()">Tạo sự kiện</a></li>
                <li><a href="<?php echo $ticket_link; ?>" onclick="toggleMobileMenu()">Vé của tôi</a></li>
                <?php if (is_logged_in()): ?>
                    <li><a href="<?php echo $profile_link; ?>" onclick="toggleMobileMenu()">Tài khoản</a></li>
                <?php else: ?>
                    <li><a href="dangnhap.php" onclick="toggleMobileMenu()">Đăng nhập</a></li>
                    <li><a href="dangky.php" onclick="toggleMobileMenu()">Đăng ký</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </header>
<script src="../js/search-autocomplete.js"></script>
<script>
    function toggleMobileMenu() {
        var menu = document.getElementById('mobileMenu');
        if (menu) {
            menu.classList.toggle('open');
        }
    }

    // Ẩn menu mobile khi quay lại màn hình lớn
    window.addEventListener('resize', function () {
        var menu = document.getElementById('mobileMenu');
        if (window.innerWidth > 922 && menu && menu.classList.contains('open')) {
            menu.classList.remove('open');
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
    // 1. Tìm liên kết "Vé của tôi"
    var myTicketLink = document.getElementById("ticket-link");

    // 2. Gắn sự kiện "click"
    myTicketLink.addEventListener("click", function(event) {
        var destinationUrl = this.href; 

        // 3. Kiểm tra nếu là link đăng nhập
        if (destinationUrl.includes("dangnhap.php")) {
            
            // 4. Ngăn chuyển trang ngay lập tức
            event.preventDefault(); 

            // 5. HIỆN THÔNG BÁO SWEETALERT2
            Swal.fire({
                title: 'Bạn chưa đăng nhập!',
                text: 'Đang chuyển đến trang đăng nhập...',
                icon: 'info',
                timer: 3000, // Tự động đóng sau 3 giây
                timerProgressBar: true, // Hiển thị thanh đếm lùi
                showConfirmButton: false // Ẩn nút "OK"
            }).then(() => {
                // 6. Hết 3 giây, tự động chuyển trang
                window.location.href = destinationUrl;
            });
        }
    });
});
</script>