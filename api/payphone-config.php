<?php
//Este archivo lee los datos de config.php y los devuelve 
// en formato JSON para que app.js pueda cargar la Cajita de Pagos.

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$config = require dirname(__DIR__) . '/config.php';

$token = trim((string)($config['token'] ?? ''));
$storeId = trim((string)($config['storeId'] ?? ''));

if ($token === '' || $storeId === '' || str_contains($token, 'PEGA_AQUI') || str_contains($storeId, 'PEGA_AQUI')) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'message' => 'Configura tu TOKEN y STORE ID en el archivo config.php antes de iniciar una compra.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode([
    'ok' => true,
    // El SDK oficial de la Cajita de Pagos requiere estos datos para renderizar el formulario.
    'token' => $token,
    'storeId' => $storeId,
], JSON_UNESCAPED_UNICODE);
