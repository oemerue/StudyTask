<?php
require_once __DIR__ . '/../core/bootstrap.php';

$userId = requireLogin();
$input = json_decode(file_get_contents('php://input'), true) ?: [];

$groupId = (int)($input['group_id'] ?? 0);
$targetUserId = (int)($input['user_id'] ?? 0);

if ($groupId<=0 || $targetUserId<=0) errorResponse('group_id und user_id erforderlich', 400);

// owner check
$role = $pdo->prepare("SELECT role FROM group_members WHERE group_id=? AND user_id=?");
$role->execute([$groupId, $userId]);
$myRole = $role->fetchColumn();
if ($myRole !== 'ADMIN') errorResponse('Nur Owner darf Mitglieder entfernen', 403);

if ($targetUserId === $userId) {
  errorResponse('Owner kann sich nicht selbst kicken', 400);
}

$targetRole = $pdo->prepare("SELECT role FROM group_members WHERE group_id=? AND user_id=?");
$targetRole->execute([$groupId, $targetUserId]);
$tr = $targetRole->fetchColumn();
if (!$tr) errorResponse('Mitglied nicht gefunden', 404);
if ($tr === 'ADMIN') errorResponse('Owner kann nicht entfernt werden', 409);

$del = $pdo->prepare("DELETE FROM group_members WHERE group_id=? AND user_id=?");
$del->execute([$groupId, $targetUserId]);

jsonResponse(['ok'=>true]);
