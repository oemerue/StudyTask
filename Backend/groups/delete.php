<?php
require_once __DIR__ . '/../core/bootstrap.php';

$userId = requireLogin();
$input = json_decode(file_get_contents('php://input'), true) ?: [];

$groupId = (int)($input['group_id'] ?? 0);
if ($groupId <= 0) errorResponse('group_id erforderlich', 400);

$roleStmt = $pdo->prepare("SELECT role FROM group_members WHERE group_id=? AND user_id=?");
$roleStmt->execute([$groupId, $userId]);
$myRole = $roleStmt->fetchColumn();

if ($myRole !== 'ADMIN') errorResponse('Nur der Admin kann die Gruppe löschen', 403);

$pdo->beginTransaction();
try {
  $pdo->prepare("DELETE FROM tasks WHERE group_id=?")->execute([$groupId]);
  $pdo->prepare("DELETE FROM group_members WHERE group_id=?")->execute([$groupId]);
  $pdo->prepare("DELETE FROM `groups` WHERE id=?")->execute([$groupId]);
  $pdo->commit();
  jsonResponse(['ok' => true]);
} catch (Throwable $e) {
  $pdo->rollBack();
  errorResponse('Gruppe konnte nicht gelöscht werden', 500);
}
