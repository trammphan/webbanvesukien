<?php
session_start(); // Bắt buộc ở đầu file

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
    
    // Kiểm tra mật khẩu
    if ($password === $user['password'] || md5($password) === $user['password'] ) 
    {
      
      // Gán phiên đăng nhập (SESSION)
      $_SESSION['email'] = $user['email'];
      $_SESSION['user_name'] = $user['user_name']; // Đảm bảo cột 'user_name' tồn tại trong CSDL

      // Điều hướng theo loại tài khoản
      switch ($user['table']) {
          case 'khachhang':
              if (isset($_POST['redirect']) && !empty($_POST['redirect'])) {
                  header('Location: ' . urldecode($_POST['redirect']));
              } else {
                  header("Location: index.php");
              }
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
      echo "<script>alert('Sai mật khẩu!'); window.location='dangnhap.php';</script>";
    }
  } 
  else {
    echo "<script>alert('Email không tồn tại!'); window.location='dangnhap.php';</script>";
  }
}

$conn->close();
?>