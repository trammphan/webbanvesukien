<?php
session_start();

// 1. Kết nối CSDL
// (Đảm bảo đường dẫn này chính xác)
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

    // 4. Validation cơ bản (giữ nguyên)
    if (empty($maSK) || empty($maLV) || $soLuong <= 0 || empty($tenKH) || empty($emailKH)) {
        die("Lỗi: Vui lòng điền đầy đủ thông tin bắt buộc.");
    }

    // 5. *** KIỂM TRA BẢO MẬT (QUAN TRỌNG) ***
    // Lấy lại giá vé từ CSDL để xác thực, không tin tưởng giá từ form
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
        // Ghi log lại trường hợp gian lận nếu cần
        die("Lỗi: Có dấu hiệu gian lận giá. Yêu cầu bị từ chối.");
    }
    // Từ giờ, chúng ta sẽ chỉ sử dụng $server_total
    
    // 6. *** BẮT ĐẦU GIAO DỊCH (TRANSACTION) ***
    // Điều này đảm bảo tất cả các lệnh (INSERT và UPDATE) cùng thành công hoặc cùng thất bại
    $conn->autocommit(FALSE);
    $conn->begin_transaction();

    try {
        // --- BƯỚC 1: TÌM VÀ KHÓA VÉ CÓ SẴN ---
        // Tìm $soLuong vé thuộc loại $maLV và chưa được ai thanh toán (MaTT IS NULL)
        // 'FOR UPDATE' sẽ khóa các hàng này lại, ngăn người khác mua chúng
        $stmt_find_ve = $conn->prepare("SELECT MaVe FROM ve WHERE MaLoai = ? AND MaTT IS NULL LIMIT ? FOR UPDATE");
        if ($stmt_find_ve === false) {
            throw new Exception("Lỗi sql tìm vé: " . $conn->error);
        }
        $stmt_find_ve->bind_param("si", $maLV, $soLuong);
        $stmt_find_ve->execute();
        $result_ve = $stmt_find_ve->get_result();
        
        $ve_ids = []; // Mảng chứa các MaVe sẽ được cập nhật
        while($row = $result_ve->fetch_assoc()) {
            $ve_ids[] = $row['MaVe'];
        }
        $stmt_find_ve->close();

        // Kiểm tra xem có đủ vé không
        if (count($ve_ids) < $soLuong) {
            // Nếu không đủ, ném lỗi để rollback
            throw new Exception("Lỗi: Không đủ vé. Số vé còn lại đã thay đổi. Vui lòng thử lại.");
        }

       // --- BƯỚC 2: INSERT VÀO BẢNG THANHTOAN (ĐÃ SỬA ĐỂ LƯU Email_KH) ---
        
        // Lấy Email của người dùng đã đăng nhập từ SESSION
        $email_khach_hang_login = $_SESSION['email'] ?? null;

        $maTT = uniqid('TT_'); // Mã này đã đúng với CSDL (varchar 20)

        // SỬA SQL: Thêm cột "Email_KH" vào câu lệnh INSERT
        $sql_thanhtoan = "INSERT INTO ThanhToan (
                            MaTT, PhuongThucThanhToan, SoTien, 
                            TenNguoiThanhToan, SDT, Email, TrangThai, 
                            Email_KH  /* <-- CỘT MỚI */
                        ) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)"; /* <-- Thêm 1 dấu ? */
        
        $stmt_thanhtoan = $conn->prepare($sql_thanhtoan);
        if ($stmt_thanhtoan === false) {
            throw new Exception("Lỗi sql thanh toán: " . $conn->error);
        }

        // SỬA BIND_PARAM: Thêm 's' (cho Email_KH) vào cuối chuỗi
        $stmt_thanhtoan->bind_param("ssisssss",  /* <-- Sửa "ssissss" thành "ssisssss" */
            $maTT,
            $phuongThucTT, 
            $server_total, // Dùng tổng tiền đã xác thực
            $tenKH,        // Tên người nhận vé (từ form)
            $sdtKH,        // SĐT người nhận vé (từ form)
            $emailKH,      // Email người nhận vé (từ form)
            $trangThai_payment,
            $email_khach_hang_login // <-- Biến mới: Email từ SESSION
        );

        if (!$stmt_thanhtoan->execute()) {
            throw new Exception("Lỗi khi tạo thanh toán: " . $stmt_thanhtoan->error);
        }
        $stmt_thanhtoan->close();
        // --- BƯỚC 3: UPDATE BẢNG 'VE' ĐỂ GÁN MaTT ---
        // Gán MaTT mới và Trạng Thái mới cho các vé đã tìm thấy ở BƯỚC 1
        $sql_update_ve = "UPDATE ve SET MaTT = ?, TrangThai = ? WHERE MaVe = ?";
        $stmt_update_ve = $conn->prepare($sql_update_ve);
        
        foreach ($ve_ids as $maVe) {
            $stmt_update_ve->bind_param("sss", $maTT, $trangThai_ve, $maVe);
            if (!$stmt_update_ve->execute()) {
                // Nếu 1 vé bị lỗi, ném lỗi để rollback tất cả
                throw new Exception("Lỗi khi cập nhật vé: " . $stmt_update_ve->error);
            }
        }
        $stmt_update_ve->close();

        // --- BƯỚC 4: COMMIT GIAO DỊCH ---
        $conn->commit();
        // Nếu tất cả các bước trên thành công, lưu thay đổi vào CSDL
        $stmt_tenve = $conn->prepare("SELECT TenLoai FROM loaive WHERE MaLoai = ?");
        $stmt_tenve->bind_param("s", $maLV);
        $stmt_tenve->execute();
        
        // *** SỬA LỖI 5: Dùng đúng khóa mảng (TenLoai) ***
        $tenLV = $stmt_tenve->get_result()->fetch_assoc()['TenLoai'];
        $stmt_tenve->close();


        // LƯU SESSION VÀ CHUYỂN HƯỚNG (SAU KHI COMMIT THÀNH CÔNG)

        // 1. Lưu thông tin đơn hàng vào Session
        $_SESSION['order_details'] = [
            'ticket_name' => $tenLV,
            'quantity' => $soLuong,
            'total_price' => $server_total, // Dùng giá đúng
            'order_id' => $maTT, // Dùng MaTT thật
            'ticket_codes' => $ve_ids // <-- THÊM MỚI: Lưu danh sách mã vé
        ];
        
        // 2. Lưu thông tin khách hàng vào Session
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
        // Nếu có bất kỳ lỗi nào xảy ra (ở BƯỚC 1, 2, hoặc 3), hoàn tác tất cả thay đổi
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

