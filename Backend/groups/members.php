<?php
require_once __DIR__ . '/../core/bootstrap.php';

$userId = requireLogin();
$groupId = (int)($_GET['group_id'] ?? 0);
if ($groupId <= 0) errorResponse('group_id erforderlich', 400);

// muss Mitglied sein
$chk = $pdo->prepare("SELECT 1 FROM group_members WHERE group_id=? AND user_id=?");
$chk->execute([$groupId, $userId]);
if (!$chk->fetchColumn()) errorResponse('Kein Zugriff', 403);

$stmt = $pdo->prepare("
  SELECT
    gm.user_id,
    COALESCE(u.display_name, u.email) AS name,
    gm.role
  FROM group_members gm
  JOIN users u ON u.id = gm.user_id
  WHERE gm.group_id = ?
  ORDER BY (gm.role='ADMIN') DESC, name ASC
");
$stmt->execute([$groupId]);

jsonResponse($stmt->fetchAll());
