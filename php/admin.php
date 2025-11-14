<?php
// Cấu hình CSDL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlysukien";

// Khởi tạo các biến trạng thái
$is_logged_in = false;
$user_info = null;


// Khởi tạo kết quả vé
$result_thong_ke_ve = null; 
$items_per_page = 10;
$current_page = isset($_GET['trang']) ? (int)$_GET['trang'] : 1;
if ($current_page < 1) $current_page = 1;
$total_pages = 1; // Khởi tạo tổng số trang

// Khởi tạo kết quả sự kiện
$result_sukien = null; 
$items_per_page_sukien = 10;
$current_page_sukien = isset($_GET['trang_sk']) ? (int)$_GET['trang_sk'] : 1; 
if ($current_page_sukien < 1) $current_page_sukien = 1;
$total_pages_sukien = 1;

// Khởi tạo danh mục
$result_thong_ke_loai_sk = null;
$current_page_lsk = isset($_GET['trang_lsk']) ? (int)$_GET['trang_lsk'] : 1; 
if ($current_page_lsk < 1) $current_page_lsk = 1;
$total_pages_lsk = 1; // Khởi tạo tổng số trang cho loại sự kiện

$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$search_condition = '';

// Khởi tạo kết quả khách hàng
$result_khachhang = null; 
$items_per_page_khachhang = 10;
$current_page_khachhang = isset($_GET['trang_kh']) ? (int)$_GET['trang_kh'] : 1; 
if ($current_page_khachhang < 1) $current_page_khachhang = 1;
$total_pages_khachhang = 1;
// Khởi tạo kết quả nhân viên soát vé
$result_nhanviensoatve = null; 
$items_per_page_nhanviensoatve = 10;
$current_page_nhanviensoatve = isset($_GET['trang_nv']) ? (int)$_GET['trang_nv'] : 1; 
if ($current_page_nhanviensoatve < 1) $current_page_nhanviensoatve = 1;
$total_pages_nhanviensoatve = 1;
// Khởi tạo kết quả nhà tổ chức
$result_nhatochuc = null; 
$items_per_page_nhatochuc = 10;
$current_page_nhatochuc = isset($_GET['trang_ntc']) ? (int)$_GET['trang_ntc'] : 1; 
if ($current_page_nhatochuc < 1) $current_page_nhatochuc = 1;
$total_pages_nhatochuc = 1;
$offset_nhanviensoatve = 0; 
$offset_nhatochuc = 0;



