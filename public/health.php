<?php
/**
 * Railway Health Check Endpoint
 */

header('Content-Type: application/json');

try {
    // Database connection test
    $pdo = new PDO(
        'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
    
    $dbStatus = 'connected';
    
    // Check table count
    $stmt = $pdo->query("SELECT COUNT(*) as table_count FROM information_schema.tables WHERE table_schema = '" . $_ENV['DB_DATABASE'] . "'");
    $tableCount = $stmt->fetch(PDO::FETCH_ASSOC)['table_count'];
    
    // Check demo data
    $stmt = $pdo->query("SELECT COUNT(*) as talep_count FROM talepler");
    $talepCount = $stmt->fetch(PDO::FETCH_ASSOC)['talep_count'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as bayi_count FROM bayiler");
    $bayiCount = $stmt->fetch(PDO::FETCH_ASSOC)['bayi_count'];
    
} catch (Exception $e) {
    $dbStatus = 'error: ' . $e->getMessage();
    $tableCount = 0;
    $talepCount = 0;
    $bayiCount = 0;
}

$response = [
    'status' => 'ok',
    'timestamp' => date('Y-m-d H:i:s'),
    'database' => $dbStatus,
    'tables' => $tableCount,
    'demo_data' => [
        'talepler' => $talepCount,
        'bayiler' => $bayiCount
    ]
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>