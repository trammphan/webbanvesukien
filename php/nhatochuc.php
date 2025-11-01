           
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
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nhà tổ chức</title>
  <link rel="icon" href="../img/fav-icon.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/webstyle.css">
</head>

<body>
  <header class="main-header">
    <div class="header-container">
      <div class="header-logo">
        <a href="index.php" style="color:white; text-decoration:none; font-size:24px; font-weight:bold;">Vibe4</a>
      </div>

      <div class="header-search">
        <form action="/search" method="get">
          <input type="text" placeholder="Tìm kiếm sự kiện, địa điểm..." name="q" class="search-input">
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
        <?php include __DIR__ . '/header_actions.php'; ?>
      </div>
    </div>
  </header>

  <main>
    <div class="container_nhatochuc">
        <article class=" noidung  nhatochuc">
          <h2>Chào mừng Nhà tổ chức đến với Vibe4</h2>
          <p class="chaomung">
            Vibe4 là nền tảng giúp bạn tạo, quản lý và bán vé sự kiện một cách dễ dàng.
            Từ hội thảo, hòa nhạc đến các buổi gặp gỡ, chúng tôi mang đến công cụ mạnh mẽ
            để bạn kết nối khán giả, tăng doanh thu và lan tỏa thương hiệu của mình.</p>
        </article>
        <article class="noidung hidden " id="taosk-section">
            <h2>Tạo Sự Kiện Mới</h2>
            <div class="khungdungchung">
                <form action="luuthongtin.php" method="post" id="form_dk" autocomplete="off">

                    <div class="form-group">
                        <label for="tensk" id="tensk">
                            <i class="fa-solid fa-book-open-reader"></i>
                            <input type="text"  id="tsk"
                            placeholder="Vui lòng nhập tên sự kiện" name="tensk"/>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="loaisk" id="loaisk">
                            <i class="fa-solid fa-venus-mars"></i>
                            <select name="chonsk" id="chonsk">
                            <option value="sample">Loại sự kiện</option>
                            <option value="Liveshow">Liveshow</option>
                            <option value="Festival">Festival</option>
                            <option value="Concert">Concert</option>
                            </select>
                        </label>
                    </div>
                    <div class="form-group">
                      <label for="thoigian" id="thoigiansk">
                        <i class="fa-solid fa-calendar-days"></i>
                        <input type="date" class="date-input" placeholder="Chọn ngày">
                      </label>
                    </div>
                  <div class="form-group">
                      <label for="thoigian" id="thoigiansk">
                        <i class="fa-solid fa-clock"></i>
                        <input type="time" class="time-input" placeholder="Chọn giờ">
                      </label>
                  </div>
                    <div class="form-group">
                      <label for="diadiem" id="diadiemsk">
                        <i class="fa-solid fa-map-marker-alt"></i>
                        <input type="text" placeholder="Địa điểm tổ chức" id="ddsk">
                      </label>
                    </div>
                    <div class="form-group">
                      <label for="image_anh" id="image_anh">
                        <i class="fa-solid fa-image"></i>
                        <input type="file" accept="image/*">
                        </label>
                    </div>
                    <div class="form-group">
                      <label for="ve_loaive" id="ve_loaive">
                        <i class="fa-solid fa-list-ol"></i>
                        <input type="number" id="soloaive" min="1" placeholder="Nhập số lượng loại vé">
                      </div>
                    <div id="khungloaive"></div>
                    <div > 
                        <textarea class="mota" row="95%" placeholder="Mô tả sự kiện..." id="mtsk"></textarea>
                    </div>
                    <div id="dang_ky">
                        <input type="submit" name="submit" value="Đăng Ký" id="submit"/>
                    </div>
                </form>
          </div>

        </article>

        <article class="noidung hidden quanly" id="qly-section">
          <h2>Quản Lý Sự Kiện</h2>
          <p>Danh sách các sự kiện bạn đã tạo.</p>
          <i class="fa-solid fa-spinner"></i> Đang cập nhật...
        </article>

        <article class="noidung hidden xembaocao" id="xembc-section">
          <h2>Xem Báo Cáo</h2>
          <p>Báo cáo thống kê doanh thu, lượt bán vé,...</p>
          <i class="fa-solid fa-spinner"></i> Đang cập nhật...
        </article>

        <article class="noidung hidden capnhat" id="capnhat-section">
          <h2>Cập Nhật Thông Tin Nhà Tổ Chức</h2>
          <p>Trang cập nhật hồ sơ và thông tin liên hệ.</p>
          <i class="fa-solid fa-spinner"></i> Đang cập nhật...
        </article>


        <article class="thanh_doc nhatochuc_2">
            <p class="tieude">NHÀ TỔ CHỨC</p>
            <button class="congviec" id="btn-taosk">
                <i class="fa-solid fa-calendar-plus"></i>
                <span>Tạo Sự Kiện Mới</span>
            </button>
            <button class="congviec" id="btn-qly">
                <i class="fa-solid fa-list-check"></i>
                <span>Quản Lý Sự Kiện</span>
            </button>
            <button class="congviec" id="btn-xembc">
                <i class="fa-solid fa-chart-line"></i>
                <span>Xem Báo Cáo</span>
            </button>
            <button class="congviec" id="btn-capnhat">
                <i class="fa-solid fa-user-pen"></i>
                <span>Cập Nhật Thông Tin</span>
            </button>

          <?php if ($is_logged_in && $user_info): ?>
            <label class="email_ntc">
              <i class="fa-solid fa-envelope"></i>
              <span>Email: <b><?= htmlspecialchars($user_info['email']) ?></b></span>
            </label>
            <div class="logout box_1">
              <a href="dangxuat.php" id="logout"><i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
          <?php endif; ?>
        </article>
        
      </div>


  </main>

  <footer>
    <div class="footer-container">
      <div class="footer-col footer-branding">
        <h3 class="footer-logo">Vibe4</h3>
        <p>Nền tảng tổ chức và bán vé sự kiện hàng đầu Việt Nam.</p>
        <div class="social-links">
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
      </div>
      <div class="footer-col">
        <h4>Liên hệ</h4>
        <ul class="footer-contact">
          <li><i class="fas fa-phone-alt"></i><span>+123 456 789</span></li>
          <li><i class="fas fa-envelope"></i><span>support@vibe4.com</span></li>
          <li><i class="fas fa-map-marker-alt"></i><span>ĐH Cần Thơ</span></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>@2025 - Vibe4 • Nhóm 1 CT299 - Phát Triển Hệ Thống Web</p>
    </div>
  </footer>

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


</body>
</html>


<!-- Footer Section-->