// 1. KIỂM TRA VÀ TRUY VẤN THÔNG TIN NẾU ĐÃ ĐĂNG NHẬP
if (isset($_COOKIE['email'])) {
    $user_email = $_COOKIE['email'];
    $is_logged_in = true;

    // Kết nối CSDL
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        // Nếu kết nối lỗi, coi như chưa đăng nhập hoặc có lỗi hệ thống
        $is_logged_in = false; 
    }

    if ($conn && !$conn->connect_error) {
        function get_statistic_value($conn, $sql) {
            $result = $conn->query($sql);
            // Kiểm tra kết quả và trả về giá trị đầu tiên (số lượng)
            if ($result && $row = $result->fetch_array()) {
                return $row[0];
            }
            return 0;
        }
    }
    if ($is_logged_in) {
        // // Lấy thông tin người dùng an toàn hơn (Prepared Statement)
         $sql = "SELECT user_name, email FROM quantrivien WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user_info = $result->fetch_assoc(); 
        } else {
            // Nếu email trong cookie không tồn tại trong DB, xóa cookie và đặt trạng thái chưa đăng nhập
            setcookie("email", "", time() - 3600, "/"); 
            setcookie("user_name", "", time() - 3600, "/");
            $is_logged_in = false;
        }

        $stmt->close();
    }
    // D. THỰC HIỆN TRUY VẤN THỐNG KÊ VÉ CHỈ KHI ĐÃ ĐĂNG NHẬP VÀ KẾT NỐI TỐT
    if ($is_logged_in) {
        // Lấy TỔNG SỐ DÒNG (COUNT)
        $sql_count = "
            SELECT COUNT(DISTINCT CONCAT(s.TenSK, lv.TenLoai)) AS total_items
            FROM ve v JOIN loaive lv ON v.MaLoai = lv.MaLoai JOIN sukien s ON lv.MaSK = s.MaSK;
        ";
        $result_count = $conn->query($sql_count);
        $total_items = $result_count ? $result_count->fetch_assoc()['total_items'] : 0;
        $total_pages = ceil($total_items / $items_per_page);

        // Tính toán OFFSET cho truy vấn chính
        $offset = ($current_page - 1) * $items_per_page;
        if ($offset < 0) $offset = 0;
        
        // 2. Câu truy vấn chính đã thêm LIMIT và OFFSET
        $sql_thong_ke_ve = "
            SELECT 
                s.TenSK, lv.TenLoai, lv.MaLoai, COUNT(v.MaVe) AS TongSoVe,
                SUM(CASE WHEN v.MaTT IS NOT NULL THEN 1 ELSE 0 END) AS SoVeDaThanhToan,
                SUM(CASE WHEN v.TrangThai = 'chưa thanh toán' AND v.MaTT IS NULL THEN 1 ELSE 0 END) AS SoVeTon
            FROM ve v JOIN loaive lv ON v.MaLoai = lv.MaLoai JOIN sukien s ON lv.MaSK = s.MaSK
            GROUP BY s.TenSK, lv.TenLoai, lv.MaLoai
            ORDER BY s.TenSK ASC, lv.TenLoai ASC
            LIMIT $items_per_page OFFSET $offset;
        ";

        $result_thong_ke_ve = $conn->query($sql_thong_ke_ve);
    }

    if ($is_logged_in) {
        // 1. Lấy TỔNG SỐ DÒNG (COUNT) cho Sự kiện (Đã thêm điều kiện tìm kiếm)
        $sql_count_sk = "SELECT COUNT(MaSK) AS total_items FROM sukien" . $search_condition;
        
        $result_count_sk = $conn->query($sql_count_sk);
        $total_items_sukien = $result_count_sk ? $result_count_sk->fetch_assoc()['total_items'] : 0;
        
        // Tính tổng số trang (vẫn 10 mục/trang)
        $total_pages_sukien = ceil($total_items_sukien / $items_per_page_sukien);

        // Tính toán OFFSET cho truy vấn sự kiện
        $offset_sukien = ($current_page_sukien - 1) * $items_per_page_sukien;
        if ($offset_sukien < 0) $offset_sukien = 0;
        
        // 2. Câu truy vấn chính (Đã thêm điều kiện tìm kiếm)
        // lấy sự kiện xếp theo thứ tự mã sự kiện
        $sql_sukien = "
            SELECT 
                MaSK, 
                TenSK, 
                TGian, 
                luot_timkiem,
                luot_truycap 
            FROM sukien " 
        . $search_condition . 
            " ORDER BY MaSK ASC
            LIMIT $items_per_page_sukien OFFSET $offset_sukien;
        ";

        $result_sukien = $conn->query($sql_sukien);
    }

    if ($is_logged_in && $conn && !$conn->connect_error) {
        // 1. Lấy TỔNG SỐ DÒNG (COUNT)
        // COUNT DISTINCT (MaloaiSK) vì mỗi loại sự kiện là một dòng
        $sql_count_lsk = "SELECT COUNT(DISTINCT t1.MaloaiSK) AS total_items 
                        FROM loaisk t1 JOIN sukien t2 ON t1.MaloaiSK = t2.MaLSK";
        
        $result_count_lsk = $conn->query($sql_count_lsk);
        $total_items_lsk = $result_count_lsk ? $result_count_lsk->fetch_assoc()['total_items'] : 0;
        
        // Tính tổng số trang (sử dụng $items_per_page = 10)
        $total_pages_lsk = ceil($total_items_lsk / $items_per_page);

        // Tính toán OFFSET cho truy vấn loại sự kiện
        $offset_lsk = ($current_page_lsk - 1) * $items_per_page;
        if ($offset_lsk < 0) $offset_lsk = 0;
        
        // 2. Câu truy vấn chính đã thêm LIMIT và OFFSET
        // 2. Câu truy vấn chính đã thêm LIMIT và OFFSET
        $sql_thong_ke_loai_sk = "
            SELECT
                t1.MaloaiSK,
                t1.TenLoaiSK,
                COUNT(t2.MaSK) AS SoLuongSuKien
            FROM
                loaisk t1
            JOIN
                sukien t2 ON t1.MaloaiSK = t2.MaLSK
            GROUP BY
                t1.MaloaiSK, t1.TenLoaiSK /* Phải GROUP BY cả 2 trường */
            ORDER BY
                SoLuongSuKien DESC
            LIMIT $items_per_page OFFSET $offset_lsk;
        ";

        $result_thong_ke_loai_sk = $conn->query($sql_thong_ke_loai_sk);
    }
    
    if ($is_logged_in) {
        $tong_khach_hang_tk = get_statistic_value($conn, "SELECT COUNT(email) FROM khachhang");

        // Tổng số Nhân viên soát vé
        $tong_nhan_vien = get_statistic_value($conn, "SELECT COUNT(email) FROM nhanviensoatve");
        
        // Tổng số Nhà tổ chức (Đã có 2 bản ghi trong CSDL mới)
        $tong_nha_to_chuc = get_statistic_value($conn, "SELECT COUNT(email) FROM nhatochuc"); // <--- Đã thêm truy vấn này

        // Tổng số Tài khoản (Tổng 3 loại)
        $tong_tai_khoan_3_bang = $tong_khach_hang_tk + $tong_nhan_vien + $tong_nha_to_chuc;

        $sql_khachhang = "
            SELECT 
                email,
                 user_name,
                 tel
            FROM khachhang " 
        . $search_condition . 
            " ORDER BY email ASC
            LIMIT $items_per_page_khachhang OFFSET $offset_khachhang;
        ";

        $result_khachhang = $conn->query($sql_khachhang);

        $sql_nhanviensoatve = "
            SELECT 
                email,
                 user_name,
                 gender,
                 tel
            FROM nhanviensoatve " 
        . $search_condition . 
            " ORDER BY email ASC
            LIMIT $items_per_page_nhanviensoatve OFFSET $offset_nhanviensoatve;
        ";

        $result_nhanviensoatve = $conn->query($sql_nhanviensoatve);

        $sql_nhatochuc = "
            SELECT 
                email,
                 user_name,
                 tel,
                 address,
                 taikhoannganhang
            FROM nhatochuc " 
        . $search_condition . 
            " ORDER BY email ASC
            LIMIT $items_per_page_nhatochuc OFFSET $offset_nhatochuc;
        ";

        $result_nhatochuc = $conn->query($sql_nhatochuc);
    }




    // E. ĐÓNG KẾT NỐI SAU KHI DÙNG XONG TẤT CẢ
    if ($conn && !$conn->connect_error) {
        $conn->close();
    }

}

