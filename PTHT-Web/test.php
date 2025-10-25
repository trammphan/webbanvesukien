<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Organizer Center - Sự kiện của tôi</title>
  <link rel="stylesheet" href="organizer.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">
        <i class="fa-solid fa-ticket"></i> TicketBox
      </div>
      <nav>
        <ul>
          <li class="active"><i class="fa-solid fa-calendar"></i> Sự kiện của tôi</li>
          <li><i class="fa-solid fa-folder"></i> Quản lý báo cáo</li>
          <li><i class="fa-solid fa-file-contract"></i> Điều khoản cho Ban tổ chức</li>
        </ul>
      </nav>
      <div class="language">
        <span>Ngôn ngữ</span>
        <button class="lang-btn">Vie 🇻🇳</button>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <header class="top-bar">
        <input type="text" placeholder="Tìm kiếm sự kiện...">
        <button class="search-btn">Tìm kiếm</button>
        <div class="tabs">
          <button class="tab active">Sắp tới</button>
          <button class="tab">Đã qua</button>
          <button class="tab">Chờ duyệt</button>
          <button class="tab">Nháp</button>
        </div>
      </header>

      <section class="content">
        <div class="empty-state">
          <i class="fa-solid fa-box-open"></i>
          <p>Chưa có sự kiện nào</p>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
