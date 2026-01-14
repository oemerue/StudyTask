<?php
require_once __DIR__ . '/../core/bootstrap.php';
require_once __DIR__ . '/_admin-only.php';

$stmt = $pdo->query("
    SELECT id, email, display_name, role, matrikelnummer, created_at
    FROM users
    ORDER BY created_at DESC
");

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
