<?php
require_once __DIR__ . '/../core/bootstrap.php';

$userId = requireLogin();
$groupId = (int)($_GET['group_id'] ?? 0);

if ($groupId <= 0) {
    errorResponse('group_id fehlt', 400);
}

// Zugriff prüfen
$check = $pdo->prepare("
    SELECT 1
    FROM group_members
    WHERE group_id = ? AND user_id = ?
");
$check->execute([$groupId, $userId]);

if (!$check->fetchColumn()) {
    errorResponse('Kein Zugriff', 403);
}

/*
 Aufgaben laden
 WICHTIG: responsible_user_id + responsible_name
*/
$stmt = $pdo->prepare("
SELECT
  t.id,
  t.title,
  t.description,
  t.status,
  t.due_at,
  t.created_by,
  cu.display_name AS created_by_name,
  t.responsible_user_id,
  ru.display_name AS responsible_name,
  GROUP_CONCAT(tg.name) AS tags,
  GROUP_CONCAT(tg.color) AS tag_colors
FROM tasks t
JOIN users cu ON cu.id = t.created_by
LEFT JOIN users ru ON ru.id = t.responsible_user_id
LEFT JOIN task_tags tt ON tt.task_id = t.id
LEFT JOIN tags tg ON tg.id = tt.tag_id
WHERE t.group_id = ?
GROUP BY t.id
ORDER BY t.due_at IS NULL, t.due_at ASC
");


$stmt->execute([$groupId]);

jsonResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
