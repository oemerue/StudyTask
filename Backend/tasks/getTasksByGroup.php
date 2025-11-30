<?php
// backend/tasks/getTasksByGroup.php

require_once __DIR__ . '/../core/bootstrap.php';

$userId  = requireLogin();
$groupId = isset($_GET['group_id']) ? (int)$_GET['group_id'] : 0;

if ($groupId <= 0) {
    errorResponse('group_id ist erforderlich', 400);
}

// Prüfen, ob User Mitglied der Gruppe ist
$check = $pdo->prepare('
    SELECT 1
    FROM group_members
    WHERE group_id = :group_id AND user_id = :user_id
    LIMIT 1
');
$check->execute([
    ':group_id' => $groupId,
    ':user_id'  => $userId,
]);

if (!$check->fetch()) {
    errorResponse('Kein Zugriff auf diese Gruppe', 403);
}

$stmt = $pdo->prepare('
    SELECT
        t.id,
        t.group_id,
        t.title,
        t.description,
        t.status,
        t.due_at,
        t.created_by,
        t.created_at,
        t.updated_at
    FROM tasks t
    WHERE t.group_id = :group_id
    ORDER BY t.due_at IS NULL, t.due_at
');
$stmt->execute([':group_id' => $groupId]);
$tasks = $stmt->fetchAll();

jsonResponse($tasks);
