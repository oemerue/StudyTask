<?php
require_once __DIR__ . '/../core/bootstrap.php';

$userId = requireLogin();
$input = json_decode(file_get_contents('php://input'), true) ?: [];

$taskId = (int)($input['task_id'] ?? 0);
$status = strtoupper(trim($input['status'] ?? ''));

$allowed = ['OPEN', 'IN_PROGRESS', 'DONE'];

if ($taskId <= 0) {
  errorResponse('task_id ist erforderlich', 400);
}
if (!in_array($status, $allowed, true)) {
  errorResponse('Ungültiger Status', 400);
}

// Zugriff: User muss Mitglied der Gruppe sein
$chk = $pdo->prepare("
  SELECT t.group_id
  FROM tasks t
  JOIN group_members gm ON gm.group_id = t.group_id
  WHERE t.id = :tid AND gm.user_id = :uid
");
$chk->execute([
  ':tid' => $taskId,
  ':uid' => $userId
]);

if (!$chk->fetchColumn()) {
  errorResponse('Kein Zugriff auf diese Aufgabe', 403);
}

$upd = $pdo->prepare("
  UPDATE tasks
  SET status = :status
  WHERE id = :tid
");
$upd->execute([
  ':status' => $status,
  ':tid' => $taskId
]);

jsonResponse(['ok' => true]);
