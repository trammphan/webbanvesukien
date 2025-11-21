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
                        // Include header_actions.php to access should_show_ticket()
                        require_once __DIR__ . '/header_actions.php';
                        
                        $is_logged_in = isset($_COOKIE['email']);
                        
                        // Set ticket link based on login status
                        $ticket_link = $is_logged_in 
                            ? "lich_su_mua_ve.php" 
                            : "dangnhap.php?redirect=" . urlencode("lich_su_mua_ve.php");
                        
                        // Only show ticket link if user is not admin or organizer
                        if (should_show_ticket()): ?>
                        <li><a href="<?php echo $ticket_link; ?>" id="ticket-link">Vé của tôi</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>

            <?php 
                require_once __DIR__ . '/auth.php';
                // Include header_actions.php is already done at the top
            ?>
                </div>
            </div>
        </div>
        <div class="mobile-menu" id="mobileMenu">
            <ul>
                <!-- <li><a href="#taosukien" onclick="toggleMobileMenu()">Tạo sự kiện</a></li> -->
                <?php if (should_show_ticket()): ?>
                <li><a href="<?php echo $ticket_link; ?>" onclick="toggleMobileMenu()">Vé của tôi</a></li>
                <?php endif; ?>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Lấy các phần tử cần thiết
        var menuToggle = document.querySelector('.menu-toggle');
        var mobileMenu = document.getElementById('mobileMenu');
        var menuLinks = document.querySelectorAll('.mobile-menu a');

        // Hàm toggle menu
        function toggleMobileMenu() {
            if (mobileMenu) {
                mobileMenu.style.display = mobileMenu.style.display === 'block' ? 'none' : 'block';
            }
        }

        // Thêm sự kiện click cho nút menu
        if (menuToggle) {
            menuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleMobileMenu();
            });
        }

        // Đóng menu khi click bên ngoài
        document.addEventListener('click', function(e) {
            if (mobileMenu && !mobileMenu.contains(e.target) && !menuToggle.contains(e.target)) {
                mobileMenu.style.display = 'none';
            }
        });

        // Đóng menu khi click vào các link trong menu
        menuLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                mobileMenu.style.display = 'none';
            });
        });
    });

    // Giữ lại hàm gốc để tương thích ngược
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