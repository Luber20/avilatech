<?php
echo "======================================================\n";
echo "   INICIANDO SUITE DE PRUEBAS AUTOMÁTICAS - AVILATECH \n";
echo "======================================================\n\n";

$errores = 0;
$totalTests = 0;

// Función para imprimir los resultados ordenados
function runTest($nombre, $condicion) {
    global $errores, $totalTests;
    $totalTests++;
    echo "[Test $totalTests] $nombre... \n";
    if ($condicion) {
        echo "   -> ✅ PASÓ\n\n";
    } else {
        echo "   -> ❌ FALLÓ\n\n";
        $errores++;
    }
}

// TEST 1: Seguridad y Credenciales
// Verifica que el archivo config.php se haya creado bien en el pipeline y tenga un token real.
$config = @include __DIR__ . '/../config.php';
$hasToken = ($config && !empty($config['token']) && strpos($config['token'], 'PEGA_AQUI') === false);
runTest("Seguridad: Verificar credenciales de Payphone", $hasToken);

// TEST 2: Backend - API de Configuración
// Verifica que tu archivo api/payphone-config.php esté listo para enviar datos en formato JSON a la vista.
$apiContent = @file_get_contents(__DIR__ . '/../api/payphone-config.php');
$hasApiLogic = (strpos($apiContent, 'json_encode') !== false && strpos($apiContent, '$config[\'token\']') !== false);
runTest("Backend: Verificar endpoint interno de configuración (API)", $hasApiLogic);

// TEST 3: Frontend - Inyección de Payphone
// Verifica que en el index.php exista el script oficial de Payphone y el contenedor donde carga la cajita.
$indexContent = @file_get_contents(__DIR__ . '/../index.php');
$hasSDK = (strpos($indexContent, 'payphone-payment-box.js') !== false && strpos($indexContent, 'pp-button') !== false);
runTest("Frontend: Comprobar inyección del SDK de Payphone en la vista principal", $hasSDK);

// TEST 4: Frontend - Lógica de Carrito y Pasarela
// Escanea el archivo JavaScript para asegurar que la lógica de la pasarela de pagos (PPaymentButtonBox) no se haya borrado.
$appJsContent = @file_get_contents(__DIR__ . '/../assets/app.js');
$hasCartLogic = (strpos($appJsContent, 'cart = []') !== false && strpos($appJsContent, 'PPaymentButtonBox') !== false);
runTest("Lógica: Validar el carrito y llamada a la Cajita de Pagos en JS", $hasCartLogic);

// TEST 5: Servidor a Servidor - Respuesta de Pago
// Revisa que respuesta.php tenga habilitada la función cURL para confirmar el pago por detrás con Payphone.
$respuestaContent = @file_get_contents(__DIR__ . '/../respuesta.php');
$hasCurl = (strpos($respuestaContent, 'curl_init') !== false && strpos($respuestaContent, 'CURLOPT_URL') !== false);
runTest("Servidor: Verificar lógica de confirmación servidor-a-servidor (cURL)", $hasCurl);

echo "======================================================\n";
// Resultado final para Jenkins
if ($errores > 0) {
    echo "⚠️  ERROR FATAL: $errores de $totalTests pruebas fallaron. El despliegue se cancela.\n";
    exit(1); // Manda un código de error a Jenkins para que detenga todo
} else {
    echo "🚀 ÉXITO: $totalTests/$totalTests pruebas pasaron correctamente.\n";
    echo "🌐 Continuando con el despliegue de AvilaTech...\n";
    exit(0); // Manda un código de éxito a Jenkins para que avance al Deploy
}