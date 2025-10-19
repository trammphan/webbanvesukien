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
  $sql = "SELECT * FROM khachhang WHERE email = '$email'";
  $result = $conn->query($sql);

  // Kiểm tra có tài khoản không
  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

     if (md5($password) === $row['password'])  {
      
      setcookie("email", $row['email'], time() + 3600, "/");
      setcookie("user_name", $row['"user_name"'], time() + 3600, "/");
     // setcookie("id", $row['id'], time() + 3600, "/");

      header("Location: nguoidung.php");
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
