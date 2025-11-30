<?php

function getCurrentUserId(): ?int
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    return (int)$_SESSION['user_id'];
}

function requireLogin(): int
{
    $userId = getCurrentUserId();
    if (!$userId) {
        errorResponse('Not authenticated', 401);
    }
    return $userId;
}

// Optional: aktuellen User aus DB laden
function getCurrentUser(PDO $pdo): ?array
{
    $userId = getCurrentUserId();
    if (!$userId) {
        return null;
    }

    $stmt = $pdo->prepare('SELECT id, matrikelnummer, email, display_name, created_at, updated_at FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    return $stmt->fetch() ?: null;
}
