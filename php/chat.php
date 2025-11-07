<?php

// ---- BẢO MẬT (QUAN TRỌNG) ----
require_once 'config.php'; // Tệp này chứa $GEMINI_API_KEY và $db_host...

// === SỬA LỖI Ở ĐÂY ===
// Dùng $GEMINI_API_KEY (từ config.php) thay vì $API_KEY
$API_URL = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $GEMINI_API_KEY;

// Báo cho script.js biết rằng chúng ta sẽ trả về JSON (với UTF-8)
header('Content-Type: application/json; charset=utf-8');

// 1. Lấy dữ liệu (chatHistory) mà script.js gửi lên
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (is_null($data) || !isset($data['contents'])) {
    echo json_encode(['error' => 'Lỗi: Không nhận được nội dung trò chuyện.']);
    exit;
}

$live_data_string = "";
try {
    // Sử dụng các biến $db_... từ config.php
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $userMessage = end($data['contents'])['parts'][0]['text'];
    $searchTerm = "%" . $userMessage . "%";

    $stmt = $pdo->prepare("
        SELECT 
            s.TenSK, s.Tgian, s.mota, d.TenTinh, l.TenLoaiSK
        FROM sukien AS s
        JOIN diadiem AS d ON s.MaDD = d.MaDD
        JOIN loaisk AS l ON s.MaLSK = l.MaloaiSK
        WHERE s.TenSK LIKE ? OR s.mota LIKE ?
        ORDER BY s.luot_truycap DESC
        LIMIT 3
    ");
    $stmt->execute([$searchTerm, $searchTerm]);
    $live_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // --- LOGIC TRẢ LỜI "KHÔNG TÌM THẤY" ---
    if (count($live_events) > 0) {
        // 2a. TÌM THẤY: Xây dựng chuỗi dữ liệu "sống" để gửi cho Google
        $live_data_string = "Thông tin bổ sung từ cơ sở dữ liệu (dữ liệu 'sống' mà bot có thể dùng để trả lời):\n\n";
        foreach ($live_events as $event) {
            $live_data_string .= "- Sự kiện: " . $event['TenSK'] . "\n";
            $live_data_string .= "  Ngày: " . $event['Tgian'] . "\n";
            $live_data_string .= "  Địa điểm: " . $event['TenTinh'] . "\n";
            $live_data_string .= "  Loại: " . $event['TenLoaiSK'] . "\n\n";
        }
    } else {
        // 2b. KHÔNG TÌM THẤY: PHP tự trả lời, không cần gọi Google
        
        $notFoundResponse = "Rất tiếc, tôi không tìm thấy sự kiện nào liên quan đến '" . htmlspecialchars($userMessage) . "'. Bạn vui lòng thử tìm kiếm với tên sự kiện khác nhé!";
        
        // Gửi câu trả lời này về và KẾT THÚC script
        echo json_encode([
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            ['text' => $notFoundResponse]
                        ]
                    ]
                ]
            ]
        ]);
        exit; // Dừng script ngay lập tức
    }
    // --- KẾT THÚC LOGIC ---

} catch (PDOException $e) {
    // Nếu lỗi CSDL, trả về lỗi này
    $dbErrorResponse = "Lỗi hệ thống: Không thể kết nối cơ sở dữ liệu. " . $e->getMessage();
     echo json_encode([
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            ['text' => $dbErrorResponse]
                        ]
                    ]
                ]
            ]
        ]);
    exit;
}

// 3. (CHỈ CHẠY NẾU TÌM THẤY SỰ KIỆN) "Nhồi" dữ liệu "sống" vào
$lastUserMessage = array_pop($data['contents']);
$data['contents'][] = [
    'role' => 'model',
    'parts' => [['text' => $live_data_string]]
];
$data['contents'][] = $lastUserMessage;


// 4. (CHỈ CHẠY NẾU TÌM THẤY SỰ KIỆN) Gửi yêu cầu đến Google API
$googleApiRequest = [
    'contents' => $data['contents']
];

$ch = curl_init($API_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($googleApiRequest));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

// 5. Trả kết quả về cho script.js
if ($curl_error) {
    echo json_encode(['error' => 'Lỗi cURL: ' . $curl_error]);
} elseif ($httpcode != 200) {
    echo json_encode(['error' => 'Lỗi API Google (HTTP ' . $httpcode . ')', 'details' => json_decode($response)]);
} else {
    echo $response;
}

// === HẾT TỆP CHAT.PHP SẠCH ===
?>