           
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
$additional_css = ['webstyle.css', 'nhatochuc.css'];
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
                <a href="#" class="btn-create" id="btn-create-qly">
                    <i class="fa-solid fa-calendar-plus"></i>
                    <span>Tạo sự kiện</span>
                </a>
            </div>
<div class="revenue-panel hidden" id="revenue-panel">
  <div class="revenue-header">
    <h3 class="revenue-title">Doanh thu sự kiện</h3>
    <button type="button" class="revenue-close" id="revenue-close">&times;</button>
  </div>

  <p class="revenue-event" id="revenue-event-name">Tên sự kiện</p>

  <!-- Dòng 1: tổng doanh thu -->
  <div class="revenue-summary">
    <span class="label">Tổng doanh thu:</span>
    <span class="value" id="revenue-total">500.000.000đ</span>
  </div>

  <!-- Dòng 2: thống kê từng loại vé -->
  <h4 class="revenue-subtitle">Thống kê theo loại vé</h4>
  <table class="revenue-table">
    <thead>
      <tr>
        <th>Loại vé</th>
        <th>Số lượng bán</th>
        <th>Doanh thu</th>
      </tr>
    </thead>
    <tbody id="revenue-ticket-rows">
      <tr>
        <td>Vé VIP</td>
        <td>200</td>
        <td>300.000.000đ</td>
      </tr>
      <tr>
        <td>Vé Thường</td>
        <td>500</td>
        <td>200.000.000đ</td>
      </tr>
    </tbody>
  </table>
</div>

<!-- Panel Đơn hàng: danh sách khách hàng -->
<div class="orders-panel hidden" id="orders-panel">
  <div class="orders-header">
    <h3 class="orders-title">Đơn hàng sự kiện</h3>
    <button type="button" class="orders-close" id="orders-close">&times;</button>
  </div>
  <p class="orders-event" id="orders-event-name">Tên sự kiện</p>

  <p class="orders-count">Có 1.234 đơn hàng</p>

  <div class="orders-list" id="orders-list">
    <div class="order-card">
      <div class="order-main">
        <div class="order-name">Hoàng Trung</div>
        <div class="order-code">Mã đơn hàng: 123454</div>
        <div class="order-row">
          <span>Email: h***05@gmail.com</span>
          <span>Số điện thoại: +84353***166</span>
        </div>
      </div>
    </div>

    <div class="order-card">
      <div class="order-main">
        <div class="order-name">Phong Nguyen</div>
        <div class="order-code">Mã đơn hàng: 567890</div>
        <div class="order-row">
          <span>Email: p***09@gmail.com</span>
          <span>Số điện thoại: +84888***999</span>
        </div>
      </div>
    </div>
  </div>
