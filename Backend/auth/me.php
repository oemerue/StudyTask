<?php
require_once __DIR__ . '/../core/bootstrap.php';

$userId = getCurrentUserId();
if (!$userId) {
    errorResponse('Nicht eingeloggt', 401);
}

$stmt = $pdo->prepare('
    SELECT id, display_name, email, role
    FROM users
    WHERE id = ?
    LIMIT 1
');
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    errorResponse('User nicht gefunden', 401);
}

jsonResponse($user);
