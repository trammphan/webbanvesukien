<?php
// *** BẮT ĐẦU THAY ĐỔI ***
// Lấy URL redirect từ tham số GET, nếu có
$redirect_url_hidden = ''; // Dùng cho input hidden
$redirect_url_href = '';   // Dùng cho href links

if (isset($_GET['redirect'])) {
    // htmlspecialchars cho giá trị của input
    $redirect_url_hidden = htmlspecialchars($_GET['redirect']);
    // urlencode cho tham số trên URL
    $redirect_url_href = '?redirect=' . urlencode($_GET['redirect']);
    // *** KẾT THÚC THAY ĐỔI ***
}

// Thêm CSS riêng cho trang này (sử dụng cơ chế trong header.php)
$additional_css = ['webstyle.css'];

// Giữ tiêu đề trang cũ và assets ở <head>
$page_title = 'Đăng nhập';
$additional_head = <<<HTML
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
HTML;
?>
<?php require_once 'header.php'; ?>
<main>
        <article class="khungdungchung">
          <h2>ĐĂNG NHẬP</h2>

          <form action="log.php" method="post"  >
            <!-- Thêm trường ẩn để chứa URL redirect -->
             <input type="hidden" name="redirect" value="<?php echo $redirect_url_hidden; ?>">
            <!-- *** KẾT THÚC THAY ĐỔI *** -->

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

          <div class="dang_nhap">
            <input type="submit" name="submit" value="Đăng nhập" id="login"/>
        </div>
          </form>
 <!-- *** BẮT ĐẦU THAY ĐỔI MỚI *** -->
         <!-- Thêm link chuyển sang trang đăng ký bên dưới form -->
         <div class="chuyen_trang" style="text-align: center; margin-top: 15px; color: #333;">
            <p>Chưa có tài khoản? <a href="dangky.php<?php echo $redirect_url_href; ?>" style="color: #007bff; text-decoration: none; font-weight: 600;">Đăng ký ngay</a></p>
            <!-- *** BẮT ĐẦU BỔ SUNG QUÊN MẬT KHẨU *** -->
           <p style="margin-top: 10px;"><a href="quen_mk.php<?php echo $redirect_url_href; ?>" style="color: #007bff; text-decoration: none; font-weight: 600;">Quên mật khẩu?</a></p>
           <!-- *** KẾT THÚC BỔ SUNG QUÊN MẬT KHẨU *** -->

         </div>
         <!-- *** KẾT THÚC THAY ĐỔI MỚI *** -->

        </article>
</main>
<?php 
    $additional_footer_scripts = <<<HTML
        <script defer src="/scripts/web-layout.js"></script>
        <script defer src="/scripts/homepage.js"></script>
    HTML;
    require_once 'footer.php'; 
?>