<?php
require_once __DIR__ . '/../core/bootstrap.php';

$input = getJsonInput();

$email    = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

if ($email === '' || $password === '') {
    errorResponse('email und password sind erforderlich', 400);
}

$stmt = $pdo->prepare('
    SELECT id, matrikelnummer, email, password_hash, display_name, role
    FROM users
    WHERE email = ?
    LIMIT 1
');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    errorResponse('Ungültige Zugangsdaten', 401);
}

// Session
$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['role']    = $user['role'];

jsonResponse([
    'id'             => (int)$user['id'],
    'matrikelnummer' => $user['matrikelnummer'],
    'email'          => $user['email'],
    'display_name'   => $user['display_name'],
    'role'           => $user['role'],
]);
