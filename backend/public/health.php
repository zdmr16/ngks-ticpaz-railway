<?php
// Simple health check endpoint for Railway
// Independent of Laravel framework

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Simple health check response
$response = [
    'status' => 'ok',
    'timestamp' => date('c'),
    'service' => 'NGKS Ticaret Pazarlama API',
    'version' => '1.0.0',
    'server' => 'Railway',
    'php_version' => PHP_VERSION,
    'memory_usage' => memory_get_usage(true),
    'uptime' => time()
];

// Test database connection if possible
try {
    if (extension_loaded('pdo_mysql')) {
        $response['database'] = 'mysql_extension_loaded';
    }
} catch (Exception $e) {
    $response['database'] = 'error';
}

// Return health status
http_response_code(200);
echo json_encode($response, JSON_PRETTY_PRINT);
?>