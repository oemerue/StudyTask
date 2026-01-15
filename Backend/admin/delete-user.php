<?php
require_once __DIR__ . '/../core/bootstrap.php';
require_once __DIR__ . '/_admin-only.php';

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['user_id'] ?? null;

if (!$userId) {
    http_response_code(400);
    echo json_encode(['error' => 'User-ID fehlt']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Tasks
    $pdo->prepare("DELETE FROM tasks WHERE responsible_user_id = ?")->execute([$userId]);
    $pdo->prepare("DELETE FROM tasks WHERE created_by = ?")->execute([$userId]);

    // Gruppen
    $pdo->prepare("DELETE FROM group_members WHERE user_id = ?")->execute([$userId]);

    // User
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$userId]);

    $pdo->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
