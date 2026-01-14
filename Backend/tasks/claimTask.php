<?php
require_once __DIR__ . '/../core/bootstrap.php';

$userId = requireLogin();

$input = json_decode(file_get_contents('php://input'), true) ?: [];
$taskId = (int)($input['task_id'] ?? 0);

if ($taskId <= 0) {
    errorResponse('task_id fehlt', 400);
}

// Prüfen, ob User Mitglied der Gruppe ist
$check = $pdo->prepare("
    SELECT 1
    FROM tasks t
    JOIN group_members gm ON gm.group_id = t.group_id
    WHERE t.id = ? AND gm.user_id = ?
");
$check->execute([$taskId, $userId]);

if (!$check->fetchColumn()) {
    errorResponse('Kein Zugriff', 403);
}

// Aufgabe claimen → responsible_user_id setzen
$stmt = $pdo->prepare("
    UPDATE tasks
    SET responsible_user_id = ?
    WHERE id = ?
");
$stmt->execute([$userId, $taskId]);

jsonResponse([
    'ok' => true
]);
