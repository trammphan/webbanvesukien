<?php
session_start();

// Xóa toàn bộ session
session_unset();
session_destroy();

// Xóa cookies (nếu còn)
setcookie("email", "", time() - 3600, "/");
setcookie("user_name", "", time() - 3600, "/");
//setcookie("id", "", time() - 3600, "/");

// Quay lại trang dangnhap.php
header("Location: dangnhap.php");
exit();
?>
