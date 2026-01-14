<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/_admin-only.php';

$data = json_decode(file_get_contents('php://input'), true);

$userId = $data['user_id'] ?? null;
$newPassword = $data['new_password'] ?? '';

if (!$userId || trim($newPassword) === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Ungültige Daten']);
    exit;
}

if (strlen($newPassword) < 8) {
    http_response_code(400);
    echo json_encode(['error' => 'Passwort muss mindestens 8 Zeichen haben']);
    exit;
}

$hash = password_hash($newPassword, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
$stmt->execute([$hash, $userId]);

echo json_encode(['success' => true]);
