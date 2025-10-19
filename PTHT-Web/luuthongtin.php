<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlkhachhang";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$date = date_create($_POST["birth"]);

$sql = "INSERT INTO khachhang (user_name, gender, birthday, tel, address, email, password) 
	VALUES ('".$_POST["user_name"] ."',
          '".$_POST["gender"] ."',
          '".$date ->format('Y-m-d') ."',
          '".$_POST["tel"] ."',
          '".$_POST["address"] ."',
          '".$_POST["email"] ."',
          '".md5(trim($_POST["password"]))."' 
          )";

if ($conn->query($sql) == TRUE) {
  header("Location: dangnhap.php");
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
