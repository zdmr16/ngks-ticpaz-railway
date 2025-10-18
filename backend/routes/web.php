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

// Geçici route - BayiMagazaSeeder çalıştırmak için
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
            'message' => 'Seeder çalıştırılırken hata oluştu',
            'error' => $e->getMessage()
        ]);
    }
});
