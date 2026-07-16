<?php
$config = require __DIR__ . '/config.php';

function escapeHtml(mixed $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function moneyFromCents(mixed $value): string {
    return '$' . number_format(((int)$value) / 100, 2, '.', ',');
}

//RESPONSE
//Esta es la primera respuesta que Payphone envía después del pago. 
// Con esos datos puedo saber qué transacción debo confirmar.

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$clientTxId = isset($_GET['clientTransactionId']) ? trim((string)$_GET['clientTransactionId']) : '';
$token = trim((string)($config['token'] ?? ''));
$confirmUrl = (string)($config['confirmUrl'] ?? 'https://paymentbox.payphonetodoesposible.com/api/confirm');

$result = null;
$error = null;
$httpCode = 0;

//Antes de llamar al API de Payphone, el sistema revisa tres cosas
if ($id <= 0 || $clientTxId === '') {
    $error = 'No se recibieron correctamente los parámetros id y clientTransactionId desde Payphone.';
} elseif ($token === '' || str_contains($token, 'PEGA_AQUI')) {
    $error = 'Debes colocar tu token de Payphone en el archivo config.php.';
} elseif (!function_exists('curl_init')) {
    $error = 'La extensión cURL de PHP no está habilitada. Actívala para realizar la confirmación servidor-servidor.';
} else {


//se hace después del pago  
// para ver si transacción fue aprobada o cancelada.

    $requestBody = json_encode([
        'id' => $id,
        'clientTxId' => $clientTxId,
    ], JSON_UNESCAPED_UNICODE);

    //Comunicación servidor Avila - servidorPayphone
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $confirmUrl,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $requestBody,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20,
    ]);

//Response principal: contiene el estado real del pago 
// y los datos de la transacción.

    $rawResponse = curl_exec($curl);
    $httpCode = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curlError = curl_error($curl);
    curl_close($curl);

    if ($rawResponse === false || $curlError !== '') {
        $error = 'No fue posible comunicarse con Payphone: ' . $curlError;
    } else {
        $decoded = json_decode($rawResponse, true);
        if (!is_array($decoded)) {
            $error = 'Payphone devolvió una respuesta que no pudo interpretarse como JSON.';
            $result = ['rawResponse' => $rawResponse];
        } else {
            $result = $decoded;
            if ($httpCode >= 400 || isset($decoded['errorCode'])) {
                $error = (string)($decoded['message'] ?? 'Payphone informó un error al confirmar la transacción.');
            }
        }
    }
}

//Determina si el pago fue aprobado.
$status = (string)($result['transactionStatus'] ?? '');
$isApproved = $status === 'Approved' || (int)($result['statusCode'] ?? 0) === 3;
$title = $isApproved ? '¡Pago aprobado!' : ($error ? 'No se pudo confirmar el pago' : 'Pago procesado');
$subtitle = $isApproved
    ? 'La transacción fue confirmada correctamente mediante el API de Payphone.'
    : ($error ?: 'Revisa el detalle devuelto por Payphone.');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado del pago | AvilaTech</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="assets/images/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="result-page">
    <header class="site-header">
        <div class="container header-content">
            <a class="brand" href="index.php">
                <span class="brand-mark">A</span>
                <span>Avila<span>Tech</span></span>
            </a>
            <span class="demo-label">Entorno de pruebas</span>
        </div>
    </header>

    <main class="result-main container">
        <section class="result-card">
            <div class="result-icon <?= $isApproved ? 'approved' : 'not-approved' ?>">
                <?= $isApproved ? '✓' : '!' ?>
            </div>
            <p class="eyebrow centered"><span></span> Respuesta del API <span></span></p>
            <h1><?= escapeHtml($title) ?></h1>
            <p class="result-subtitle"><?= escapeHtml($subtitle) ?></p>

            <?php if ($result): ?>
                <div class="result-grid">
                    <article>
                        <span>Estado</span>
                        <strong><?= escapeHtml($result['transactionStatus'] ?? ($error ? 'Error' : 'Sin información')) ?></strong>
                    </article>
                    <article>
                        <span>ID de transacción</span>
                        <strong><?= escapeHtml($result['transactionId'] ?? $id ?: '—') ?></strong>
                    </article>
                    <article>
                        <span>Monto</span>
                        <strong><?= isset($result['amount']) ? escapeHtml(moneyFromCents($result['amount'])) : '—' ?></strong>
                    </article>
                    <article>
                        <span>Autorización</span>
                        <strong><?= escapeHtml($result['authorizationCode'] ?? '—') ?></strong>
                    </article>
                    <article>
                        <span>Referencia del comercio</span>
                        <strong><?= escapeHtml($result['clientTransactionId'] ?? $clientTxId ?: '—') ?></strong>
                    </article>
                    <article>
                        <span>Forma de pago</span>
                        <strong><?= escapeHtml($result['cardBrand'] ?? $result['transactionType'] ?? '—') ?></strong>
                    </article>
                </div>
            <?php endif; ?>

            <div class="response-panel">
                <div class="response-panel-heading">
                    <div>
                        <span>Servidor → API Payphone → Servidor</span>
                        <h2>Response recibido</h2>
                    </div>
                    <small>HTTP <?= escapeHtml($httpCode ?: '—') ?></small>
                </div>
                <pre><?= escapeHtml(json_encode($result ?? ['message' => $error], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) ?></pre>
            </div>

            <a href="index.php" class="result-link">← Volver a la tienda</a>
        </section>
    </main>
</body>
</html>
