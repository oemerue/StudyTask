<?php
// session_start() ENTFERNEN!
// bootstrap.php startet die Session bereits

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Nicht eingeloggt']);
    exit;
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'ADMIN') {
    http_response_code(403);
    echo json_encode(['error' => 'Kein Admin-Zugriff']);
    exit;
}
