<?php
require_once __DIR__ . '/../core/bootstrap.php';

$userId = requireLogin();
$groupId = (int)($_GET['group_id'] ?? 0);

if ($groupId <= 0) {
    errorResponse('group_id erforderlich', 400);
}

/*
 Zugriff prüfen:
 User muss Mitglied der Gruppe sein
*/
$chk = $pdo->prepare("
    SELECT gm.role
    FROM group_members gm
    WHERE gm.group_id = :gid AND gm.user_id = :uid
");
$chk->execute([
    ':gid' => $groupId,
    ':uid' => $userId
]);

$myRole = $chk->fetchColumn();
if (!$myRole) {
    errorResponse('Kein Zugriff auf diese Gruppe', 403);
}

/*
 Gruppeninfos laden
 ACHTUNG: created_by statt owner_user_id
*/
$stmt = $pdo->prepare("
    SELECT
        g.id,
        g.name,
        g.description,
        g.join_code,
        g.created_by
    FROM `groups` g
    WHERE g.id = :gid
");
$stmt->execute([':gid' => $groupId]);

$group = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$group) {
    errorResponse('Gruppe nicht gefunden', 404);
}

/*
 Owner-Name laden
*/
$ownerStmt = $pdo->prepare("
    SELECT COALESCE(u.display_name, u.email) AS owner_name
    FROM users u
    WHERE u.id = :oid
");
$ownerStmt->execute([':oid' => $group['created_by']]);
$ownerName = $ownerStmt->fetchColumn();

/*
 Response
*/
jsonResponse([
    'id' => (int)$group['id'],
    'name' => $group['name'],
    'description' => $group['description'],
    'join_code' => $group['join_code'],
    // bewusst owner_user_id genannt, damit das Frontend unverändert bleibt
    'owner_user_id' => (int)$group['created_by'],
    'owner_name' => $ownerName,
    'my_role' => $myRole // 'ADMIN' oder 'MEMBER'
]);
