

<?php
session_start();
// Kết nối CSDL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlysukien";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}

// Khi người dùng nhấn nút Đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = mysqli_real_escape_string($conn, trim(strtolower($_POST['email'])));
  $password = mysqli_real_escape_string($conn, trim($_POST['password']));
  $redirect_url = $_POST['redirect']; 
  // Lấy thông tin người dùng theo email
  $email = $conn->real_escape_string($email);
  $tables = ['khachhang', 'quantrivien', 'nhatochuc', 'nhanviensoatve'];
  $user = null; // Khởi tạo $user

  foreach ($tables as $table) {
    $sql = "SELECT * FROM $table WHERE email = '$email' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user['table'] = $table; // biết được người dùng thuộc bảng nào
        break; // dừng lại khi đã tìm thấy
    }
  }


  // Kiểm tra có tài khoản không
    if (!empty($user)) {
    //$user= $result->fetch_assoc();

      if ($password === $user['password'] || md5($password) === $user['password'] ) 
      {
      
      setcookie("email", $user['email'], time() + 3600, "/");
      setcookie("user_name", $user['user_name'], time() + 3600, "/");
      setcookie("user_role", $user['table'], time() + 3600, "/");

      switch ($user['table']) {
            case 'khachhang':
                if (isset($redirect_url) && !empty($redirect_url)) {
                       header('Location: ' . $redirect_url);
                } else {
                      header("Location: index.php");
                } 
                // --- KẾT THÚC PHẦN GỘP CODE ---
                break;
            case 'quantrivien':
                header("Location: admin.php");
                break;
            case 'nhatochuc':
                header("Location: nhatochuc.php");
                break;
            case 'nhanviensoatve':
                header("Location: nhanvien.php");
                break;
        }
        exit(); // Rất quan trọng, phải gọi exit() sau khi header()
}
      else {
        // BỔ SUNG MỚI (3/3): Thêm redirect_url vào link lỗi
        $error_location = 'dangnhap.php';
      if (!empty($redirect_url)) {
            // Gửi lại redirect_url để form đăng nhập không bị mất
            $error_location .= '?redirect=' . urlencode($redirect_url);
        }
      echo "<script>alert('Sai mật khẩu!'); window.location='$error_location';</script>";
     }
   } 
   else {
    // BỔ SUNG MỚI (3/3): Thêm redirect_url vào link lỗi
    $error_location = 'dangnhap.php';
    if (!empty($redirect_url)) {
        // Gửi lại redirect_url để form đăng nhập không bị mất
        $error_location .= '?redirect=' . urlencode($redirect_url);
    }
  echo "<script>alert('Email không tồn tại!'); window.location='$error_location';</script>";
 }
}

$conn->close();
?>
