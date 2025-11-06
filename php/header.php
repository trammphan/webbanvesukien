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
            
            <div class="header-right">
                <nav class="header-nav">
                    <ul>
                        <li><a href="#taosukien">Tạo sự kiện</a></li>
                        <li><a href="lich_su_mua_ve.php">Vé của tôi</a></li>
                    </ul>
                </nav>

            <?php 
                require_once __DIR__ . '/auth.php';
                include __DIR__ . '/../php/header_actions.php'; 
            ?>
                </div>
            </div>
        </div>
    </header>
