<!-- <?php require_once __DIR__ . '/auth.php'; ?>  -->
<?php
// Bắt đầu logic kiểm tra đăng nhập và xác định liên kết
$logged_in = false;
$profile_link = '#';
$user_name = 'Tài khoản';
$user_role = 'guest';

// Kiểm tra xem các cookie cần thiết có tồn tại không
if (isset($_COOKIE['email']) && isset($_COOKIE['user_name']) && isset($_COOKIE['user_role'])) {
    $logged_in = true;
    $user_name = htmlspecialchars($_COOKIE['user_name']);
    $user_role = $_COOKIE['user_role'];

    // Ánh xạ vai trò (user_role) tới trang profile tương ứng
    switch ($user_role) {
        case 'quantrivien':
            $profile_link = 'admin.php'; // Quản trị viên -> admin.php
            break;
        case 'nhatochuc':
            $profile_link = 'nhatochuc.php'; // Nhà tổ chức -> nhatochuc.php
            break;
        case 'nhanviensoatve':
            $profile_link = 'nhanvien.php'; // Nhân viên soát vé -> nhanvien.php
            break;
        case 'khachhang':
        default:
            $profile_link = 'nguoidung.php'; // Khách Hàng (hoặc mặc định) -> nguoidung.php
            break;
    }
}
// Kết thúc logic
?>
<div class="header-actions">
    <?php if (is_logged_in()): ?>
        <!-- <a href="nguoidung.php" class="btn-signin"><i class="fas fa-user-circle"></i></a> -->
        <a href="<?php echo $profile_link; ?>" class="btn-signin" 
           title="Trang cá nhân: <?php echo $user_name; ?> (<?php echo $user_role; ?>)">
            <i class="fas fa-user-circle" ></i> 
        </a>
    <?php else: ?>
        <a href="dangnhap.php" class="btn-signin">Đăng nhập</a>
        <a href="dangky.php" class="btn-signin">Đăng ký</a>
    <?php endif; ?>
</div>


