<?php
require_once __DIR__ . '/../core/bootstrap.php';

$userId = requireLogin();
$input = json_decode(file_get_contents('php://input'), true) ?: [];

$groupId = (int)($input['group_id'] ?? 0);
if ($groupId<=0) errorResponse('group_id erforderlich', 400);

$roleStmt = $pdo->prepare("SELECT role FROM group_members WHERE group_id=? AND user_id=?");
$roleStmt->execute([$groupId, $userId]);
$role = $roleStmt->fetchColumn();
if (!$role) errorResponse('Du bist nicht Mitglied', 403);

if ($role === 'ADMIN') {
  $cnt = $pdo->prepare("SELECT COUNT(*) FROM group_members WHERE group_id=? AND user_id<>?");
  $cnt->execute([$groupId, $userId]);
  $others = (int)$cnt->fetchColumn();

  if ($others > 0) {
    errorResponse('Owner muss Ownership übertragen bevor er die Gruppe verlässt', 409);
  }

  // Owner ist allein -> Gruppe auflösen
  $pdo->beginTransaction();
  $pdo->prepare("DELETE FROM group_members WHERE group_id=?")->execute([$groupId]);
  $pdo->prepare("DELETE FROM `groups` WHERE id=?")->execute([$groupId]);
  $pdo->commit();

  jsonResponse(['ok'=>true, 'group_deleted'=>true]);
  exit;
}

// MEMBER leave
$pdo->prepare("DELETE FROM group_members WHERE group_id=? AND user_id=?")->execute([$groupId, $userId]);
jsonResponse(['ok'=>true]);
