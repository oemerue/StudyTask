<?php
require_once __DIR__ . '/../core/bootstrap.php';

$userId = requireLogin();

$start = $_GET['start'] ?? null;
$end   = $_GET['end'] ?? null;

if (!$start || !$end) {
    errorResponse('Zeitraum fehlt', 400);
}

$stmt = $pdo->prepare("
    SELECT
        t.id,
        t.group_id,
        g.name AS group_name,
        t.title,
        t.description,
        t.status,
        t.due_at
    FROM tasks t
    JOIN groups g ON g.id = t.group_id
    JOIN group_members gm
      ON gm.group_id = t.group_id
     AND gm.user_id = ?
    WHERE t.due_at BETWEEN ? AND ?
    ORDER BY t.due_at ASC
");

$stmt->execute([$userId, $start, $end]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

jsonResponse($tasks);
