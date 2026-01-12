<?php
require_once __DIR__ . '/../core/bootstrap.php';

$userId = requireLogin();

$stmt = $pdo->prepare("
  SELECT
    g.id,
    g.name,
    gm.role
  FROM `groups` g
  INNER JOIN group_members gm ON gm.group_id = g.id
  WHERE gm.user_id = ?
  ORDER BY g.name ASC
");

$stmt->execute([$userId]);

jsonResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
