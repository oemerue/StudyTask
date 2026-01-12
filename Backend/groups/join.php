<?php
require_once __DIR__ . '/../core/bootstrap.php';

$userId = requireLogin();
$input = json_decode(file_get_contents('php://input'), true) ?: [];

$code = strtoupper(trim($input['join_code'] ?? ''));
if ($code === '') errorResponse('join_code erforderlich', 400);

// 1) Gruppe über Join-Code finden
$g = $pdo->prepare("SELECT id FROM `groups` WHERE join_code = ?");
$g->execute([$code]);
$groupId = (int)($g->fetchColumn() ?: 0);

if ($groupId <= 0) {
  errorResponse('Ungültiger Join-Code', 404);
}

// 2) Prüfen ob schon Mitglied (nur in DIESER Gruppe)
$already = $pdo->prepare("SELECT 1 FROM group_members WHERE group_id = ? AND user_id = ?");
$already->execute([$groupId, $userId]);
if ($already->fetchColumn()) {
  errorResponse('Du bist bereits Mitglied dieser Gruppe', 409);
}

// 3) Mitgliedschaft anlegen
$ins = $pdo->prepare("
  INSERT INTO group_members (group_id, user_id, role)
  VALUES (?, ?, 'MEMBER')
");

try {
  $ins->execute([$groupId, $userId]);
} catch (Throwable $e) {
  // wenn du UNIQUE (group_id,user_id) hast, landet ein double-join hier
  errorResponse('Beitritt fehlgeschlagen', 500);
}

jsonResponse(['ok' => true, 'group_id' => $groupId]);
