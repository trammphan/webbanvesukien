<?php
require_once __DIR__ . '/auth.php';
?>
<div class="header-actions">
    <?php if (is_logged_in()): ?>
        <a href="nguoidung.php" class="btn-signin"><i class="fas fa-user-circle"></i></a>
    <?php else: ?>
        <a href="dangnhap.php" class="btn-signin">Đăng nhập</a>
        <a href="dangky.php" class="btn-signin">Đăng ký</a>
    <?php endif; ?>
</div>


