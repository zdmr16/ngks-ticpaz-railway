<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\TalepController;
use App\Http\Controllers\Api\BayiController;
use App\Http\Controllers\Api\BolgeMimariController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Health check endpoint for Railway
Route::get('health', function () {
    $debug = [];
    
    // Environment variables
    $debug['environment'] = [
        'DB_HOST' => env('DB_HOST', 'NOT SET'),
        'DB_PORT' => env('DB_PORT', 'NOT SET'),
        'DB_DATABASE' => env('DB_DATABASE', 'NOT SET'),
        'DB_USERNAME' => env('DB_USERNAME', 'NOT SET'),
        'DB_PASSWORD' => env('DB_PASSWORD') ? '***SET***' : 'NOT SET',
        'APP_ENV' => env('APP_ENV', 'NOT SET'),
        'APP_DEBUG' => env('APP_DEBUG', 'NOT SET'),
    ];
    
    // Database connection test
    try {
        $pdo = new \PDO(
            'mysql:host=' . env('DB_HOST') . ';port=' . (env('DB_PORT') ?: 3306) . ';dbname=' . env('DB_DATABASE'),
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
        
        $debug['database'] = [
            'connection' => 'SUCCESS',
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE')
        ];
        
        // Check tables
        $stmt = $pdo->query('SHOW TABLES');
        $tables = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        $debug['database']['tables_count'] = count($tables);
        $debug['database']['tables'] = $tables;
        
        // Check users table
        if (in_array('kullanicilar', $tables)) {
            $stmt = $pdo->query('SELECT COUNT(*) as count FROM kullanicilar');
            $userCount = $stmt->fetch()['count'];
            $debug['database']['users_count'] = $userCount;
            
            if ($userCount > 0) {
                $stmt = $pdo->query('SELECT id, ad_soyad, email, rol FROM kullanicilar LIMIT 3');
                $users = $stmt->fetchAll();
                $debug['database']['sample_users'] = $users;
            }
        } else {
            $debug['database']['users_table'] = 'NOT FOUND';
        }
        
    } catch (\Exception $e) {
        $debug['database'] = [
            'connection' => 'FAILED',
            'error' => $e->getMessage()
        ];
    }
    
    // Laravel status
    try {
        \DB::connection()->getPdo();
        $debug['laravel_db'] = 'SUCCESS';
    } catch (\Exception $e) {
        $debug['laravel_db'] = 'FAILED: ' . $e->getMessage();
    }
    
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'service' => 'NGKS Ticaret Pazarlama API',
        'version' => '1.0.0',
        'debug' => $debug
    ]);
});

