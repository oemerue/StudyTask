<?php
require_once __DIR__ . '/../core/bootstrap.php';

requireLogin();
header('Content-Type: application/json');

$data = getJsonInput();
$userId = $_SESSION['user_id'];

$currentPassword = $data['current_password'] ?? '';
$newPassword = $data['new_password'] ?? null;
$newPasswordRepeat = $data['new_password_repeat'] ?? null;

$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$matrikelnummer = trim($data['matrikelnummer'] ?? '');

/* --- User laden (WICHTIG: password_hash) --- */
$stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
    http_response_code(403);
    echo json_encode(["message" => "Aktuelles Passwort ist falsch."]);
    exit;
}

/* --- Profil-Daten aktualisieren --- */
$stmt = $pdo->prepare("
    UPDATE users
    SET display_name = ?, email = ?, matrikelnummer = ?
    WHERE id = ?
");
$stmt->execute([
    $name,
    $email,
    $matrikelnummer,
    $userId
]);

/* --- Passwort ändern (optional) --- */
if ($newPassword || $newPasswordRepeat) {
    if ($newPassword !== $newPasswordRepeat) {
        http_response_code(400);
        echo json_encode(["message" => "Neue Passwörter stimmen nicht überein."]);
        exit;
    }

    if (strlen($newPassword) < 8) {
        http_response_code(400);
        echo json_encode(["message" => "Neues Passwort muss mindestens 8 Zeichen lang sein."]);
        exit;
    }

    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
    $stmt->execute([$hash, $userId]);
}

echo json_encode(["success" => true]);
