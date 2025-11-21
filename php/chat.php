<?php

// ---- BẢO MẬT ----
require_once 'config.php'; 

$API_URL = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $GEMINI_API_KEY;

header('Content-Type: application/json; charset=utf-8');

// 2. HÀM LẤY DỮ LIỆU TỪ DATABASE
// function getEventsData($host, $dbname, $user, $pass) {
//     try {
//         $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
//         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//         $sql = "
//             SELECT 
//                 sk.TenSK, 
//                 sk.Tgian, 
//                 dd.TenTinh, 
//                 lsk.TenLoaiSK AS LoaiHinh, 
//                 lv.TenLoai, 
//                 lv.Gia
//             FROM sukien sk
//             JOIN diadiem dd ON sk.MaDD = dd.MaDD
//             JOIN loaisk lsk ON sk.MaLSK = lsk.MaloaiSK
//             JOIN loaive lv ON sk.MaSK = lv.MaSK
//             WHERE sk.Tgian >= CURDATE() 
//             ORDER BY sk.Tgian ASC;
//         ";

//         $stmt = $pdo->prepare($sql);
//         $stmt->execute();
//         $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

//         if (empty($events)) {
//             return "Hiện tại không có dữ liệu sự kiện nào trong hệ thống.";
//         }

//         $dataString = "Dưới đây là danh sách các sự kiện và vé ĐANG MỞ BÁN trong hệ thống (Dữ liệu thực tế):\n";
//         foreach ($events as $ev) {
//             $giaVeVND = number_format($ev['Gia'], 0, ',', '.'); 
            
//             // --- CẬP NHẬT QUAN TRỌNG Ở ĐÂY ---
//             // Thêm trường "Loại hình" vào chuỗi để AI đọc được
//             $dataString .= "- Sự kiện: {$ev['TenSK']} | Loại hình: {$ev['LoaiHinh']} | Ngày: {$ev['Tgian']} | Tại: {$ev['TenTinh']} | Loại vé: {$ev['TenLoai']} - Giá: {$giaVeVND} VND\n";
//         }
//         return $dataString;

//     } catch (PDOException $e) {
//         return "Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage();
//     }
// }
function getEventsData($host, $dbname, $user, $pass) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "
            SELECT 
                sk.TenSK, 
                sk.Tgian, 
                dd.TenTinh, 
                lsk.TenLoaiSK AS LoaiHinh,
                GROUP_CONCAT(
                    CONCAT(IFNULL(lv.TenLoai, 'Chưa có vé'), ': ', IFNULL(FORMAT(lv.Gia, 0), 'Liên hệ')) 
                    SEPARATOR ', '
                ) as DanhSachVe
            FROM sukien sk
            JOIN diadiem dd ON sk.MaDD = dd.MaDD
            JOIN loaisk lsk ON sk.MaLSK = lsk.MaloaiSK
            LEFT JOIN loaive lv ON sk.MaSK = lv.MaSK  -- ĐỔI THÀNH LEFT JOIN
            -- WHERE sk.Tgian >= CURDATE()  <-- Tạm thời comment dòng này lại để test xem có phải do ngày tháng không
            GROUP BY sk.MaSK, sk.TenSK, sk.Tgian, dd.TenTinh, lsk.TenLoaiSK
            ORDER BY sk.Tgian ASC;
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($events)) {
            return "Hệ thống ghi nhận: Hiện chưa có dữ liệu sự kiện nào.";
        }

        $dataString = "Dữ liệu sự kiện thực tế từ Database:\n";
        foreach ($events as $ev) {
            // Xử lý hiển thị ngày
            $dataString .= "- Sự kiện: {$ev['TenSK']} \n";
            $dataString .= "  + Phân loại: {$ev['LoaiHinh']} | Tại: {$ev['TenTinh']} | Ngày: {$ev['Tgian']}\n";
            $dataString .= "  + Bảng giá vé: {$ev['DanhSachVe']}\n";
            $dataString .= "------------------------------------------------\n";
        }
        return $dataString;

    } catch (PDOException $e) {
        return "Lỗi truy vấn DB: " . $e->getMessage();
    }
}
// 3. XỬ LÝ YÊU CẦU
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (is_null($data) || !isset($data['contents'])) {
    echo json_encode(['error' => 'Không nhận được nội dung tin nhắn.']);
    exit;
}

// Lấy dữ liệu
$databaseContext = getEventsData($db_host, $db_name, $db_user, $db_pass);

// CẬP NHẬT SYSTEM INSTRUCTION (Thêm quy tắc phân loại)
$systemInstruction = [
    'role' => 'user', 
    'parts' => [
        ['text' => "Bạn là trợ lý ảo tư vấn sự kiện (Chatbot).
        
        Dữ liệu hệ thống (Database):
        " . $databaseContext . "
        
        QUY TẮC ỨNG XỬ & TRẢ LỜI (BẮT BUỘC):
        
        1. KHI NGƯỜI DÙNG HỎI THEO THỂ LOẠI (Liveshow, Concert, Festival...):
           - Hãy kiểm tra kỹ trường 'Loại hình' trong dữ liệu.
           - Chỉ liệt kê các sự kiện có 'Loại hình' khớp hoặc gần đúng với yêu cầu (ví dụ: Liveshow, Concert, Nhạc hội).
           - Nếu không có loại sự kiện đó, hãy trả lời thật thà: 'Hiện tại hệ thống chưa có sự kiện thuộc thể loại [tên thể loại] nào ạ.'
        
        2. KHI HIỂN THỊ DANH SÁCH:
           - Chỉ liệt kê Tên Sự Kiện, Ngày diễn ra và Loại hình.
           - Kết thúc bằng câu hỏi gợi mở: 'Bạn quan tâm đến sự kiện nào để mình báo giá chi tiết ạ?'
        
        3. KHI BÁO GIÁ VÉ:
           - Chỉ báo giá khi được hỏi chi tiết.
           - Kết thúc bằng hướng dẫn: 'Để đặt vé, bạn hãy nhấn vào sự kiện trên website và chọn Mua Vé nhé.'
        
        4. GIỚI HẠN CHỨC NĂNG:
           - Bạn KHÔNG có chức năng đặt vé trực tiếp.
           - Trả lời dựa trên dữ liệu đã cung cấp.
        "]
    ]
];

$googleApiRequest = [
    'system_instruction' => $systemInstruction,
    'contents' => $data['contents']
];

$ch = curl_init($API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($googleApiRequest));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

if ($curl_error) {
    echo json_encode(['error' => 'Lỗi cURL: ' . $curl_error]);
} elseif ($httpcode != 200) {
    echo json_encode(['error' => 'Lỗi API Google: ' . $httpcode, 'details' => json_decode($response)]);
} else {
    echo $response;
}
?>