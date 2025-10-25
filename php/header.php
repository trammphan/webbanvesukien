<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Phạm Huỳnh Ngân">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="./images/fav-icon.png" rel="icon" type="image/vnd.microsoft.icon">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="../index/css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700;800&family=Nunito:wght@300;400;500;600;700;800;900&family=Source+Sans+Pro:wght@300;400;600;700;900&display=swap" rel="stylesheet">
</head>

<body>
    <header class="main-header">
        <div class="header-container">
            <div class="header-logo">
                <a href="../index/index.php" style="color: #ffffff; text-decoration: none; font-size: 24px; font-weight: bold;">Vibe4</a>
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
                <nav class="header-nav">
                    <ul>
                        <li><a href="#taosukien">Tạo sự kiện</a></li>
                        <li><a href="#vecuatoi">Vé của tôi</a></li>
                    </ul>
                </nav>

            <div class="header-actions">
                <a href="../PTHT-Web/dangnhap.php" class="btn-signin">Đăng nhập</a> 
                <a href="../PTHT-Web/dangky.php" class="btn-signup">Đăng ký</a> 
            </div>
                </div>
            </div>
        </div>
    </header>
    <nav class="category-bar">
        <div class="category-container">
            <ul class="category-list">
                <li><a href="../sukien/sukien.html?loai_sukien=LSK03" class="category-item active">Concert🔥</a></li>
                <li><a href="../sukien/sukien.html?loai_sukien=LSK02" class="category-item">Festival</a></li>
                <li><a href="../sukien/sukien.html?loai_sukien=LSK01" class="category-item">Liveshow</a></li>
            </ul>
        </div>
    </nav>
    
    <main>