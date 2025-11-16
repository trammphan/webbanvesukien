           
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

$events_upcoming = [];
$events_past = [];

$conn_list = new mysqli($servername, $username, $password, $dbname);
if (!$conn_list->connect_error) {
    $sql_upcoming = "SELECT s.*, d.TenTinh FROM sukien s LEFT JOIN diadiem d ON s.MaDD = d.MaDD WHERE s.Tgian >= NOW() ORDER BY s.Tgian ASC";
    if ($result_upcoming = $conn_list->query($sql_upcoming)) {
        while ($row = $result_upcoming->fetch_assoc()) {
            $events_upcoming[] = $row;
        }
        $result_upcoming->free();
    }

    $sql_past = "SELECT s.*, d.TenTinh FROM sukien s LEFT JOIN diadiem d ON s.MaDD = d.MaDD WHERE s.Tgian < NOW() ORDER BY s.Tgian DESC";
    if ($result_past = $conn_list->query($sql_past)) {
        while ($row = $result_past->fetch_assoc()) {
            $events_past[] = $row;
        }
        $result_past->free();
    }

    $conn_list->close();
}

$view = $_GET['view'] ?? null;
$selected_mask = $_GET['mask'] ?? null;

$revenue_total = 0;
$revenue_rows = [];
$orders = [];
$stats_event_name = '';

