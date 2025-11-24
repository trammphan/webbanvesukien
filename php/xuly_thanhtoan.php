<?php
session_start();
if (!isset($_COOKIE['email']) || empty($_COOKIE['email'])){
    $redirect_url = urlencode($_SERVER['REQUEST_URI']);
    header("Location: dangnhap.php?redirect=" . $redirect_url);
    exit; 
}
// 1. Kết nối CSDL
include 'connect_1.php'; 
$email_khach_hang_cookie = $_COOKIE['email'] ?? null;

if ($email_khach_hang_cookie === null) {
    die("Lỗi: Phiên đăng nhập không hợp lệ hoặc đã hết hạn. Vui lòng đăng nhập lại và thử thanh toán.");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $maSK = $_POST['maSK'];
    $maLV = $_POST['maLV'];
    $soLuong = (int)$_POST['quantity'];
    $tongTien_form = (int)$_POST['total_price']; 
    $tenKH = $_POST['customer_name'];
    $sdtKH = $_POST['customer_phone'];
    $phuongThucTT = $_POST['payment_method'];

    $trangThai_payment = 'Đã thanh toán';
    $trangThai_ve = 'Đã bán';       
    $chiTietJson = null; 

    if ($phuongThucTT === 'card') {
        $card_name = $_POST['customer_card_name'] ?? '';
        $card_number = $_POST['customer_card_number'] ?? '';
        $card_expiry = $_POST['customer_card_expiry'] ?? '';
        $card_last_four = substr(preg_replace('/[^0-9]/', '', $card_number), -4);

        $chiTiet = [
            'payment_method' => 'card',
            'card_holder_name' => $card_name,
            'card_last_four' => $card_last_four,
            'card_expiry' => $card_expiry
        ];
        
        $chiTietJson = json_encode($chiTiet); 

    } elseif ($phuongThucTT === 'momo') {
         $chiTiet = [
            'payment_method' => 'momo'
         ];
         $chiTietJson = json_encode($chiTiet);
    }
    if (empty($maSK) || empty($maLV) || $soLuong <= 0 || empty($tenKH)) {
        die("Lỗi: Vui lòng điền đầy đủ thông tin bắt buộc.");
    }

    $stmt_price_check = $conn->prepare("SELECT Gia FROM loaive WHERE MaSK = ? AND MaLoai = ?");
    if ($stmt_price_check === false) {
        die("Lỗi sql kiểm tra giá: " . $conn->error);
    }
    $stmt_price_check->bind_param("ss", $maSK, $maLV);
    $stmt_price_check->execute();
    
    $result_price = $stmt_price_check->get_result();

    if ($result_price->num_rows == 0) {
        die("Lỗi: Loại vé không hợp lệ.");
    }
    $gia_db = (int)$result_price->fetch_assoc()['Gia'];
    $stmt_price_check->close();

    $server_total = $gia_db * $soLuong;

    if ($server_total != $tongTien_form) {
        die("Lỗi: Có dấu hiệu gian lận giá. Yêu cầu bị từ chối.");
    }
        $conn->autocommit(FALSE);
    $conn->begin_transaction();

    try {
        $stmt_find_ve = $conn->prepare("SELECT MaVe FROM ve WHERE MaLoai = ? AND MaTT IS NULL LIMIT ? FOR UPDATE");
        if ($stmt_find_ve === false) {
            throw new Exception("Lỗi sql tìm vé: " . $conn->error);
        }
        $stmt_find_ve->bind_param("si", $maLV, $soLuong);
        $stmt_find_ve->execute();
        $result_ve = $stmt_find_ve->get_result();
        
        $ve_ids = []; 
        while($row = $result_ve->fetch_assoc()) {
            $ve_ids[] = $row['MaVe'];
        }
        $stmt_find_ve->close();

        if (count($ve_ids) < $soLuong) {
            throw new Exception("SOLD_OUT"); 
        }
 
        $maTT = uniqid('TT_'); 

        $sql_thanhtoan = "INSERT INTO ThanhToan (
                            MaTT, PhuongThucThanhToan, SoTien, 
                            TenNguoiThanhToan, SDT, TrangThai, 
                            Email_KH, ChiTietThanhToan
                        ) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)"; // 8 dấu ?
        
        $stmt_thanhtoan = $conn->prepare($sql_thanhtoan);
        if ($stmt_thanhtoan === false) {
            throw new Exception("Lỗi sql thanh toán: " . $conn->error);
        }
        $stmt_thanhtoan->bind_param("ssisssss",  
            $maTT,                      // 1. MaTT (s)
            $phuongThucTT,              // 2. PhuongThucThanhToan (s)
            $server_total,              // 3. SoTien (i)
            $tenKH,                     // 4. TenNguoiThanhToan (s)
            $sdtKH,                     // 5. SDT (s)
            $trangThai_payment,         // 6. TrangThai (s)
            $email_khach_hang_cookie,   // 7. Email_KH (s) - Lấy từ cookie
            $chiTietJson                // 8. ChiTietThanhToan (s) - BỔ SUNG
        );

        if (!$stmt_thanhtoan->execute()) {
            throw new Exception("Lỗi khi tạo thanh toán: " . $stmt_thanhtoan->error);
        }
        $stmt_thanhtoan->close();
        $sql_update_ve = "UPDATE ve SET MaTT = ?, TrangThai = ? WHERE MaVe = ?";
        $stmt_update_ve = $conn->prepare($sql_update_ve);
        
        foreach ($ve_ids as $maVe) {
            $stmt_update_ve->bind_param("sss", $maTT, $trangThai_ve, $maVe);
            if (!$stmt_update_ve->execute()) {
                throw new Exception("Lỗi khi cập nhật vé: " . $stmt_update_ve->error);
            }
        }
        $stmt_update_ve->close();

        $conn->commit();
        
        $stmt_tenve = $conn->prepare("SELECT TenLoai FROM loaive WHERE MaLoai = ?");
        $stmt_tenve->bind_param("s", $maLV);
        $stmt_tenve->execute();
        $tenLV = $stmt_tenve->get_result()->fetch_assoc()['TenLoai'];
        $stmt_tenve->close();

        $_SESSION['order_details'] = [
            'ticket_name' => $tenLV,
            'quantity' => $soLuong,
            'total_price' => $server_total,
            'order_id' => $maTT,
            'ticket_codes' => $ve_ids
        ];
        
        $_SESSION['customer_info'] = [
            'name' => $tenKH,
            'email' => $email_khach_hang_cookie, 
            'phone' => $sdtKH
        ];
        
        header("Location: cam_on.php");
        exit;

    }  catch (Exception $e) {
        $conn->rollback();

        if ($e->getMessage() === 'SOLD_OUT') {
            $redirectUrl = "thanhtoan.php?MaSK=" . urlencode($maSK) . "&zone=" . urlencode($maLV) . "&qty=" . urlencode($soLuong) . "&error=sold_out";
            header("Location: " . $redirectUrl);
            exit;
        }

        echo "Đã xảy ra lỗi giao dịch: " . $e->getMessage();
    
    } finally {
        $conn->autocommit(TRUE);
        $conn->close();
    }

} else {
    echo "Lỗi: Phương thức truy cập không hợp lệ.";
    header("Location: index.php");
}
?>