<?php
session_start();
?>
<?php
$redirect_url_hidden = ''; 
$redirect_url_href = '';   

if (isset($_GET['redirect'])) {
    $redirect_url_hidden = htmlspecialchars($_GET['redirect']);
    $redirect_url_href = '?redirect=' . urlencode($_GET['redirect']);
}


$additional_css = ['webstyle.css'];
$page_title = 'Quên mật khẩu';
$additional_head = <<<HTML
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
HTML;
?>
<?php require_once 'header.php'; ?>
<main>
        <article class="khungdungchung">
          <h2>QUÊN MẬT KHẨU</h2>
          <p style="text-align: center; margin-bottom: 20px; color: #555;">Vui lòng nhập email của bạn. Chúng tôi sẽ gửi một liên kết để đặt lại mật khẩu.</p>
<?php if (isset($_SESSION['thong_bao'])): ?>
    <div style="background-color: #d1e7dd; color: #0f5132; padding: 15px; margin-bottom: 20px; border: 1px solid #badbcc; border-radius: 5px; text-align: center;">
        <?php 
            echo $_SESSION['thong_bao']; 
            unset($_SESSION['thong_bao']); 
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['loi'])): ?>
    <div style="background-color: #f8d7da; color: #842029; padding: 15px; margin-bottom: 20px; border: 1px solid #f5c2c7; border-radius: 5px; text-align: center;">
        <?php 
            echo $_SESSION['loi']; 
            unset($_SESSION['loi']); 
        ?>
    </div>
<?php endif; ?>

          <form action="xu_ly_quen_mk.php" method="post">
             <input type="hidden" name="redirect" value="<?php echo $redirect_url_hidden; ?>">

             <div class="thongtin">
             <label for="email">
                <i class="fa-solid fa-envelope"></i>
                <input
                  type="email"
                  id="email"
                  name="email"
                  placeholder="Vui lòng nhập email của bạn"
                  required
                />
             </label>
            </div>

          <div class="dang_nhap">
            <input type="submit" name="submit" value="Gửi yêu cầu" id="login"/>
        </div>
          </form>
        <div class="chuyen_trang" style="text-align: center; margin-top: 15px; color: #333;">
           <p>Nhớ mật khẩu? <a href="dangnhap.php<?php echo $redirect_url_href; ?>" style="color: #007bff; text-decoration: none; font-weight: 600;">Đăng nhập ngay</a></p>
        </div>

        </article>
</main>
<?php 
    $additional_footer_scripts = <<<HTML
        <script src="../js/vi.js"></script>
    HTML;
    require_once 'footer.php'; 
?>