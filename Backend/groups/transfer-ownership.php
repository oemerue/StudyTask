<?php
require_once __DIR__ . '/../core/bootstrap.php';

$userId = requireLogin();
$input = json_decode(file_get_contents('php://input'), true) ?: [];

$groupId = (int)($input['group_id'] ?? 0);
$newOwnerId = (int)($input['new_owner_user_id'] ?? 0);

if ($groupId <= 0 || $newOwnerId <= 0) {
    errorResponse('group_id und new_owner_user_id erforderlich', 400);
}

/*
 Prüfen: aktueller User muss ADMIN sein
*/
$role = $pdo->prepare("
    SELECT role
    FROM group_members
    WHERE group_id = ? AND user_id = ?
");
$role->execute([$groupId, $userId]);

if ($role->fetchColumn() !== 'ADMIN') {
    errorResponse('Nur der Admin darf Ownership übertragen', 403);
}

/*
 Neuer Owner muss Mitglied der Gruppe sein
*/
$chk = $pdo->prepare("
    SELECT 1
    FROM group_members
    WHERE group_id = ? AND user_id = ?
");
$chk->execute([$groupId, $newOwnerId]);

if (!$chk->fetchColumn()) {
    errorResponse('Neuer Owner muss Mitglied sein', 400);
}

/*
 Ownership übertragen
 ACHTUNG: created_by statt owner_user_id
*/
$pdo->beginTransaction();

try {
    // neuen Owner setzen
    $pdo->prepare("
        UPDATE `groups`
        SET created_by = ?
        WHERE id = ?
    ")->execute([$newOwnerId, $groupId]);

    // Rollen tauschen
    $pdo->prepare("
        UPDATE group_members
        SET role = 'MEMBER'
        WHERE group_id = ? AND user_id = ?
    ")->execute([$groupId, $userId]);

    $pdo->prepare("
        UPDATE group_members
        SET role = 'ADMIN'
        WHERE group_id = ? AND user_id = ?
    ")->execute([$groupId, $newOwnerId]);

    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    errorResponse('Ownership konnte nicht übertragen werden', 500);
}

jsonResponse(['ok' => true]);
