<?php
require_once __DIR__ . '/../core/bootstrap.php';

$userId = requireLogin();
$input = json_decode(file_get_contents('php://input'), true) ?: [];
$taskId = (int)($input['task_id'] ?? 0);

if ($taskId <= 0) errorResponse('task_id ist erforderlich', 400);

// Zugriff prüfen
$chk = $pdo->prepare('
  SELECT t.group_id
  FROM tasks t
  JOIN group_members gm ON gm.group_id = t.group_id
  WHERE t.id=:tid AND gm.user_id=:uid
');
$chk->execute([':tid'=>$taskId, ':uid'=>$userId]);
if (!$chk->fetchColumn()) errorResponse('Kein Zugriff', 403);

$del = $pdo->prepare('DELETE FROM tasks WHERE id=:tid');
$del->execute([':tid'=>$taskId]);

jsonResponse(['ok'=>true]);