// Manual migration endpoint for Railway setup
Route::get('setup-database', function () {
    try {
        $output = [];
        
        // Check if tables already exist
        $tables = \DB::select('SHOW TABLES');
        if (count($tables) > 0) {
            return response()->json([
                'status' => 'already_setup',
                'message' => 'Database already has tables',
                'tables_count' => count($tables)
            ]);
        }
        
        // Run migrations
        $output['migration_start'] = 'Starting migrations...';
        \Artisan::call('migrate', ['--force' => true]);
        $output['migration_result'] = \Artisan::output();
        
        // Run seeders
        $output['seeder_start'] = 'Starting seeders...';
        \Artisan::call('db:seed', ['--force' => true]);
        $output['seeder_result'] = \Artisan::output();
        
        // Cache config
        \Artisan::call('config:cache');
        $output['config_cache'] = 'Config cached';
        
        return response()->json([
            'status' => 'success',
            'message' => 'Database setup completed!',
            'output' => $output
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);
    }
});

// Public Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

// JWT Protected Routes
Route::middleware('jwt.auth')->group(function () {
    
    // Authentication Routes (Protected)
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('profile', [AuthController::class, 'profile']);
    });
    
    // Location Routes
    Route::prefix('locations')->group(function () {
        Route::get('bolgeler', [LocationController::class, 'getBolgeler']);
        Route::get('bolgeler/{id}', [LocationController::class, 'getBolgeDetay']);
        Route::get('bolgeler/{id}/sehirler', [LocationController::class, 'getBolgeSehirleri']);
        
        Route::get('sehirler', [LocationController::class, 'getSehirler']);
        Route::get('sehirler/{id}', [LocationController::class, 'getSehirDetay']);
        Route::get('sehirler/{id}/ilceler', [LocationController::class, 'getSehirIlceleri']);
        
        Route::get('ilceler', [LocationController::class, 'getIlceler']);
        
        Route::get('asamalar', [LocationController::class, 'getAsamalar']);
        
        Route::get('hiyerarsi', [LocationController::class, 'getHiyerarsi']);
    });
    
    // Talepler CRUD Routes
    Route::prefix('talepler')->group(function () {
        Route::get('/', [TalepController::class, 'index']);
        Route::get('/{id}', [TalepController::class, 'show']);
        Route::post('/', [TalepController::class, 'store']);
        Route::put('/{id}', [TalepController::class, 'update']);
        Route::delete('/{id}', [TalepController::class, 'destroy']);
        Route::get('/{id}/asama-gecmisi', [TalepController::class, 'asamaGecmisi']);
        
        // Aşama değiştirme ve arşivleme
        Route::put('/{id}/asama', [TalepController::class, 'updateAsama']);
        Route::patch('/{id}/arsivle', [TalepController::class, 'arsivle']);
        
        // Talep Türleri ve Aşamalar Routes
        Route::get('turler', function () {
            return response()->json([
                'success' => true,
                'data' => \App\Models\TalepTuru::all()
            ]);
        });
        
        Route::get('asamalar', function () {
            return response()->json([
                'success' => true,
                'data' => \App\Models\Asama::orderBy('is_akisi_tipi')->orderBy('sira')->get()
            ]);
        });
        
        Route::get('asamalar/{tip}', function ($tip) {
            return response()->json([
                'success' => true,
                'data' => \App\Models\Asama::where('is_akisi_tipi', $tip)->orderBy('sira')->get()
            ]);
        });
    });
    
    // Dropdown API'leri talep formu için
    Route::get('/bolge-mimarlari', [LocationController::class, 'getBolgeMimarlari']);
    Route::get('/bayiler', [LocationController::class, 'getBayiler']);
    Route::get('/talep-turleri', [LocationController::class, 'getTalepTurleri']);
    Route::get('/asamalar', [LocationController::class, 'getAsamalar']);
    
    // Bayiler Management Routes
    Route::prefix('bayiler')->group(function () {
        Route::get('/', [BayiController::class, 'index']);
        Route::get('/{id}', [BayiController::class, 'show']);
        Route::post('/', [BayiController::class, 'store']);
        Route::put('/{id}', [BayiController::class, 'update']);
        Route::delete('/{id}', [BayiController::class, 'destroy']);
        
        // Bayi çalışanları
        Route::get('/{id}/calisanlar', [BayiController::class, 'getCalisanlar']);
        Route::post('/{id}/calisanlar', [BayiController::class, 'addCalisan']);
        Route::put('/{id}/calisanlar/{calisanId}', [BayiController::class, 'updateCalisan']);
        Route::delete('/{id}/calisanlar/{calisanId}', [BayiController::class, 'removeCalisan']);
        
        // Bayi mağazaları
        Route::get('/{id}/magazalar', [BayiController::class, 'getMagazalar']);
        Route::post('/{id}/magazalar', [BayiController::class, 'addMagaza']);
        Route::put('/{id}/magazalar/{magazaId}', [BayiController::class, 'updateMagaza']);
        Route::delete('/{id}/magazalar/{magazaId}', [BayiController::class, 'deleteMagaza']);
    });
    
    // Bölge Mimarları Management Routes
    Route::prefix('bolge-mimarlari')->group(function () {
        Route::get('/', [BolgeMimariController::class, 'index']);
        Route::get('/{id}', [BolgeMimariController::class, 'show']);
        Route::post('/', [BolgeMimariController::class, 'store']);
        Route::put('/{id}', [BolgeMimariController::class, 'update']);
        Route::delete('/{id}', [BolgeMimariController::class, 'destroy']);
        
        // Bölge atamaları
        Route::get('/{id}/atamalar', [BolgeMimariController::class, 'getAtamalar']);
        Route::post('/{id}/atamalar', [BolgeMimariController::class, 'addAtama']);
        Route::delete('/{id}/atamalar/{atamaId}', [BolgeMimariController::class, 'removeAtama']);
        
        // Bölgeye göre mimarları getir
        Route::get('/bolge/{bolgeId}', [BolgeMimariController::class, 'getByBolge']);
    });

});

// Legacy route for compatibility
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