// Thiết lập tiêu đề và assets trang, dùng header/footer chung
$page_title = 'Quản trị viên';
$additional_css = ['webstyle.css'];
$additional_head = <<<HTML
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
                <span class="brand-text">Quản trị viên</span>
        </div>
        <nav class="nav">
        <button class="nav-item active" id="btn-sukien">
            <i class="fa-regular fa-calendar-check"></i>
            <span>Quản lý sự kiện</span>
        </button>
        <button class="nav-item" id="btn-danhmuc">
            <i class="fa-regular fa-flag"></i>
            <span>Quản lý danh mục</span>
        </button>
        <button class="nav-item" id="btn-nguoidung">
            <i class="fa-solid fa-users"></i>
            <span>Quản lý người dùng</span>
        </button>
        <button class="nav-item" id="btn-ve">
            <i class="fa-solid fa-ticket"></i>
            <span>Quản lý vé</span>
        </button>
        <button class="nav-item" id="btn-thongke">
            <i class="fa-regular fa-file-lines"></i>
            <span>Thống kê</span>
        </button>
        <?php if ($is_logged_in && $user_info): ?>
            <label class="email_ntc">
                <i class="fa-solid fa-envelope"></i>
                <span>Email: <b><?php echo htmlspecialchars($user_info['email']); ?></b></span>
            </label>
    
            <div >
                <div class="box_3">
                    <a href="dangxuat.php" class="w3-bar-item w3-button w3-padding" id="logout" data-bs-toggle="tooltip" title="Đăng xuất">
                        <i class="fa-solid fa-right-from-bracket"></i> 
                    </a>
                </div>           
            </div>
        <?php endif; ?>
    </article>

    <article class="noidung" id="sukien-section">
        <h2 class="noidung-title">QUẢN LÝ SỰ KIỆN</h2>
        <div class="header">
            <div class="actions">
                <form class="searchbar" method="get" action="admin.php">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="q" placeholder="Danh sách sự kiện và thao tác duyệt/sửa/xóa sẽ hiển thị ở đây." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
                    <button class="btn-search" type="submit">Tìm kiếm</button>
                </form>
            </div>
        </div>

        <div class="thongkesukien mt-3">
        <?php if (!$is_logged_in): ?>
            <p style="color: red;">⚠️ Vui lòng đăng nhập để xem nội dung này.</p>
        <?php elseif (isset($result_sukien) && $result_sukien->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="tieudeqlve">Mã SK</th>
                            <th class="tieudeqlve">Tên Sự Kiện</th>
                            <th class="tieudeqlve">Thời Gian Bắt Đầu</th>
                            <th class="tieudeqlve">Lượt Truy Cập</th>
                            <th class="tieudeqlve">Lượt tìm kiếm</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_sukien->fetch_assoc()): ?>
                            <tr>
                                <td class="ndsk"><?php echo htmlspecialchars($row['MaSK']); ?></td>
                                <td class="ndsk"><?php echo htmlspecialchars($row['TenSK']); ?></td>
                                <td class="ndsk">
                                    <?php 
                                        // Định dạng lại thời gian cho dễ đọc
                                        $timestamp = strtotime($row['TGian']);
                                        echo date('d/m/Y H:i', $timestamp); 
                                    ?>
                                </td>
                                <td class="ndsk">
                                    <b><?php echo number_format($row['luot_timkiem'] ?? 0); ?></b>
                                </td>
                                <td class="ndsk">
                                    <b><?php echo number_format($row['luot_truycap'] ?? 0); ?></b>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_pages_sukien > 1): ?>
                <nav aria-label="Page navigation" class="mt-3">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= ($current_page_sukien <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="admin.php?tab=sukien&trang_sk=<?= $current_page_sukien - 1 ?><?= !empty($search_query) ? '&q=' . urlencode($search_query) : '' ?>#sukien-section">Previous</a>
                        </li>
                        
                        <?php for ($i = 1; $i <= $total_pages_sukien; $i++): ?>
                            <li class="page-item <?= ($i == $current_page_sukien) ? 'active' : '' ?>">
                                <a class="page-link" href="admin.php?tab=sukien&trang_sk=<?= $i ?><?= !empty($search_query) ? '&q=' . urlencode($search_query) : '' ?>#sukien-section"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?= ($current_page_sukien >= $total_pages_sukien) ? 'disabled' : '' ?>">
                            <a class="page-link" href="admin.php?tab=sukien&trang_sk=<?= $current_page_sukien + 1 ?><?= !empty($search_query) ? '&q=' . urlencode($search_query) : '' ?>#sukien-section">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>

        <?php else: ?>
            <p>Không có sự kiện nào được tìm thấy hoặc lỗi kết nối CSDL.</p>
        <?php endif; ?>
    </div>
    </article>

    <article class="noidung hidden" id="danhmuc-section">
        <h2 class="noidung-title">QUẢN LÝ DANH MỤC</h2>
        <div class="header">
            <div class="actions">
                <form class="searchbar" method="get" action="admin.php">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="q" placeholder="Thêm/sửa/xóa danh mục sự kiện." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
                    <button class="btn-search" type="submit">Tìm kiếm</button>
                </form>
            </div>
        </div>
            <div class="thongkeloaisk mt-3">
                <?php if (!$is_logged_in): ?>
                    <p style="color: red;">⚠️ Vui lòng đăng nhập để xem nội dung này.</p>
                <?php elseif (isset($result_thong_ke_loai_sk) && $result_thong_ke_loai_sk->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="tieudeqlve">Loại Sự Kiện</th>
                                    <th class="tieudeqlve">Số Lượng Sự Kiện</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result_thong_ke_loai_sk->fetch_assoc()): ?>
                                    <tr>
                                        <td class="ndsk">
                                            <a href="#" class="loaisk-link" data-id="<?php echo htmlspecialchars($row['MaloaiSK']); ?>" style="color: blue; text-decoration: underline;">
                                                <?php echo htmlspecialchars($row['TenLoaiSK']); ?>
                                            </a>
                                        </td>
                                        <td class="ndsk">
                                            <b><?php echo number_format($row['SoLuongSuKien']); ?></b>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                        <?php if ($total_pages_lsk > 1): ?>
                            <nav aria-label="Page navigation" class="mt-3">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item <?= ($current_page_lsk <= 1) ? 'disabled' : '' ?>">
                                        <a class="page-link" href="admin.php?tab=danhmuc&trang_lsk=<?= $current_page_lsk - 1 ?>#danhmuc-section">Previous</a>
                                    </li>
                                    
                                    <?php for ($i = 1; $i <= $total_pages_lsk; $i++): ?>
                                        <li class="page-item <?= ($i == $current_page_lsk) ? 'active' : '' ?>">
                                            <a class="page-link" href="admin.php?tab=danhmuc&trang_lsk=<?= $i ?>#danhmuc-section"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <li class="page-item <?= ($current_page_lsk >= $total_pages_lsk) ? 'disabled' : '' ?>">
                                        <a class="page-link" href="admin.php?tab=danhmuc&trang_lsk=<?= $current_page_lsk + 1 ?>#danhmuc-section">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        <?php endif; ?>
                <?php else: ?>
                    <p>Không có dữ liệu thống kê loại sự kiện nào được tìm thấy.</p>
                <?php endif; ?>
            </div>
            <div id="sukien-chi-tiet" class="mt-4 p-3 border rounded" style="display: none;">
                <h4 id="chi-tiet-title">Danh sách Sự kiện:</h4>
                <div id="sukien-list"></div>
            </div>
    </article>

    <article class="noidung hidden" id="nguoidung-section">
        <h2 class="noidung-title">QUẢN LÝ NGƯỜI DÙNG</h2>
        <div class="header">
            <div class="actions">
                <form class="searchbar" method="get" action="admin.php">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="q" placeholder="Danh sách và quyền hạn người dùng." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
                    <button class="btn-search" type="submit">Tìm kiếm</button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Tổng số Khách hàng</div>
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $tong_khach_hang_tk; ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Tổng số Nhân viên</div>
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $tong_nhan_vien; ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-header">Tổng số Nhà tổ chức</div>
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $tong_nha_to_chuc; ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-header">TỔNG SỐ TÀI KHOẢN (3 loại)</div>
                    <div class="card-body">
                        <h3 class="card-title"><?php echo $tong_tai_khoan_3_bang; ?></h3>
                    </div>
                </div>
            </div>
        </div>

    </article>

    <article class="noidung hidden" id="ve-section">
        <h2 class="noidung-title">QUẢN LÝ VÉ</h2>
        <div class="header">
            <div class="actions">
                <form class="searchbar" method="get" action="admin.php">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="q" placeholder="Theo dõi vé, doanh thu từng sự kiện." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
                    <button class="btn-search" type="submit">Tìm kiếm</button>
                </form>
            </div>
        </div>
       
        <!-- Quản lý số lượng vé bán-->
        <div class="thongkeve">
        <?php if (!$is_logged_in): ?>
        <p style="color: red;">⚠️ Vui lòng đăng nhập để xem nội dung này.</p>
        <?php elseif (isset($result_thong_ke_ve) && $result_thong_ke_ve->num_rows > 0): ?>
        <div class=" table-responsive">
            <table class="table table-striped table-hover " >
                <thead >
                    <tr >
                        <th class="tieudeqlve">Tên Sự Kiện</th> 
                        <th class="tieudeqlve">Loại Vé</th>
                        <th class="tieudeqlve">Tổng Số Vé</th>
                        <th class="tieudeqlve">Đã Thanh Toán </th>
                        <th class="tieudeqlve">Tồn Kho </th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_thong_ke_ve->fetch_assoc()): ?>
                        <tr>
                            <td class="ndsk"><?php echo htmlspecialchars($row['TenSK']); ?></td>
                            <td class="ndsk"><?php echo htmlspecialchars($row['TenLoai']); ?> (<?php echo htmlspecialchars($row['MaLoai']); ?>)</td>
                            <td class="ndsk"><?php echo number_format($row['TongSoVe']); ?></td>
                            <td  class="ndsk">
                                <b><?php echo number_format($row['SoVeDaThanhToan']); ?></b>
                            </td class="ndsk">
                            <td  class="ndsk">
                                <?php echo number_format($row['SoVeTon']); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation " class="mt-3">
            <ul class="pagination justify-content-center ">
                <li class="page-item   <?= ($current_page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link " href="admin.php?tab=ve&trang=<?= $current_page - 1 ?>#ve-section">Previous</a>
                </li>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
                        <a class="page-link" href="admin.php?tab=ve&trang=<?= $i ?>#ve-section"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                
                <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="admin.php?tab=ve&trang=<?= $current_page + 1 ?>#ve-section">Next</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
                <?php else: ?>
                <p>Không có dữ liệu thống kê vé hoặc lỗi kết nối CSDL.</p>
            <?php endif; ?>

        </div>   
    </article>

    <article class="noidung hidden" id="thongke-section">
        <h2 class="noidung-title">THỐNG KÊ</h2>
        <div class="header">
            <div class="actions">
                <form class="searchbar" method="get" action="admin.php">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="q" placeholder="Báo cáo tổng hợp theo thời gian." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
                    <button class="btn-search" type="submit">Tìm kiếm</button>
                </form>
            </div>
        </div>
        <i class="fa-solid fa-spinner"></i> Đang cập nhật...</i>
    </article>


       
    </main>
