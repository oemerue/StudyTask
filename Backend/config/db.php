<?php
$charset = 'utf8mb4';

/**
 * Fallback-Werte = deine AWS-RDS-Daten
 * (bitte DB_PASS unten anpassen!)
 */
$defaultHost = 'studytask.c182o44suwmb.eu-central-1.rds.amazonaws.com';
$defaultDb   = 'studytask';
$defaultUser = 'ServerAdmin';
$defaultPass = 'SerdoErdo123';

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
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode([
    'error' => $e->getMessage(),
    'code'  => $e->getCode()
  ]);
  exit;
}
