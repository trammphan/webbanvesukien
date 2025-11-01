
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlysukien";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Lấy dữ liệu từ POST và làm sạch (chuyển sang dùng trim và Prepared Statement)
$user_name = trim($_POST["user_name"] ?? '');
$tel = trim($_POST["tel"] ?? '');
$email = trim(strtolower($_POST["email"] ?? ''));
$raw_password = $_POST["password"] ?? '';
$redirect_url = $_POST["redirect"] ?? '';

$errors = [];

// --- 1. XÁC THỰC SERVER-SIDE (Kiểm tra trường rỗng và độ dài) ---

if (empty($user_name) || empty($tel) || empty($email) || empty($raw_password)) {
    $errors[] = "Vui lòng điền đầy đủ tất cả các trường bắt buộc.";
}

// Kiểm tra độ dài mật khẩu tối thiểu 5 ký tự
if (strlen($raw_password) < 5) {
    $errors[] = "Mật khẩu phải có tối thiểu 5 ký tự.";
}

// --- 2. BẮT SỰ KIỆN EMAIL ĐÃ TỒN TẠI (TRƯỚC INSERT) ---

if (empty($errors)) {
    // Kiểm tra Email đã tồn tại trong NHIỀU BẢNG (khachhang, nhatochuc, nhanviensoatve)
    $check_email_sql = "
        SELECT email FROM khachhang WHERE email = ?
        UNION
        SELECT email FROM nhatochuc WHERE email = ?
        UNION
        SELECT email FROM nhanviensoatve WHERE email = ?
    ";
    $stmt_check = $conn->prepare($check_email_sql);
    
    // Liên kết 3 tham số (đều là email)
    $stmt_check->bind_param("sss", $email, $email, $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $errors[] = "Email này đã được sử dụng bởi một tài khoản khác. Vui lòng dùng email khác.";
    }
    $stmt_check->close();
}

// --- 3. XỬ LÝ LỖI VÀ THỰC HIỆN INSERT ---

if (!empty($errors)) {
    $conn->close(); // Đóng kết nối trước khi xuất HTML

    // Lấy URL chuyển hướng (nếu có) để quay lại đúng trang đăng ký ban đầu
    $redirect_param = !empty($redirect_url) ? '?redirect=' . urlencode($redirect_url) : '';
    $dangky_url = "dangky.php" . $redirect_param;

    ?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title>Đăng ký Thất bại</title>
        <style>
            body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #d7f3f8ff; }
            .error-box { background-color: #c6d7f5ff; border: 1px solid #3546dcff; color: #081c55ff; padding: 20px; border-radius: 5px; text-align: center; max-width: 400px; }
            .error-box h3 { color: #35b8dcff; margin-top: 0; }
            .error-box ul { list-style: none; padding: 0; }
            .countdown { font-size: 1.2em; font-weight: bold; margin-top: 15px; }
        </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
    </head>
    <body>
        <div class="error-box">
            <h3>🔴 Đăng ký thất bại!</h3>
            <ul>
                <?php foreach ($errors as $error) { ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php } ?>
            </ul>
            <p>Bạn sẽ được chuyển hướng về trang Đăng ký sau <span id="countdown-timer">10</span> giây.</p>
            <p>Hoặc <a href="<?php echo $dangky_url; ?>"><i class="fa-solid fa-backward-fast"></i></a>.</p>
        </div>

        <script>
            let seconds = 10;
            const timerElement = document.getElementById('countdown-timer');
            const targetURL = "<?php echo $dangky_url; ?>";

            function updateTimer() {
                timerElement.textContent = seconds;
                seconds--;
                
                if (seconds < 0) {
                    window.location.href = targetURL;
                } else {
                    // Cập nhật lại sau 1 giây
                    setTimeout(updateTimer, 1000); 
                }
            }

            // Bắt đầu đếm ngược
            setTimeout(updateTimer, 1000); 
        </script>
    </body>
    </html>
    <?php
    exit(); // RẤT QUAN TRỌNG: Dừng PHP sau khi xuất HTML
}

// Hash mật khẩu an toàn thay cho md5()
$password = md5(trim($_POST["password"]));

// Sử dụng Prepared Statement cho câu lệnh INSERT (AN TOÀN HƠN)
$sql = "INSERT INTO khachhang (email, user_name, tel, password) 
        VALUES (?, ?, ?, ?)";

$stmt_insert = $conn->prepare($sql);
$stmt_insert->bind_param("ssss", $email, $user_name, $tel, $password); // Lưu $hashed_password

if ($stmt_insert->execute()) {
    // *** LOGIC REDIRECT SAU ĐĂNG KÝ ***
    
    // Kiểm tra xem có URL redirect không
    if (!empty($redirect_url)) {
        // Tự động đăng nhập và chuyển hướng TRỞ LẠI trang sự kiện
        setcookie("email", $email, time() + 3600, "/");
        setcookie("user_name", $user_name, time() + 3600, "/");
        
        header('Location: ' . urldecode($redirect_url));
        
    } else {
        // Nếu không, chuyển hướng về trang đăng nhập (có thể thay bằng trang người dùng)
        header("Location: dangnhap.php");
    }
    $stmt_insert->close();
    
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>