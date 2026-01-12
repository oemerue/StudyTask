<?php
require_once __DIR__ . '/../core/bootstrap.php';
require_once __DIR__ . '/_admin-only.php';

header('Content-Type: application/json');

$stmt = $pdo->query("
    SELECT *
    FROM contact_messages
    ORDER BY created_at DESC
");

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