<?php 
    $additional_footer_scripts = <<<HTML
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script defer src="/scripts/web-layout.js"></script>
        <script defer src="/scripts/homepage.js"></script>
    HTML;
    require_once 'footer.php';
?>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
        const btnSukien = document.getElementById("btn-sukien");
        const btnDanhmuc = document.getElementById("btn-danhmuc");
        const btnNguoidung = document.getElementById("btn-nguoidung");
        const btnVe = document.getElementById("btn-ve");
        const btnThongke = document.getElementById("btn-thongke");

        const sectionSukien = document.getElementById("sukien-section");
        const sectionDanhmuc = document.getElementById("danhmuc-section");
        const sectionNguoidung = document.getElementById("nguoidung-section");
        const sectionVe = document.getElementById("ve-section");
        const sectionThongke = document.getElementById("thongke-section");

        const sections = [sectionSukien, sectionDanhmuc, sectionNguoidung, sectionVe, sectionThongke];
        const buttons = [btnSukien, btnDanhmuc, btnNguoidung, btnVe, btnThongke];
        
        // Ánh xạ tham số URL 'tab' sang ID của button/section
        const tabMap = {
            'sukien': { button: btnSukien, section: sectionSukien },
            'danhmuc': { button: btnDanhmuc, section: sectionDanhmuc },
            'nguoidung': { button: btnNguoidung, section: sectionNguoidung },
            've': { button: btnVe, section: sectionVe },
            'thongke': { button: btnThongke, section: sectionThongke },
        };
        function showSection(sectionToShow, clickedButton) {
            [sectionSukien, sectionDanhmuc, sectionNguoidung, sectionVe, sectionThongke]
              .forEach(sec => sec.classList.add("hidden"));
            sectionToShow.classList.remove("hidden");
            [btnSukien, btnDanhmuc, btnNguoidung, btnVe, btnThongke]
              .forEach(btn => btn.classList.remove("active"));
            clickedButton.classList.add("active");
        }

        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab') || 'sukien'; // Mặc định là 'sukien'
        
        if (tabMap[activeTab]) {
            showSection(tabMap[activeTab].section, tabMap[activeTab].button);
        } else {
            // Đảm bảo mục 'sukien' vẫn là mặc định nếu không có tham số hợp lệ
            showSection(sectionSukien, btnSukien);
        }

        btnSukien.addEventListener("click", () => showSection(sectionSukien, btnSukien));
        btnDanhmuc.addEventListener("click", () => showSection(sectionDanhmuc, btnDanhmuc));
        btnNguoidung.addEventListener("click", () => showSection(sectionNguoidung, btnNguoidung));
        btnVe.addEventListener("click", () => showSection(sectionVe, btnVe));
        btnThongke.addEventListener("click", () => showSection(sectionThongke, btnThongke));
        });


        $(document).on('click', '.loaisk-link', function(e) {
        e.preventDefault(); // Ngăn chặn hành động mặc định của thẻ a
        
        const maLoai = $(this).data('id');
        const tenLoai = $(this).text().trim();
        const chiTietDiv = $('#sukien-chi-tiet');
        const listDiv = $('#sukien-list');

        // Hiển thị loading
        listDiv.html('<i class="fa-solid fa-spinner fa-spin"></i> Đang tải danh sách sự kiện...');
        chiTietDiv.show();
        $('#chi-tiet-title').text(`Danh sách Sự kiện thuộc loại "${tenLoai}":`);

        // Gửi yêu cầu AJAX
        $.ajax({
            url: 'get_sk_by_loai.php', // Tên file mới sẽ tạo ở Bước 3
            type: 'GET',
            data: { maloai: maLoai },
            success: function(response) {
                listDiv.html(response);
            },
            error: function() {
                listDiv.html('<p style="color: red;">Lỗi khi tải dữ liệu sự kiện.</p>');
            }
        });
        // Cuộn màn hình về khu vực hiển thị kết quả
        $('html, body').animate({
            scrollTop: chiTietDiv.offset().top - 20 
        }, 500);
    });
</script>