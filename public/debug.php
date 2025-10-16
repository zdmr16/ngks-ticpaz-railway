<?php
// Database bağlantı testi için basit debug scripti

echo "<h1>NGKS Railway Debug Info</h1>";

echo "<h2>Environment Variables:</h2>";
echo "<pre>";
echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'NOT SET') . "\n";
echo "DB_PORT: " . ($_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? 'NOT SET') . "\n";
echo "DB_DATABASE: " . ($_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE') ?? 'NOT SET') . "\n";
echo "DB_USERNAME: " . ($_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME') ?? 'NOT SET') . "\n";
echo "DB_PASSWORD: " . (($_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD')) ? '***SET***' : 'NOT SET') . "\n";
echo "</pre>";

echo "<h2>Database Connection Test:</h2>";
echo "<pre>";

try {
    $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
    $port = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? 3306;
    $dbname = $_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE');
    $username = $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME');
    $password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD');
    
    if (!$host || !$dbname || !$username) {
        echo "❌ Missing database credentials\n";
    } else {
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
        echo "Connecting to: $dsn\n";
        echo "Username: $username\n";
        
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        
        echo "✅ Database connection successful!\n";
        
        // Test tabloları kontrol et
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "\n📋 Tables in database (" . count($tables) . "):\n";
        foreach ($tables as $table) {
            echo "  - $table\n";
        }
        
        // Kullanıcı tablosunu kontrol et
        if (in_array('kullanicilar', $tables)) {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM kullanicilar");
            $userCount = $stmt->fetch()['count'];
            echo "\n👥 Users in kullanicilar table: $userCount\n";
            
            if ($userCount > 0) {
                $stmt = $pdo->query("SELECT id, ad_soyad, email, rol FROM kullanicilar LIMIT 3");
                $users = $stmt->fetchAll();
                echo "\n📋 Sample users:\n";
                foreach ($users as $user) {
                    echo "  - {$user['ad_soyad']} ({$user['email']}) - {$user['rol']}\n";
                }
            }
        } else {
            echo "\n❌ 'kullanicilar' table not found!\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
}

echo "</pre>";

echo "<h2>Laravel Status:</h2>";
echo "<pre>";

// Laravel bootstrap deneyelim
try {
    require_once __DIR__ . '/../bootstrap/app.php';
    echo "✅ Laravel bootstrap successful\n";
    
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "✅ Laravel kernel created\n";
    
} catch (Exception $e) {
    echo "❌ Laravel bootstrap failed: " . $e->getMessage() . "\n";
}

echo "</pre>";

?>