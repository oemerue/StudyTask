<?php
require_once __DIR__ . '/../core/bootstrap.php';

$userId = requireLogin();

$start = $_GET['start'] ?? null;
$end   = $_GET['end'] ?? null;

if (!$start || !$end) {
  errorResponse('start und end erforderlich', 400);
}

$stmt = $pdo->prepare("
  SELECT
    t.id,
    t.title,
    t.due_at,
    t.status,
    t.group_id,
    g.name AS group_name
  FROM tasks t
  JOIN group_members gm ON gm.group_id = t.group_id
  JOIN groups g ON g.id = t.group_id
  WHERE gm.user_id = ?
    AND t.due_at >= ?
    AND t.due_at < ?
  ORDER BY t.due_at ASC
");
$stmt->execute([$userId, $start, $end]);

jsonResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
