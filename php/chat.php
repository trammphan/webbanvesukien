<?php

// ---- BẢO MẬT (QUAN TRỌNG) ----
require_once 'config.php'; 

$API_URL = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $GEMINI_API_KEY;

header('Content-Type: application/json; charset=utf-8');

// 2. HÀM LẤY DỮ LIỆU TỪ DATABASE
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
                lv.TenLoai, 
                lv.Gia
            FROM sukien sk
            JOIN diadiem dd ON sk.MaDD = dd.MaDD
            JOIN loaisk lsk ON sk.MaLSK = lsk.MaloaiSK
            JOIN loaive lv ON sk.MaSK = lv.MaSK
            WHERE sk.Tgian >= CURDATE() 
            ORDER BY sk.Tgian ASC;
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($events)) {
            return "Hiện tại không có dữ liệu sự kiện nào trong hệ thống.";
        }

        $dataString = "Dưới đây là danh sách các sự kiện và vé ĐANG MỞ BÁN trong hệ thống (Dữ liệu thực tế):\n";
        foreach ($events as $ev) {
            $giaVeVND = number_format($ev['Gia'], 0, ',', '.'); 
            $dataString .= "- Sự kiện: {$ev['TenSK']} | Ngày: {$ev['Tgian']} | Tại: {$ev['TenTinh']} | Loại vé: {$ev['TenLoai']} - Giá: {$giaVeVND} VND\n";
        }
        return $dataString;

    } catch (PDOException $e) {
        return "Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage();
    }
}

// 3. XỬ LÝ YÊU CẦU
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (is_null($data) || !isset($data['contents'])) {
    echo json_encode(['error' => 'Không nhận được nội dung tin nhắn.']);
    exit;
}

// Lấy dữ liệu TRƯỚC
$databaseContext = getEventsData($db_host, $db_name, $db_user, $db_pass);

// CẬP NHẬT SYSTEM INSTRUCTION (THÊM PHẦN GỢI Ý)
$systemInstruction = [
    'role' => 'user', 
    'parts' => [
        ['text' => "Bạn là trợ lý ảo tư vấn sự kiện (Chatbot).
        
        Dữ liệu hệ thống (Database):
        " . $databaseContext . "
        
        QUY TẮC ỨNG XỬ & TRẢ LỜI (BẮT BUỘC):
        
        1. KHI HIỂN THỊ DANH SÁCH SỰ KIỆN:
           - Chỉ liệt kê Tên Sự Kiện và Ngày diễn ra (ngắn gọn).
           - KHÔNG liệt kê giá vé ngay lập tức.
           - QUAN TRỌNG: Kết thúc câu trả lời bằng một câu hỏi gợi mở để người dùng tương tác tiếp.
           - Ví dụ câu kết: 'Bạn quan tâm đến sự kiện nào để mình báo giá chi tiết ạ?' hoặc 'Bạn muốn biết thêm về show nào không?'
        
        2. KHI BÁO GIÁ VÉ:
           - Chỉ báo giá khi người dùng hỏi cụ thể về một sự kiện.
           - Liệt kê rõ các hạng vé và giá tiền.
           - Kết thúc bằng hướng dẫn đặt vé: 'Để đặt vé, bạn hãy nhấn vào sự kiện trên website và chọn Mua Vé nhé.'
        
        3. GIỚI HẠN CHỨC NĂNG:
           - Bạn KHÔNG có chức năng đặt vé trực tiếp trong khung chat.
           - Nếu khách muốn đặt, hãy hướng dẫn họ thao tác trên website.
        
        4. DỮ LIỆU:
           - Trả lời dựa trên dữ liệu đã cung cấp. Nếu không tìm thấy, hãy nói 'Hiện chưa có sự kiện nào theo yêu cầu của bạn'.
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