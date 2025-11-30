<?php
// backend/tasks/createTask.php

require_once __DIR__ . '/../core/bootstrap.php';

$userId = requireLogin();
$input  = getJsonInput();

$groupId     = (int)($input['group_id'] ?? 0);
$title       = trim($input['title'] ?? '');
$description = trim($input['description'] ?? '');
$dueAt       = $input['due_at'] ?? null; // z.B. '2025-11-30 23:59:00'

if ($groupId <= 0 || $title === '') {
    errorResponse('group_id und title sind erforderlich', 400);
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
    INSERT INTO tasks (group_id, title, description, status, due_at, created_by)
    VALUES (:group_id, :title, :description, :status, :due_at, :created_by)
');

$stmt->execute([
    ':group_id'   => $groupId,
    ':title'      => $title,
    ':description'=> $description !== '' ? $description : null,
    ':status'     => 'TO_DO',
    ':due_at'     => $dueAt ?: null,
    ':created_by' => $userId,
]);

$taskId = (int)$pdo->lastInsertId();

// frisch angelegte Task zurückgeben
$stmt = $pdo->prepare('
    SELECT id, group_id, title, description, status, due_at, created_by, created_at, updated_at
    FROM tasks
    WHERE id = ?
');
$stmt->execute([$taskId]);
$task = $stmt->fetch();

jsonResponse($task, 201);
