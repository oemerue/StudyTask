<?php
require_once __DIR__ . '/../core/bootstrap.php';

$userId = requireLogin();
$input = json_decode(file_get_contents('php://input'), true) ?: [];

$taskId = (int)($input['task_id'] ?? 0);
if ($taskId <= 0) errorResponse('task_id erforderlich', 400);

$upd = $pdo->prepare("
  UPDATE tasks
  SET responsible_user_id = NULL
  WHERE id = ? AND responsible_user_id = ?
");
$upd->execute([$taskId, $userId]);

if ($upd->rowCount() !== 1) {
  errorResponse('Du bist nicht verantwortlich für diese Aufgabe', 403);
}

jsonResponse(['ok' => true]);
