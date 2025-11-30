<?php

require_once __DIR__ . '/../core/bootstrap.php';

$input = getJsonInput();

$matrikelnummer = trim($input['matrikelnummer'] ?? '');
$email          = trim($input['email'] ?? '');
$password       = $input['password'] ?? '';
$displayName    = trim($input['display_name'] ?? '');

if ($matrikelnummer === '' || $email === '' || $password === '' || $displayName === '') {
    errorResponse('matrikelnummer, email, password und display_name sind erforderlich', 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    errorResponse('Ungültige E-Mail-Adresse', 400);
}

// Prüfen, ob E-Mail schon existiert
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    errorResponse('E-Mail wird bereits verwendet', 409);
}

$passwordHash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $pdo->prepare('
    INSERT INTO users (matrikelnummer, email, password_hash, display_name)
    VALUES (:matrikelnummer, :email, :password_hash, :display_name)
');

$stmt->execute([
    ':matrikelnummer' => $matrikelnummer,
    ':email'          => $email,
    ':password_hash'  => $passwordHash,
    ':display_name'   => $displayName,
]);

$userId = (int)$pdo->lastInsertId();

// Direkt einloggen
$_SESSION['user_id'] = $userId;

jsonResponse([
    'id'             => $userId,
    'matrikelnummer' => $matrikelnummer,
    'email'          => $email,
    'display_name'   => $displayName,
], 201);
