<?php
require_once __DIR__ . '/db_connect.php';

header('Content-Type: application/json; charset=utf-8');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q === '' || mb_strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

try {
    $pattern = '%' . $q . '%';

    // Prepared statements cho 2 bảng, sau đó gộp kết quả và loại trùng
    $stmtEvent = $conn->prepare("SELECT TenSK AS label FROM sukien WHERE TenSK LIKE ? LIMIT 10");
    $stmtEvent->bind_param('s', $pattern);
    $stmtEvent->execute();
    $resEvent = $stmtEvent->get_result();

    $stmtPlace = $conn->prepare("SELECT TenTinh AS label FROM diadiem WHERE TenTinh LIKE ? LIMIT 10");
    $stmtPlace->bind_param('s', $pattern);
    $stmtPlace->execute();
    $resPlace = $stmtPlace->get_result();

    $labels = [];
    if ($resEvent) {
        while ($row = $resEvent->fetch_assoc()) {
            if (!empty($row['label'])) {
                $labels[] = $row['label'];
            }
        }
    }
    if ($resPlace) {
        while ($row = $resPlace->fetch_assoc()) {
            if (!empty($row['label'])) {
                $labels[] = $row['label'];
            }
        }
    }

    // Loại bỏ trùng, cắt tối đa 10
    $labels = array_values(array_unique($labels));
    $labels = array_slice($labels, 0, 10);

    echo json_encode($labels, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'server_error']);
} finally {
    // Không đóng $conn ở đây để không ảnh hưởng vòng đời nếu endpoint dùng lại
}
