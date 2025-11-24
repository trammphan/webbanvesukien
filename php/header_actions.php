<?php
$logged_in = false;
$profile_link = '#';
$user_name = 'Tài khoản';
$user_role = 'guest';
$show_ticket = true;

if (isset($_COOKIE['email']) && isset($_COOKIE['user_name']) && isset($_COOKIE['user_role'])) {
    $logged_in = true;
    $user_name = htmlspecialchars($_COOKIE['user_name']);
    $user_role = $_COOKIE['user_role'];
    
    $show_ticket = !in_array($user_role, ['quantrivien', 'nhatochuc', 'nhanviensoatve']);

    switch ($user_role) {
        case 'quantrivien':
            $profile_link = 'admin.php';
            break;
        case 'nhatochuc':
            $profile_link = 'nhatochuc.php';
            break;
        case 'nhanviensoatve':
            $profile_link = 'nhanvien.php';
            break;
        case 'khachhang':
        default:
            $profile_link = 'nguoidung.php';
            break;
    }
}

function should_show_ticket() {
    global $show_ticket;
    return $show_ticket;
}
?>
<div class="header-actions">
    <?php if ($logged_in): ?>
        <a href="<?php echo $profile_link; ?>" class="btn-signin" 
           title="Trang cá nhân: <?php echo $user_name; ?> (<?php echo $user_role; ?>)">
            <i class="fas fa-user-circle" ></i> 
        </a>
    <?php else: ?>
        <a href="dangnhap.php" class="btn-signin">Đăng nhập</a>
        <a href="dangky.php" class="btn-signin">Đăng ký</a>
    <?php endif; ?>
</div>