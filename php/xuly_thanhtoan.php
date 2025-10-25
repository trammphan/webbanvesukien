<?php
// THÊM MỚI: Luôn bắt đầu session ở đầu tệp
session_start();

// 1. Kết nối CSDL
include '../sukien/connect.php';

// 2. Kiểm tra phương thức POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. Lấy dữ liệu từ form
    $maSK = $_POST['maSK'];
    $maLV = $_POST['maLV'];
    $soLuong = $_POST['quantity'];
    $tongTien = $_POST['total_price'];
    $tenKH = $_POST['customer_name'];
    $emailKH = $_POST['customer_email'];
    $sdtKH = $_POST['customer_phone'];
    $phuongThucTT = $_POST['payment_method'];
    $trangThai = 'Chờ thanh toán';

    // (Validation dữ liệu... giữ nguyên)
    if (empty($maSK) || empty($maLV) || empty($soLuong) || empty($tenKH) || empty($emailKH)) {
        die("Lỗi: Vui lòng điền đầy đủ thông tin bắt buộc.");
    }

    try {
        // 4. Chuẩn bị câu lệnh SQL cho bảng `ThanhToan`
        $sql = "INSERT INTO ThanhToan (MaSK, MaLV, SoLuong, SoTien, TenKhachHang, EmailKhachHang, SDTKhachHang, PhuongThucThanhToan, TrangThaiTT) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiisssss", 
            $maSK, $maLV, $soLuong, $tongTien, $tenKH, 
            $emailKH, $sdtKH, $phuongThucTT, $trangThai
        );

        // 5. Thực thi
        if ($stmt->execute()) {
            
            // === PHẦN THÊM MỚI QUAN TRỌNG ===
            
            // Lấy MaTT (Mã Thanh Toán) vừa được tạo
            $maTT = $stmt->insert_id; 
            
            // Lấy Tên Loại Vé (TenLV) từ MaLV để hiển thị trên trang cảm ơn
            $stmt_tenve = $conn->prepare("SELECT TenLV FROM loaive WHERE MLV = ?");
            $stmt_tenve->bind_param("s", $maLV);
            $stmt_tenve->execute();
            $tenLV = $stmt_tenve->get_result()->fetch_assoc()['TenLV'];
            $stmt_tenve->close();

            // 1. Lưu thông tin đơn hàng vào Session
            $_SESSION['order_details'] = [
                'ticket_name' => $tenLV,
                'quantity' => $soLuong,
                'total_price' => $tongTien,
                'order_id' => $maTT // Dùng MaTT thật
            ];
            
            // 2. Lưu thông tin khách hàng vào Session
            $_SESSION['customer_info'] = [
                'name' => $tenKH,
                'email' => $emailKH,
                'phone' => $sdtKH
            ];
            
            // === KẾT THÚC PHẦN THÊM MỚI ===

            // 6. Chuyển hướng (giữ nguyên)
            header("Location: cam-on.php");
            exit;
            
        } else {
            echo "Lỗi: Không thể lưu thanh toán. " . $stmt->error;
        }
        $stmt->close();

    } catch (Exception $e) {
        echo "Đã xảy ra lỗi: " . $e->getMessage();
    }
    $conn->close();

} else {
    echo "Lỗi: Phương thức truy cập không hợp lệ.";
}
?>