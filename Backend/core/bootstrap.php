<?php

// CORS (für lokale Entwicklung – später evtl. einschränken)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

session_start();

// Basis-Funktionen & DB laden
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/response.php';
require_once __DIR__ . '/auth.php';

// Hilfsfunktion: JSON-Body lesen
function getJsonInput(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === false || $raw === '') {
        return [];
    }

    $data = json_decode($raw, true);
    if (!is_array($data)) {
        errorResponse('Invalid JSON body', 400);
    }
    return $data;
}