if ($view && $selected_mask) {
    $conn_stats = new mysqli($servername, $username, $password, $dbname);
    if (!$conn_stats->connect_error) {
        // Doanh thu theo loại vé
        $sql_rev = "SELECT s.TenSK, lv.TenLoai, COUNT(v.MaVe) AS so_luong, SUM(lv.Gia) AS doanh_thu
                    FROM sukien s
                    JOIN loaive lv ON lv.MaSK = s.MaSK
                    JOIN ve v ON v.MaLoai = lv.MaLoai
                    JOIN thanhtoan tt ON tt.MaTT = v.MaTT
                    WHERE s.MaSK = ?
                      AND tt.TrangThai = 'Đã bán'
                    GROUP BY s.MaSK, s.TenSK, lv.MaLoai, lv.TenLoai";
        if ($stmt_rev = $conn_stats->prepare($sql_rev)) {
            $stmt_rev->bind_param('s', $selected_mask);
            $stmt_rev->execute();
            $result_rev = $stmt_rev->get_result();
            while ($row = $result_rev->fetch_assoc()) {
                if ($stats_event_name === '' && !empty($row['TenSK'])) {
                    $stats_event_name = $row['TenSK'];
                }
                $revenue_rows[] = $row;
                $revenue_total += (float)($row['doanh_thu'] ?? 0);
            }
            $stmt_rev->close();
        }

        // Đơn hàng / vé đã bán
        $sql_orders = "SELECT s.TenSK, v.MaVe, lv.TenLoai, lv.Gia, tt.MaTT, tt.TenNguoiThanhToan,
                              tt.SDT, tt.Email_KH, tt.NgayTao, tt.TrangThai
                        FROM sukien s
                        JOIN loaive lv ON lv.MaSK = s.MaSK
                        JOIN ve v ON v.MaLoai = lv.MaLoai
                        JOIN thanhtoan tt ON tt.MaTT = v.MaTT
                        WHERE s.MaSK = ?
                          AND tt.TrangThai = 'Đã bán'";
        if ($stmt_orders = $conn_stats->prepare($sql_orders)) {
            $stmt_orders->bind_param('s', $selected_mask);
            $stmt_orders->execute();
            $result_orders = $stmt_orders->get_result();
            while ($row = $result_orders->fetch_assoc()) {
                if ($stats_event_name === '' && !empty($row['TenSK'])) {
                    $stats_event_name = $row['TenSK'];
                }
                $orders[] = $row;
            }
            $stmt_orders->close();
        }

        $conn_stats->close();
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
        <button class="nav-item" id="btn-qly">
            <i class="fa-solid fa-list-check"></i>
            <span>Quản lý sự kiện</span>
        </button>

        <button class="nav-item" id="btn-xembc">
            <i class="fa-solid fa-chart-line"></i>
            <span>Xem báo cáo</span>
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
<div class="revenue-panel <?= ($view === 'revenue' && $selected_mask) ? '' : 'hidden' ?>" id="revenue-panel">
  <div class="revenue-header">
    <h3 class="revenue-title">Doanh thu sự kiện</h3>
    <button type="button" class="revenue-close" id="revenue-close">&times;</button>
  </div>

  <p class="revenue-event" id="revenue-event-name">
    <?= htmlspecialchars($stats_event_name ?: 'Chọn một sự kiện để xem doanh thu') ?>
  </p>

  <!-- Dòng 1: tổng doanh thu -->
  <div class="revenue-summary">
    <span class="label">Tổng doanh thu:</span>
    <span class="value" id="revenue-total">
      <?= number_format($revenue_total, 0, ',', '.') ?>đ
    </span>
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
      <?php if (!empty($revenue_rows)): ?>
        <?php foreach ($revenue_rows as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['TenLoai'] ?? '') ?></td>
            <td><?= (int)($row['so_luong'] ?? 0) ?></td>
            <td><?= number_format((float)($row['doanh_thu'] ?? 0), 0, ',', '.') ?>đ</td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="3">Chưa có dữ liệu doanh thu cho sự kiện này.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Panel Đơn hàng: danh sách khách hàng -->
<div class="orders-panel <?= ($view === 'orders' && $selected_mask) ? '' : 'hidden' ?>" id="orders-panel">
  <div class="orders-header">
    <h3 class="orders-title">Đơn hàng sự kiện</h3>
    <button type="button" class="orders-close" id="orders-close">&times;</button>
  </div>
  <p class="orders-event" id="orders-event-name">
    <?= htmlspecialchars($stats_event_name ?: 'Chọn một sự kiện để xem đơn hàng') ?>
  </p>

  <p class="orders-count">
    <?php if (!empty($orders)): ?>
      Có <?= count($orders) ?> đơn hàng
    <?php else: ?>
      Chưa có đơn hàng nào cho sự kiện này.
    <?php endif; ?>
  </p>

  <div class="orders-list" id="orders-list">
    <?php if (!empty($orders)): ?>
      <?php foreach ($orders as $order): ?>
        <div class="order-card">
          <div class="order-main">
            <div class="order-name">
              <?= htmlspecialchars($order['TenNguoiThanhToan'] ?? 'Khách hàng') ?>
            </div>
            <div class="order-code">Mã đơn hàng: <?= htmlspecialchars($order['MaTT'] ?? 'N/A') ?></div>
            <div class="order-row">
              <span>Email: <?= htmlspecialchars($order['Email_KH'] ?? 'Không có') ?></span>
              <span>Số điện thoại: <?= htmlspecialchars($order['SDT'] ?? 'Không có') ?></span>
            </div>
            <div class="order-row">
              <span>Loại vé: <?= htmlspecialchars($order['TenLoai'] ?? '') ?></span>
              <span>Giá: <?= number_format((float)($order['Gia'] ?? 0), 0, ',', '.') ?>đ</span>
            </div>
            <div class="order-row">
              <span>Thời gian: <?= htmlspecialchars($order['NgayTao'] ?? '') ?></span>
              <span>Trạng thái: <?= htmlspecialchars($order['TrangThai'] ?? '') ?></span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Không có đơn hàng để hiển thị.</p>
    <?php endif; ?>
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
                        <?php if (!empty($events_upcoming)): ?>
                            <?php foreach ($events_upcoming as $event): ?>
                                <?php
                                    $dt = null;
                                    $time_str = '';
                                    if (!empty($event['Tgian'])) {
                                        try {
                                            $dt = new DateTime($event['Tgian']);
                                            $time_str = $dt->format('d/m/Y H:i');
                                        } catch (Exception $e) {
                                            $time_str = $event['Tgian'];
                                        }
                                    }
                                    $location = $event['TenTinh'] ?? '';
                                ?>
                                <div class="qly-card">
                                    <div class="qly-card-thumb">
                                        <img src="<?= htmlspecialchars($event['img_sukien'] ?? '') ?>" alt="<?= htmlspecialchars($event['TenSK'] ?? '') ?>" />
                                    </div>
                                    <div class="qly-card-body">
                                        <div class="qly-card-title"><?= htmlspecialchars($event['TenSK'] ?? '') ?></div>
                                        <div class="qly-card-meta"><?= htmlspecialchars($time_str) ?><?= $location ? ' • ' . htmlspecialchars($location) : '' ?></div>
                                        <div class="qly-card-actions">
                                            <button type="button"
                                                    class="btn-qly btn-manage">
                                                Quản lý
                                            </button>
                                            <div class="manage-menu hidden">
                                                <button type="button"
                                                        class="btn-qly btn-revenue"
                                                        data-event="<?= htmlspecialchars($event['TenSK'] ?? '') ?>">
                                                    Doanh thu
                                                </button>
                                                <button type="button" class="btn-qly btn-scan">Đơn hàng</button>
                                            </div>
                                            <a href="chitietsk_1.php?MaSK=<?= urlencode($event['MaSK'] ?? '') ?>"
                                               class="btn-qly btn-update">
                                                Chỉnh sửa
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Hiện chưa có sự kiện sắp tới.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="qly-list qly-list-daqua hidden">
                    <div class="qly-events-list">
                        <?php if (!empty($events_past)): ?>
                            <?php foreach ($events_past as $event): ?>
                                <?php
                                    $dt = null;
                                    $time_str = '';
                                    if (!empty($event['Tgian'])) {
                                        try {
                                            $dt = new DateTime($event['Tgian']);
                                            $time_str = $dt->format('d/m/Y H:i');
                                        } catch (Exception $e) {
                                            $time_str = $event['Tgian'];
                                        }
                                    }
                                    $location = $event['TenTinh'] ?? '';
                                ?>
                                <div class="qly-card">
                                    <div class="qly-card-thumb">
                                        <img src="<?= htmlspecialchars($event['img_sukien'] ?? '') ?>" alt="<?= htmlspecialchars($event['TenSK'] ?? '') ?>" />
                                    </div>
                                    <div class="qly-card-body">
                                        <div class="qly-card-title"><?= htmlspecialchars($event['TenSK'] ?? '') ?></div>
                                        <div class="qly-card-meta"><?= htmlspecialchars($time_str) ?><?= $location ? ' • ' . htmlspecialchars($location) : '' ?></div>
                                        <div class="qly-card-actions">
                                            <button type="button" class="btn-qly btn-manage">
                                                Quản lý
                                            </button>
                                            <div class="manage-menu hidden">
                                                <button type="button" class="btn-qly btn-revenue" data-event="<?= htmlspecialchars($event['TenSK'] ?? '') ?>">
                                                    Doanh thu
                                                </button>
                                                <button type="button" class="btn-qly btn-scan">Đơn hàng</button>
                                            </div>
                                            <a href="chitietsk_1.php?MaSK=<?= urlencode($event['MaSK'] ?? '') ?>"
                                               class="btn-qly btn-update">
                                                Xem
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Hiện chưa có sự kiện đã qua.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="qly-list qly-list-khac hidden">
                    <p><i class="fa-solid fa-spinner"></i> Đang cập nhật thêm dữ liệu...</p>
                </div>
            </div>
        </article>

        <article class="noidung hidden" id="xembc-section">
            <h2 class="noidung-title">XEM BÁO CÁO</h2>
            <div class="report-table-wrapper">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>File</th>
                            <th>Ngày tạo</th>
                            <th>Người tạo</th>
                            <th>Trạng thái xử lý</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>baocao_doanhthu_gdragon_2025.xlsx</td>
                            <td>2025-11-09 10:15</td>
                            <td>ntc@ctu.edu.vn</td>
                            <td>Đã xử lý</td>
                            <td>
                                <button class="btn-qly btn-download-report">Tải xuống</button>
                            </td>
                        </tr>
                        <tr>
                            <td>baocao_khachhang_waterbomb.csv</td>
                            <td>2025-11-17 09:00</td>
                            <td>report@vibe4.vn</td>
                            <td>Đã xử lý</td>
                            <td>
                                <button class="btn-qly btn-download-report">Tải xuống</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal thông báo tải xuống thành công -->
            <div class="modal fade" id="downloadSuccessModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="background:#ffffff; color:#000000;">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color:#000000;">Thông báo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="color:#000000;">
                            Tải xuống thành công.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
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
    taosk: document.getElementById("btn-taosk"), // có thể null vì đã bỏ nút bên trái
    qly: document.getElementById("btn-qly"),
    xembc: document.getElementById("btn-xembc")
  };

  const sections = {
    nhatochuc: document.querySelector(".nhatochuc"),
    taosk: document.getElementById("taosk-section"),
    qly: document.getElementById("qly-section"),
    xembc: document.getElementById("xembc-section")
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

    // Làm nổi bật nút đang chọn (nếu tồn tại nút trong sidebar)
    Object.values(btns).forEach(btn => btn && btn.classList.remove("active"));
    if (btns[name]) {
      btns[name].classList.add("active");
    }
  }

  // Gán sự kiện click cho mỗi nút
  Object.keys(btns).forEach(name => {
    const btn = btns[name];
    if (!btn) return;
    btn.addEventListener("click", () => showSection(name));
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

  // Xử lý nút Quản lý: toggle menu con (Doanh thu / Đơn hàng)
  document.querySelectorAll('.qly-card .btn-manage').forEach(btn => {
    btn.addEventListener('click', () => {
      const card = btn.closest('.qly-card');
      const menu = card?.querySelector('.manage-menu');
      if (!menu) return;

      menu.classList.toggle('hidden');
    });
  });

  // Xử lý nút Doanh thu / Đơn hàng mở/đóng panel thống kê
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

  // Thông báo tải xuống thành công cho phần Xem báo cáo
  const downloadButtons = document.querySelectorAll('.btn-download-report');
  const downloadModalElement = document.getElementById('downloadSuccessModal');
  if (downloadButtons.length && downloadModalElement && window.bootstrap) {
    const downloadModal = new bootstrap.Modal(downloadModalElement);
    downloadButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        downloadModal.show();
      });
    });
  }

  // Tìm kiếm trong phần QUẢN LÝ SỰ KIỆN: lọc danh sách sự kiện bằng JS tại chỗ
  const qlySearchForm = document.querySelector('#qly-section .searchbar');
  if (qlySearchForm) {
    const qlyInput  = qlySearchForm.querySelector('input[name="q"]');
    const qlyButton = qlySearchForm.querySelector('.btn-search');
    const qlyCards  = document.querySelectorAll('#qly-section .qly-events-list .qly-card');

    const applyQlyFilter = () => {
      if (!qlyInput) return;
      const q = qlyInput.value.trim().toLowerCase();

      qlyCards.forEach(card => {
        const titleEl = card.querySelector('.qly-card-title');
        const title = (titleEl?.textContent || '').toLowerCase();
        if (!q || title.includes(q)) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    };

    qlySearchForm.addEventListener('submit', (e) => {
      e.preventDefault();
      applyQlyFilter();
    });

    if (qlyInput) {
      qlyInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
          e.preventDefault();
          applyQlyFilter();
        }
      });
    }

    if (qlyButton) {
      qlyButton.addEventListener('click', (e) => {
        e.preventDefault();
        applyQlyFilter();
      });
    }
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