<?php
require_once __DIR__ . '/../core/bootstrap.php';

$userId = requireLogin();
$input = json_decode(file_get_contents('php://input'), true) ?: [];

$name = trim($input['name'] ?? '');
$description = trim($input['description'] ?? '');

if ($name === '') {
    errorResponse('Name erforderlich', 400);
}

function genCode($len = 8) {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $out = '';
    for ($i = 0; $i < $len; $i++) {
        $out .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $out;
}

/**
 * Unique Join-Code erzeugen
 */
$code = null;
for ($i = 0; $i < 10; $i++) {
    $try = genCode(8);
    $chk = $pdo->prepare("SELECT 1 FROM `groups` WHERE join_code = ?");
    $chk->execute([$try]);
    if (!$chk->fetchColumn()) {
        $code = $try;
        break;
    }
}

if (!$code) {
    errorResponse('Konnte Join-Code nicht generieren', 500);
}

$pdo->beginTransaction();

/**
 * Gruppe anlegen
 * WICHTIG: created_by statt owner_user_id
 */
$stmt = $pdo->prepare("
    INSERT INTO `groups` (name, description, join_code, created_by)
    VALUES (?, ?, ?, ?)
");
$stmt->execute([
    $name,
    $description !== '' ? $description : null,
    $code,
    $userId
]);

$groupId = (int)$pdo->lastInsertId();

/**
 * Ersteller als ADMIN in group_members eintragen
 */
$mem = $pdo->prepare("
    INSERT INTO group_members (group_id, user_id, role)
    VALUES (?, ?, 'ADMIN')
");
$mem->execute([$groupId, $userId]);

$pdo->commit();

jsonResponse([
    'ok' => true,
    'group_id' => $groupId,
    'join_code' => $code,
    'role' => 'ADMIN'
]);
