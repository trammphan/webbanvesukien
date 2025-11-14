           
    <?php
// Cấu hình CSDL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlysukien";

// Khởi tạo các biến trạng thái
$is_logged_in = false;
$user_info = null;

// 1. KIỂM TRA COOKIE ĐĂNG NHẬP
if (isset($_COOKIE['email'])) {
    $user_email = $_COOKIE['email'];
    $is_logged_in = true;

    // Kết nối CSDL
    $conn = new mysqli($servername, $username, $password, $dbname);

    if (!$conn->connect_error) {
        $sql = "SELECT user_name, email FROM nhatochuc WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user_info = $result->fetch_assoc();
        } else {
            setcookie("email", "", time() - 3600, "/");
            setcookie("user_name", "", time() - 3600, "/");
            $is_logged_in = false;
        }

        $stmt->close();
        $conn->close();
    } else {
        $is_logged_in = false;
    }
}

$page_title = 'Nhà tổ chức';
$additional_css = ['webstyle.css'];
$additional_head = <<<HTML
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
HTML;
require_once 'header.php';
?>   
  <main class="layout">
    <article class= "sidebar">
        <div class="back">
            <a href="#" onclick="history.back(); return false;">
              <i class="fa-regular fa-circle-left" id="x"></i> 
            </a>
        </div>
        <div class="brand">
                <span class="brand-dot"></span>
                <span class="brand-text">Nhà tổ chức</span>
        </div>
        <nav class="nav">
        <button class="nav-item" id="btn-taosk">
            <i class="fa-solid fa-calendar-plus"></i>
            <span>Tạo sự kiện mới</span>
        </button>
        <button class="nav-item" id="btn-qly">
            <i class="fa-solid fa-list-check"></i>
            <span>Quản lý sự kiện</span>
        </button>
        <button class="nav-item" id="btn-xembc">
            <i class="fa-solid fa-chart-line"></i>
            <span>Xem báo cáo</span>
        </button>
        <button class="nav-item" id="btn-capnhat">
            <i class="fa-solid fa-user-pen"></i>
            <span>Cập Nhật Thông Tin</span>
        </button>
          <?php if ($is_logged_in && $user_info): ?>
            <label class="email_ntc">
              <i class="fa-solid fa-envelope"></i>
              <span>Email: <b><?= htmlspecialchars($user_info['email']) ?></b></span>
            </label>
            <div class=" box_3 ">
              <a href="dangxuat.php" class="w3-bar-item w3-button w3-padding" id="logout"  data-bs-toggle="tooltip" title="Đăng xuất">
                            <i class="fa-solid fa-right-from-bracket" ></i> 
                        </a>
            </div>
            
          <?php endif; ?>

      </article>

      <article class="noidung nhatochuc">
            <h2 class="noidung-title">Chào mừng Nhà tổ chức đến với Vibe4</h2>
            <p class="chaomung">
            Vibe4 là nền tảng giúp bạn tạo, quản lý và bán vé sự kiện một cách dễ dàng.
            Từ hội thảo, hòa nhạc đến các buổi gặp gỡ, chúng tôi mang đến công cụ mạnh mẽ
            để bạn kết nối khán giả, tăng doanh thu và lan tỏa thương hiệu của mình.</p>
        </article>

        <article class="noidung hidden" id="taosk-section">
            <h2 class="noidung-title">TẠO SỰ KIỆN MỚI</h2>
            <i class="fa-solid fa-spinner"></i> Đang cập nhật...
        </article>

        <article class="noidung hidden" id="qly-section">
            <h2 class="noidung-title">QUẢN LÝ SỰ KIỆN</h2>
            <div class="header">
                <div class="actions">
                    <form class="searchbar" method="get" action="admin.php">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="q" placeholder="Danh sách các sự kiện bạn đã tạo." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
                        <button class="btn-search" type="submit">Tìm kiếm</button>
                    </form>
                </div>
            </div>
            <i class="fa-solid fa-spinner"></i> Đang cập nhật...
        </article>

        <article class="noidung hidden" id="xembc-section">
            <h2 class="noidung-title">XEM BÁO CÁO</h2>
            <div class="header">
                <div class="actions">
                    <form class="searchbar" method="get" action="admin.php">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="q" placeholder="Báo cáo thống kê doanh thu, lượt bán vé,..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
                        <button class="btn-search" type="submit">Tìm kiếm</button>
                    </form>
                </div>
            </div>
            <i class="fa-solid fa-spinner"></i> Đang cập nhật...
        </article>

        <article class="noidung hidden" id="capnhat-section">
            <h2 class="noidung-title">CẬP NHẬT THÔNG TIN NHÀ TỔ CHỨC</h2>
            <p>Trang cập nhật hồ sơ và thông tin liên hệ.</p>
            <i class="fa-solid fa-spinner"></i> Đang cập nhật...
        </article>
    </main>
<?php 
  $additional_footer_scripts = <<<HTML
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  HTML;
  require_once 'footer.php';
?>
  <script>
document.addEventListener("DOMContentLoaded", () => {
  const btns = {
    taosk: document.getElementById("btn-taosk"),
    qly: document.getElementById("btn-qly"),
    xembc: document.getElementById("btn-xembc"),
    capnhat: document.getElementById("btn-capnhat")
  };

  const sections = {
    nhatochuc: document.querySelector(".nhatochuc"),
    taosk: document.getElementById("taosk-section"),
    qly: document.getElementById("qly-section"),
    xembc: document.getElementById("xembc-section"),
    capnhat: document.getElementById("capnhat-section")
  };

  // Ban đầu: chỉ hiển thị phần "Chào mừng Nhà tổ chức"
  Object.values(sections).forEach(sec => sec.classList.add("hidden"));
  sections.nhatochuc.classList.remove("hidden");

  // Hàm chuyển section
  function showSection(name) {
    // Ẩn toàn bộ section
    Object.values(sections).forEach(sec => sec.classList.add("hidden"));
    // Hiện section tương ứng với nút bấm
    sections[name].classList.remove("hidden");

    // Làm nổi bật nút đang chọn
    Object.values(btns).forEach(btn => btn.classList.remove("active"));
    btns[name].classList.add("active");
  }

  // Gán sự kiện click cho mỗi nút
  Object.keys(btns).forEach(name => {
    btns[name].addEventListener("click", () => showSection(name));
  });
});
</script>
<script>
document.getElementById("soloaive").addEventListener("change", function() {
  const soLuong = parseInt(this.value);
  const khung = document.getElementById("khungloaive");
  khung.innerHTML = ""; // Xóa nội dung cũ

  if (soLuong > 0) {
    for (let i = 1; i <= soLuong; i++) {
      const div = document.createElement("div");
      div.className = "ve-item";
      div.innerHTML = `
        <input type="text" placeholder="Tên loại vé ${i}">
        <input type="number" min="0" placeholder="Số lượng vé ${i}">
      `;
      khung.appendChild(div);
    }
  }
});
</script>
<script scr="vi.js">

</body>
</html>


<!-- Footer Section-->