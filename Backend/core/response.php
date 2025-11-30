<?php

function jsonResponse($data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

function errorResponse(string $message, int $statusCode = 400, array $extra = []): void
{
    $payload = array_merge(['error' => $message], $extra);
    jsonResponse($payload, $statusCode);
}
