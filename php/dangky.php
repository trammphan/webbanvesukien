<?php

$redirect_url_hidden = ''; 
$redirect_url_href = ''; 

if (isset($_GET['redirect'])) {
    $redirect_url_hidden = htmlspecialchars($_GET['redirect']);
    $redirect_url_href = '?redirect=' . urlencode($_GET['redirect']);
}

$additional_css = ['webstyle.css'];


$page_title = 'Đăng ký';
$additional_head = <<<HTML
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
HTML;
?>
<?php require_once 'header.php'; ?>
<main>
        <article class="khungdungchung">
        <h2>Đăng Ký</h2>
        <form action="luuthongtin.php" method="post" id="form_dk" >


            <input type="hidden" name="redirect" value="<?php echo $redirect_url_hidden; ?>">

              <div class="thongtin">
              <label for="user_name">
                <i class="fa-solid fa-book-open-reader"></i>
                <input
                  type="text"
                  id="user_name"
                  placeholder="Vui lòng nhập họ và tên"
                  name="user_name"
                />
              </label>
            </div>

            <div class="thongtin">
              <label for="tel">
                <i class="fa-solid fa-square-phone"></i>
                <input
                  type="tel"
                  name="tel"
                  id="tel"
                  placeholder="Vui lòng nhập số điện thoại"
                />
              </label>
            </div>

            <div class="thongtin">
              <label for="email">
                <i class="fa-solid fa-envelope"></i>
                <input
                  type="email"
                  id="email"
                  name="email"
                  placeholder="Vui lòng nhập email đăng nhập"
                />
              </label>
            </div>

            <div class="thongtin">
              <label for="password">
                <i class="fa-solid fa-lock"></i>
                <input
                  type="password"
                  name="password"
                  id="password"
                  placeholder="Vui lòng nhập mật khẩu"
                />
              </label>
            </div>

            </div>
        <div id="dang_ky">
            <input type="submit" name="submit" value="Đăng Ký" id="dk_submit"/>
        </div>
        </form>
         <div class="chuyen_trang" style="text-align: center; margin-top: 15px; color: #333;">
            <p>Đã có tài khoản? <a href="dangnhap.php<?php echo $redirect_url_href; ?>" style="color: #007bff; text-decoration: none; font-weight: 600;">Đăng nhập ngay</a></p>
         </div>
       </article>     
</main>
<?php 
    $additional_footer_scripts = <<<HTML
        <script src="../js/vi.js"></script>
    HTML;
    require_once 'footer.php'; 
?>