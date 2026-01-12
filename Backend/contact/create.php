<?php
require_once __DIR__ . '/../core/bootstrap.php';

$input = getJsonInput();

$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$message = trim($input['message'] ?? '');

if ($name === '' || $email === '' || $message === '') {
    errorResponse('Alle Felder sind erforderlich', 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    errorResponse('Ungültige E-Mail-Adresse', 400);
}

$stmt = $pdo->prepare("
    INSERT INTO contact_messages (name, email, message)
    VALUES (?, ?, ?)
");
$stmt->execute([$name, $email, $message]);

jsonResponse(['success' => true]);
