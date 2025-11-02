<?php
session_start();

// 1. Kết nối CSDL
include 'connect_1.php'; 

// 2. Kiểm tra phương thức POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. Lấy dữ liệu từ form
    $maSK = $_POST['maSK'];
    $maLV = $_POST['maLV'];
    $soLuong = (int)$_POST['quantity'];
    $tongTien_form = (int)$_POST['total_price']; // Giá từ form
    $tenKH = $_POST['customer_name'];
    $emailKH = $_POST['customer_email'];
    $sdtKH = $_POST['customer_phone'];
    $phuongThucTT = $_POST['payment_method'];
    
    // --- Trạng thái ---
    $trangThai_payment = 'Chờ thanh toán'; // Trạng thái cho bảng ThanhToan
    $trangThai_ve = 'Đã giữ chỗ';       // Trạng thái mới cho bảng Ve

    // 4. Validation cơ bản
    if (empty($maSK) || empty($maLV) || $soLuong <= 0 || empty($tenKH) || empty($emailKH)) {
        die("Lỗi: Vui lòng điền đầy đủ thông tin bắt buộc.");
    }

    // 5. *** KIỂM TRA BẢO MẬT (QUAN TRỌNG) ***
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

    // Tính toán tổng tiền thực tế trên server
    $server_total = $gia_db * $soLuong;

    // So sánh với giá từ form
    if ($server_total != $tongTien_form) {
        die("Lỗi: Có dấu hiệu gian lận giá. Yêu cầu bị từ chối.");
    }
    
    // 6. *** BẮT ĐẦU GIAO DỊCH (TRANSACTION) ***
    $conn->autocommit(FALSE);
    $conn->begin_transaction();

    try {
        // --- BƯỚC 1: TÌM VÀ KHÓA VÉ CÓ SẴN ---
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

        // Kiểm tra xem có đủ vé không
        if (count($ve_ids) < $soLuong) {
            throw new Exception("Lỗi: Không đủ vé. Số vé còn lại đã thay đổi. Vui lòng thử lại.");
        }

        // --- BƯỚC 2: INSERT VÀO BẢNG THANHTOAN (ĐÃ SỬA) ---
        
        // Lấy Email của người dùng đã đăng nhập từ SESSION
        // (File đăng nhập của bạn đã gán 'email' nên code này sẽ chạy đúng)
        $email_khach_hang_login = $_SESSION['email'] ?? null;

        $maTT = uniqid('TT_'); 

        $sql_thanhtoan = "INSERT INTO ThanhToan (
                            MaTT, PhuongThucThanhToan, SoTien, 
                            TenNguoiThanhToan, SDT, Email, TrangThai, 
                            Email_KH
                        ) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt_thanhtoan = $conn->prepare($sql_thanhtoan);
        if ($stmt_thanhtoan === false) {
            throw new Exception("Lỗi sql thanh toán: " . $conn->error);
        }

        // *** ĐÂY LÀ PHẦN SỬA QUAN TRỌNG (LỖI KIỂU DỮ LIỆU) ***
        // Sửa 'ssisssss' thành 'ssdsssss' vì SoTien là FLOAT (kiểu 'd')
        $stmt_thanhtoan->bind_param("ssdsssss",  
            $maTT,
            $phuongThucTT, 
            $server_total, // Kiểu 'd' (float/double) cho SoTien
            $tenKH,        
            $sdtKH,        
            $emailKH,      
            $trangThai_payment,
            $email_khach_hang_login // Lấy từ SESSION
        );

        if (!$stmt_thanhtoan->execute()) {
            throw new Exception("Lỗi khi tạo thanh toán: " . $stmt_thanhtoan->error);
        }
        $stmt_thanhtoan->close();

        // --- BƯỚC 3: UPDATE BẢNG 'VE' ĐỂ GÁN MaTT ---
        $sql_update_ve = "UPDATE ve SET MaTT = ?, TrangThai = ? WHERE MaVe = ?";
        $stmt_update_ve = $conn->prepare($sql_update_ve);
        
        foreach ($ve_ids as $maVe) {
            $stmt_update_ve->bind_param("sss", $maTT, $trangThai_ve, $maVe);
            if (!$stmt_update_ve->execute()) {
                throw new Exception("Lỗi khi cập nhật vé: " . $stmt_update_ve->error);
            }
        }
        $stmt_update_ve->close();

        // --- BƯỚC 4: COMMIT GIAO DỊCH ---
        $conn->commit();
        
        // Lấy tên loại vé
        $stmt_tenve = $conn->prepare("SELECT TenLoai FROM loaive WHERE MaLoai = ?");
        $stmt_tenve->bind_param("s", $maLV);
        $stmt_tenve->execute();
        $tenLV = $stmt_tenve->get_result()->fetch_assoc()['TenLoai'];
        $stmt_tenve->close();

        // LƯU SESSION VÀ CHUYỂN HƯỚNG
        $_SESSION['order_details'] = [
            'ticket_name' => $tenLV,
            'quantity' => $soLuong,
            'total_price' => $server_total,
            'order_id' => $maTT,
            'ticket_codes' => $ve_ids
        ];
        
        $_SESSION['customer_info'] = [
            'name' => $tenKH,
            'email' => $emailKH,
            'phone' => $sdtKH
        ];
        
        // 8. Chuyển hướng
        header("Location: cam_on.php");
        exit;

    } catch (Exception $e) {
        // *** XỬ LÝ LỖI ***
        $conn->rollback();
        echo "Đã xảy ra lỗi giao dịch: " . $e->getMessage();
    
    } finally {
        // Bật lại autocommit và đóng kết nối
        $conn->autocommit(TRUE);
        $conn->close();
    }

} else {
    echo "Lỗi: Phương thức truy cập không hợp lệ.";
}
?>