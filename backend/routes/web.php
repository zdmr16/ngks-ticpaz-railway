<?php

use Illuminate\Support\Facades\Route;
use App\Models\Bayi;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bayiler', function () {
    $bayiler = Bayi::orderBy('id', 'asc')->get();
    return view('bayiler', compact('bayiler'));
});

// Geçici route - Sadece seederları çalıştır
Route::get('/run-seeders', function () {
    try {
        Artisan::call('db:seed');
        $output = Artisan::output();
        return response()->json([
            'success' => true,
            'message' => 'Seederlar başarıyla çalıştırıldı',
            'output' => $output
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Seederlar çalıştırılırken hata oluştu',
            'error' => $e->getMessage()
        ]);
    }
});

// Geçici route - Sadece BayiMagazaSeeder çalıştır
Route::get('/run-bayi-seeder', function () {
    try {
        Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\BayiMagazaSeeder']);
        $output = Artisan::output();
        return response()->json([
            'success' => true,
            'message' => 'BayiMagazaSeeder başarıyla çalıştırıldı',
            'output' => $output
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'BayiMagazaSeeder çalıştırılırken hata oluştu',
            'error' => $e->getMessage()
        ]);
    }
});

// Geçici route - Tüm database'i yeniden oluştur
Route::get('/reset-database', function () {
    try {
        Artisan::call('migrate:fresh', ['--seed' => true]);
        $output = Artisan::output();
        return response()->json([
            'success' => true,
            'message' => 'Database başarıyla sıfırlandı ve tüm seederlar çalıştırıldı',
            'output' => $output
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Database reset edilirken hata oluştu',
            'error' => $e->getMessage()
        ]);
    }
});
