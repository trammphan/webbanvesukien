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

// Lấy dữ liệu từ POST để đảm bảo an toàn
$user_name = $conn->real_escape_string($_POST["user_name"]);
$gender = $conn->real_escape_string($_POST["gender"]);
$birthday = $conn->real_escape_string($_POST["birthday"]); // Sửa: Tên input là 'birthday' không phải 'birth'
$tel = $conn->real_escape_string($_POST["tel"]);
$address = $conn->real_escape_string($_POST["address"]);
$email = $conn->real_escape_string(trim(strtolower($_POST["email"])));
$password = md5(trim($_POST["password"]));

// Sửa: Đảm bảo tên 'birthday' từ form được sử dụng
$date = date_create($birthday);
$formatted_birthday = $date->format('Y-m-d');

//$date = date_create($_POST["birth"]);

$sql = "INSERT INTO khachhang (user_name, gender, birthday, tel, address, email, password) 
  VALUES ('$user_name',
          '$gender',
          '$formatted_birthday',
          '$tel',
          '$address',
          '$email',
          '$password' 
          )";

if ($conn->query($sql) == TRUE) {
    // *** BẮT ĐẦU THAY ĐỔI ***
    
    // Kiểm tra xem có URL redirect không
    if (isset($_POST['redirect']) && !empty($_POST['redirect'])) {
        // Nếu có (từ luồng mua vé), tự động đăng nhập cho họ
        
        // 1. Set cookie
        setcookie("email", $email, time() + 3600, "/");
        setcookie("user_name", $user_name, time() + 3600, "/");
        
        // 2. Chuyển hướng TRỞ LẠI trang sự kiện
        header('Location: ' . urldecode($_POST['redirect']));
        
    } else {
        // Nếu không, chuyển hướng về trang đăng nhập như cũ
        header("Location: dangnhap.php");
    }
    // *** KẾT THÚC THAY ĐỔI ***
    
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
