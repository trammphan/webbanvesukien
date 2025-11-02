<?php
    // File auth.php của bạn có thể đã khởi động session
    // nhưng để chắc chắn, chúng ta gọi session_start()
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/../php/auth.php';
    
    // Lấy loại user từ session (được gán lúc đăng nhập)
    $user_table = $_SESSION['user_table'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="../img/fav-icon.png" rel="icon" type="image/vnd.microsoft.icon">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="../css/header.css">
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css_file): ?>
            <link rel="stylesheet" href="../css/<?php echo $css_file; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700;800&family=Nunito:wght@300;400;500;600;700;800;900&family=Source+Sans+Pro:wght@300;400;600;700;900&display=swap" rel="stylesheet">
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
            
            <div class="header-right">
                <!-- thêm lịch sử mua vé -->
                <nav class="header-nav">
                    <ul>
                        <?php if (is_logged_in()): ?>
                            
                            <!-- *** BẮT ĐẦU CHỈNH SỬA *** -->
                            
                            <?php // Chỉ 'nhatochuc' mới thấy "Tạo sự kiện" ?>
                            <?php if ($user_table === 'nhatochuc'): ?>
                                <li><a href="tao_su_kien.php">Tạo sự kiện</a></li>
                            <?php endif; ?>

                            <?php // Chỉ 'khachhang' mới thấy "Vé của tôi" ?>
                            <?php if ($user_table === 'khachhang'): ?>
                                <li><a href="lich_su_mua_ve.php">Vé của tôi</a></li>
                            <?php endif; ?>
                            
                            <?php // Thêm liên kết cho Admin và NV Soát Vé (nếu cần) ?>
                             <?php if ($user_table === 'quantrivien'): ?>
                                <li><a href="admin.php">Trang Admin</a></li>
                            <?php endif; ?>
                             <?php if ($user_table === 'nhanviensoatve'): ?>
                                <li><a href="nhanvien.php">Trang Soát Vé</a></li>
                            <?php endif; ?>

                            <!-- *** KẾT THÚC CHỈNH SỬA *** -->
                            
                        <?php endif; ?>
                    </ul>
                </nav>

            <?php 
                include __DIR__ . '/../php/header_actions.php'; 
            ?>
                </div>
            </div>
        </div>
    </header>
    
    <main>