</div>
            <div class="tabs">
                <button type="button" class="tab active" data-status="saptoi">Sắp tới</button>
                <button type="button" class="tab" data-status="daqua">Đã qua</button>
                <!-- <button type="button" class="tab" data-status="choduyet">Chờ duyệt</button>
                <button type="button" class="tab" data-status="nhap">Nháp</button> -->
            </div>

            <div class="qly-events">
                <div class="qly-list qly-list-saptoi">
                    <!-- <h3 class="qly-subtitle">Sự kiện sắp tới</h3> -->
                    <div class="qly-events-list">
                        <div class="qly-card">
                            <div class="qly-card-thumb">
                                <img src="https://salt.tkbcdn.com/ts/ds/2b/62/6d/b72040ac36d256c6c51e4c01797cf879.png" alt="G-DRAGON" />
                            </div>
                            <div class="qly-card-body">
                                <div class="qly-card-title">G-DRAGON 2025 WORLD TOUR [Übermensch] IN HANOI</div>
                                <div class="qly-card-meta">08/11/2025 • Hà Nội</div>
                                <div class="qly-card-actions">
                                    <button type="button"
                                            class="btn-qly btn-revenue"
                                            data-event="G-DRAGON 2025 WORLD TOUR [Übermensch] IN HANOI">
                                        Doanh thu
                                    </button>
                                    <button type="button" class="btn-scan">Đơn hàng</button>
                                </div>
                            </div>
                        </div>

                        <div class="qly-card">
                            <div class="qly-card-thumb">
                                <img src="https://salt.tkbcdn.com/ts/ds/f3/80/f0/32ee189d7a435daf92b6a138d925381c.png" alt="Waterbomb" />
                            </div>
                            <div class="qly-card-body">
                                <div class="qly-card-title">Waterbomb Ho Chi Minh City 2025</div>
                                <div class="qly-card-meta">15–16/11/2025 • TP.HCM</div>
                                <div class="qly-card-actions">
                                    <button type="button" class="btn-qly btn-revenue" data-event="Waterbomb Ho Chi Minh City 2025">
                                        Doanh thu
                                    </button>
                                    <button type="button" class="btn-scan">Đơn hàng</button>
                                    <!-- <button type="button" class="btn-qly">Vé</button>
                                    <button type="button" class="btn-scan">Nội dung</button> -->
                                </div>
                            </div>
                        </div>

                        <div class="qly-card">
                            <div class="qly-card-thumb">
                                <img src="https://salt.tkbcdn.com/ts/ds/6e/2f/fa/32d07d9e0b2bd6ff7de8dfe2995619d5.jpg" alt="GS25" />
                            </div>
                            <div class="qly-card-body">
                                <div class="qly-card-title">GS25 MUSIC FESTIVAL 2025</div>
                                <div class="qly-card-meta">22/11/2025 • TP.HCM</div>
                                <div class="qly-card-actions">
                                    <button type="button" class="btn-qly btn-revenue" data-event="GS25 MUSIC FESTIVAL 2025">
                                        Doanh thu
                                    </button>
                                    <button type="button" class="btn-scan">Đơn hàng</button>
                                    <!-- <button type="button" class="btn-qly">Vé</button>
                                    <button type="button" class="btn-scan">Quét vé</button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="qly-list qly-list-daqua hidden">
                    <!-- <h3 class="qly-subtitle">Sự kiện đã qua</h3> -->
                    <div class="qly-events-list">
                        <div class="qly-card">
                            <div class="qly-card-thumb">
                                <img src="https://salt.tkbcdn.com/ts/ds/ee/86/df/261a5fd2fa0890c25f4c737103bbbe0c.png" alt="Lululola" />
                            </div>
                            <div class="qly-card-body">
                                <div class="qly-card-title">LULULOLA SHOW VICKY NHUNG &amp; CHU THÚY QUỲNH | NGÀY MƯA ẤY</div>
                                <div class="qly-card-meta">20/09/2025 • Đà Lạt</div>
                                <div class="qly-card-actions">
                                    <button type="button" class="btn-qly btn-revenue" data-event="LULULOLA SHOW VICKY NHUNG &amp; CHU THÚY QUỲNH | NGÀY MƯA ẤY">
                                        Doanh thu
                                    </button>
                                    <button type="button" class="btn-scan">Đơn hàng</button>
                                    <!-- <button type="button" class="btn-qly">Quản lý</button>
                                    <button type="button" class="btn-scan">Quét vé</button> -->
                                </div>
                            </div>
                        </div>

                        <div class="qly-card">
                            <div class="qly-card-thumb">
                                <img src="https://salt.tkbcdn.com/ts/ds/e3/06/ed/faff7ef36d95334510e51f7d337357d4.jpg" alt="Stephan Bodzin" />
                            </div>
                            <div class="qly-card-body">
                                <div class="qly-card-title">ELAN &amp; APLUS present: STEPHAN BODZIN</div>
                                <div class="qly-card-meta">21/09/2025 • Hà Nội</div>
                                <div class="qly-card-actions">
                                    <button type="button" class="btn-qly btn-revenue" data-event="ELAN &amp; APLUS present: STEPHAN BODZIN">
                                        Doanh thu
                                    </button>
                                    <button type="button" class="btn-scan">Đơn hàng</button>
                                    <!-- <button type="button" class="btn-qly">Quản lý</button>
                                    <button type="button" class="btn-scan">Quét vé</button> -->
                                </div>
                            </div>
                        </div>

                        <div class="qly-card">
                            <div class="qly-card-thumb">
                                <img src="https://salt.tkbcdn.com/ts/ds/90/37/6e/cfa9510b1f648451290e0cf57b6fd548.jpg" alt="Em Xinh" />
                            </div>
                            <div class="qly-card-body">
                                <div class="qly-card-title">EM XINH &quot;SAY HI&quot; CONCERT - ĐÊM 2</div>
                                <div class="qly-card-meta">11/10/2025 • Hà Nội</div>
                                <div class="qly-card-actions">
                                    <button type="button" class="btn-qly btn-revenue" data-event="EM XINH &quot;SAY HI&quot; CONCERT - ĐÊM 2">
                                        Doanh thu
                                    </button>
                                    <button type="button" class="btn-scan">Đơn hàng</button>
                                    <!-- <button type="button" class="btn-qly">Quản lý</button>
                                    <button type="button" class="btn-scan">Quét vé</button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="qly-list qly-list-khac hidden">
                    <p><i class="fa-solid fa-spinner"></i> Đang cập nhật thêm dữ liệu...</p>
                </div>
            </div>
        </article>

        <article class="noidung hidden" id="xembc-section">
            <h2 class="noidung-title">XEM BÁO CÁO</h2>
            <!-- <div class="header">
                <div class="actions">
                    <form class="searchbar" method="get" action="admin.php">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="q" placeholder="Báo cáo thống kê doanh thu, lượt bán vé,..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
                        <button class="btn-search" type="submit">Tìm kiếm</button>
                    </form>
                </div>
            </div> -->
            <i class="fa-solid fa-spinner"></i> Đang cập nhật...
             <div class="report-table-wrapper">
                <table class="report-table">
                <thead>
                <tr>
                <th style="width: 40px;"><input type="checkbox" /></th>
                <th>File</th>
                <th>Ngày tạo</th>
                <th>Người tạo</th>
                <th>Trạng thái xử lý</th>
                <th>Thao tác</th>
                </tr>
                </thead>
               <tbody>
    <tr>
        <td><input type="checkbox" /></td>
        <td>baocao_doanhthu_gdragon_2025.xlsx</td>
        <td>2025-11-09 10:15</td>
        <td>ntc@ctu.edu.vn</td>
        <td>Đã xử lý</td>
        <td>
            <button class="btn-qly">Tải xuống</button>
        </td>
    </tr>
    <tr>
        <td><input type="checkbox" /></td>
        <td>baocao_khachhang_waterbomb.csv</td>
        <td>2025-11-17 09:00</td>
        <td>report@vibe4.vn</td>
        <td>Đã xử lý</td>
        <td>
            <button class="btn-qly">Tải xuống</button>
        </td>
    </tr>
    <tr>
        <td><input type="checkbox" /></td>
        <td>baocao_ve_gs25_theoloai.pdf</td>
        <td>2025-11-23 18:30</td>
        <td>ntc@ctu.edu.vn</td>
        <td>Đang xử lý</td>
        <td>
            <button class="btn-qly">Xem</button>
        </td>
    </tr>
