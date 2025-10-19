<?php
    include 'connect.php';

    $conditions = [];
    if (!empty($_GET['diadiem'])) {
        $conditions[] = "MaDD = '" . $conn->real_escape_string($_GET['diadiem']) . "'";

    }
    if (!empty($_GET['loai_sukien'])) {
        $conditions[] = "MaLSK = '" . $conn->real_escape_string($_GET['loai_sukien']) . "'";

    }
    $sql = "SELECT * FROM sukien";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    $result = $conn->query($sql);
    $event = [];
    while ($row = $result->fetch_assoc()) {
        $event[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($event);
?>