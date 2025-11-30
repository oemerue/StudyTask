<?php
$charset = 'utf8mb4';

/**
 * Fallback-Werte = deine AWS-RDS-Daten
 * (bitte DB_PASS unten anpassen!)
 */
$defaultHost = 'studytask.cr622oc4qe11.eu-central-1.rds.amazonaws.com';
$defaultDb   = 'studytask';
$defaultUser = 'ServerAdmin';
$defaultPass = 'SerdarErsanOemer2025';

// zuerst aus ENV lesen, sonst auf AWS-Fallback gehen
$host = getenv('DB_HOST') ?: $defaultHost;
$db   = getenv('DB_NAME') ?: $defaultDb;
$user = getenv('DB_USER') ?: $defaultUser;
$pass = getenv('DB_PASS') ?: $defaultPass;

$dsn = "mysql:host=$host;port=3306;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database connection failed']);
    // optional zum Debuggen:
    // echo json_encode(['error' => $e->getMessage()]);
    exit;
}
