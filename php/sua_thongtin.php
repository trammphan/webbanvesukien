<?php
// Bắt đầu phiên (cần thiết nếu trang khác vẫn dùng session)
session_start();

// 🔹 Kiểm tra cookie "email" thay vì session
if (!isset($_COOKIE["email"])) {
    header("Location: dangnhap.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlysukien";

// Kết nối CSDL
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$msg = ""; // Biến chứa thông báo

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_pass = md5($_POST["old_pass"]);
    $new_pass = $_POST["new_pass"];
    $confirm_pass = $_POST["confirm_pass"];
    $email = $_COOKIE["email"]; // 🔹 Lấy email từ cookie

    // Lấy mật khẩu cũ từ CSDL
    $sql = "SELECT password FROM khachhang WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_pass = $row["password"];

        // Kiểm tra mật khẩu cũ có khớp không
        if ($old_pass != $current_pass) {
            $msg = "❌ Mật khẩu cũ không đúng!";
        } elseif ($new_pass != $confirm_pass) {
            $msg = "❌ Mật khẩu mới và nhập lại không trùng khớp!";
        } elseif ($old_pass == md5($new_pass)) {
            $msg = "⚠️ Mật khẩu mới không được giống mật khẩu cũ!";
        } else {
            // Cập nhật mật khẩu mới (đã băm md5)
            $new_pass_md5 = md5($new_pass);
            $update_sql = "UPDATE khachhang SET password = '$new_pass_md5' WHERE email = '$email'";

            if ($conn->query($update_sql) === TRUE) {
                $msg = "✅ Đổi mật khẩu thành công!";
                header("Location: dangnhap.php");
            } else {
                $msg = "Lỗi khi cập nhật mật khẩu: " . $conn->error;
            }
        }
    } else {
        $msg = "❌ Không tìm thấy người dùng!";
    }
}

$conn->close();
?>


<?php
// Load CSS của trang người dùng nếu cần
$additional_css = ['webstyle.css'];
// Giữ tiêu đề và assets head gốc
$page_title = 'Người dùng';
$additional_head = <<<HTML
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
HTML;
require_once 'header.php';
?>
<main>
   <article class="khungdungchung">
        <h2 class="w3-text-blue">🔒 Đổi mật khẩu</h2>

        <form class="w3-container w3-card-4 w3-light-grey" method="POST" style="max-width:500px;">
         <div class="thongtin">
            <label for="password">
                <i class="fa-solid fa-keyboard"></i></label>
            <input type="password" name="old_pass" id="old_pass"
                     placeholder="Vui lòng nhập mật khẩu cũ" required>
          </div>
          <div class="thongtin">
            <label for="password">
                <i class="fa-solid fa-key"></i></label>
            <input type="password" name="new_pass" id="new_pass"
                placeholder="Vui lòng nhập mật khẩu mới" required>
          </div>
          <div class="thongtin">
            <label for="password">
                <i class="fa-solid fa-clone"></i></label>
            <input type="password" name="confirm_pass" id="confirm_pass" 
                placeholder="Vui lòng nhập lại mật khẩu mới" required>
          </div>

        <div class="container_2">
            <div class="back">
                <a href="nguoidung.php" class="w3-bar-item w3-button w3-padding" id="back" class="sua"
                data-bs-toggle="tooltip" title="Quay lại trang người dùng">
                    <i class="fa-solid fa-backward"></i>
                </a>
            </div>
            <div class="update_info" >
                <button  type="submit" id="change" data-bs-toggle="tooltip"
                 title="Cập nhật thông tin" class="sua">
                    <i class="fa-solid fa-user-check" ></i>
                </button>
            </div>
        </div>
        
          </form>

        <?php if ($msg != "") echo "<p class='w3-text-red w3-margin-top'><b>$msg</b></p>"; ?>
    </article>
</main>
<?php 
    $additional_footer_scripts = <<<HTML
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    HTML;
    require_once 'footer.php';
?>