</tbody>
                
                </table>
                
                </div>
                
        </article>

        <article class="noidung hidden" id="capnhat-section">
            <h2 class="noidung-title">CẬP NHẬT ĐIỀU KHOẢN</h2>
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

  // Nút tạo sự kiện trong phần QUẢN LÝ SỰ KIỆN
  const btnCreateQly = document.getElementById("btn-create-qly");
  if (btnCreateQly) {
    btnCreateQly.addEventListener("click", (e) => {
      e.preventDefault();
      showSection("taosk");
    });
  }

  const qlyTabs = document.querySelectorAll('#qly-section .tabs .tab');
  const qlyLists = {
    saptoi: document.querySelector('.qly-list-saptoi'),
    daqua: document.querySelector('.qly-list-daqua'),
    choduyet: document.querySelector('.qly-list-khac'),
    nhap: document.querySelector('.qly-list-khac')
  };

  qlyTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      qlyTabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');

      const status = tab.dataset.status;
      Object.values(qlyLists).forEach(list => list && list.classList.add('hidden'));
      if (qlyLists[status]) {
        qlyLists[status].classList.remove('hidden');
      }
    });
  });

  // Xử lý nút Doanh thu mở/đóng panel
  const revenuePanel = document.getElementById('revenue-panel');
  const revenueClose = document.getElementById('revenue-close');
  const revenueEventName = document.getElementById('revenue-event-name');

  const ordersPanel = document.getElementById('orders-panel');
  const ordersClose = document.getElementById('orders-close');
  const ordersEventName = document.getElementById('orders-event-name');

  document.querySelectorAll('.btn-revenue').forEach(btn => {
    btn.addEventListener('click', () => {
      const name = btn.dataset.event || 'Sự kiện';
      if (revenueEventName) revenueEventName.textContent = name;

      // Chèn panel Doanh thu ngay dưới sự kiện được chọn
      const card = btn.closest('.qly-card');
      if (card && revenuePanel) {
        card.insertAdjacentElement('afterend', revenuePanel);
        revenuePanel.classList.remove('hidden');
      }

      // Ẩn panel Đơn hàng nếu đang mở
      if (ordersPanel) ordersPanel.classList.add('hidden');
    });
  });

  document.querySelectorAll('.btn-scan').forEach(btn => {
    btn.addEventListener('click', () => {
      const card = btn.closest('.qly-card');
      const name = card?.querySelector('.qly-card-title')?.textContent?.trim() || 'Sự kiện';
      if (ordersEventName) ordersEventName.textContent = name;

      // Chèn panel Đơn hàng ngay dưới sự kiện được chọn
      if (card && ordersPanel) {
        card.insertAdjacentElement('afterend', ordersPanel);
        ordersPanel.classList.remove('hidden');
      }

      // Ẩn panel Doanh thu nếu đang mở
      if (revenuePanel) revenuePanel.classList.add('hidden');
    });
  });

  if (revenueClose && revenuePanel) {
    revenueClose.addEventListener('click', () => {
      revenuePanel.classList.add('hidden');
    });
  }

  if (ordersClose && ordersPanel) {
    ordersClose.addEventListener('click', () => {
      ordersPanel.classList.add('hidden');
    });
  }
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