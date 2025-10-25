<?php
// Kết nối CSDL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlkhachhang";

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
  // $sql = "SELECT * FROM khachhang WHERE email = '$email'";
  // $sql = "SELECT * FROM quantrivien WHERE email = '$email'";
  // $sql = "SELECT * FROM nhatochuc WHERE email = '$email'";
  // $sql = "SELECT * FROM nhanviensoatve WHERE email = '$email'";
  $email = $conn->real_escape_string($email);
  $tables = ['khachhang', 'quantrivien', 'nhatochuc', 'nhanviensoatve'];

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
     // setcookie("id", $user['id'], time() + 3600, "/");

     switch ($user['table']) {
            case 'khachhang':
                header("Location: trangchu.html");
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
        exit();
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
