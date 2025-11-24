<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlysukien";

header('Content-Type: application/json');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Kết nối CSDL thất bại: ' . $conn->connect_error]);
    exit;
}

$conn->set_charset("utf8mb4");


$sql = "SELECT MaSK, luot_truycap FROM sukien ORDER BY luot_truycap DESC LIMIT 10"; 
$result = $conn->query($sql);

$data = []; 
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = [
            'label' => $row['MaSK'],   
            'value' => (int)$row['luot_truycap'] 
        ];
    }
}
$response = [
    'success' => true,
    'data' => $data 
];

echo json_encode($response);
$conn->close();
?>