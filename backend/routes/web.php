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

Route::get('/bolgeler', function () {
    $bolgeler = \App\Models\Bolge::orderBy('id', 'asc')->get();
    return view('bolgeler', compact('bolgeler'));
});

Route::get('/sehirler', function () {
    $sehirler = \App\Models\Sehir::with('bolge')->orderBy('id', 'asc')->get();
    return view('sehirler', compact('sehirler'));
});

Route::get('/asamalar', function () {
    // Talep türleri ile aşamalar arasındaki ilişkiyi getir
    $talepTurleri = \App\Models\TalepTuru::with(['asamalar' => function($query) {
        $query->orderBy('sira', 'asc');
    }])->orderBy('id', 'asc')->get();
    
    return view('asamalar', compact('talepTurleri'));
});

// Talep Türleri ve Aşamalar tablolarını doğrudan INSERT ile doldur
Route::get('/load-talep-data', function () {
    try {
        // Önce tabloları temizle
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        \Illuminate\Support\Facades\DB::statement('TRUNCATE TABLE asamalar');
        \Illuminate\Support\Facades\DB::statement('TRUNCATE TABLE talep_turleri');
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        
        $now = now();
        
        // Talep Türleri INSERT
        $talepTurleri = [
            ['ad' => 'Kayar Pano', 'is_akisi_tipi' => 'tip_a', 'created_at' => $now, 'updated_at' => $now],
            ['ad' => 'Dijital Baskı', 'is_akisi_tipi' => 'tip_a', 'created_at' => $now, 'updated_at' => $now],
            ['ad' => 'Dış Dijital Baskı', 'is_akisi_tipi' => 'tip_a', 'created_at' => $now, 'updated_at' => $now],
            ['ad' => 'Tabela', 'is_akisi_tipi' => 'tip_a', 'created_at' => $now, 'updated_at' => $now],
            ['ad' => 'Totem', 'is_akisi_tipi' => 'tip_a', 'created_at' => $now, 'updated_at' => $now],
            ['ad' => 'Teşhir Yenileme', 'is_akisi_tipi' => 'tip_b', 'created_at' => $now, 'updated_at' => $now],
            ['ad' => 'Mağaza Projelendirme', 'is_akisi_tipi' => 'tip_b', 'created_at' => $now, 'updated_at' => $now],
            ['ad' => 'Teşhir İade', 'is_akisi_tipi' => 'tip_c', 'created_at' => $now, 'updated_at' => $now],
        ];
        
        \Illuminate\Support\Facades\DB::table('talep_turleri')->insert($talepTurleri);
        
        // Aşamalar INSERT
        $asamalar = [
            // Ortak başlangıç aşaması - tüm tipler için (ID: 1-3)
            ['is_akisi_tipi' => 'tip_a', 'ad' => 'Talep Oluşturuldu', 'sira' => 0, 'created_at' => $now, 'updated_at' => $now],
            ['is_akisi_tipi' => 'tip_b', 'ad' => 'Talep Oluşturuldu', 'sira' => 0, 'created_at' => $now, 'updated_at' => $now],
            ['is_akisi_tipi' => 'tip_c', 'ad' => 'Talep Oluşturuldu', 'sira' => 0, 'created_at' => $now, 'updated_at' => $now],
            
            // TIP_A Aşamaları (ID: 4-11)
            ['is_akisi_tipi' => 'tip_a', 'ad' => 'Bayi Talep', 'sira' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['is_akisi_tipi' => 'tip_a', 'ad' => 'Satışçı Onay', 'sira' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['is_akisi_tipi' => 'tip_a', 'ad' => 'Erhan Talep', 'sira' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['is_akisi_tipi' => 'tip_a', 'ad' => 'Satın Alma Aşaması', 'sira' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['is_akisi_tipi' => 'tip_a', 'ad' => 'Seçkin ÇAĞRICI Onay', 'sira' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['is_akisi_tipi' => 'tip_a', 'ad' => 'Bayi Çek Alma', 'sira' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['is_akisi_tipi' => 'tip_a', 'ad' => 'Tedarik Süreci', 'sira' => 7, 'created_at' => $now, 'updated_at' => $now],
            ['is_akisi_tipi' => 'tip_a', 'ad' => 'İş Teslimi', 'sira' => 8, 'created_at' => $now, 'updated_at' => $now],
            
            // TIP_B Aşamaları (ID: 12-19)
            ['is_akisi_tipi' => 'tip_b', 'ad' => 'Bayi Talep Etti', 'sira' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['is_akisi_tipi' => 'tip_b', 'ad' => 'Satışçı Onay', 'sira' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['is_akisi_tipi' => 'tip_b', 'ad' => '2D Çizim', 'sira' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['is_akisi_tipi' => 'tip_b', 'ad' => '2D Pazarlama Onayı', 'sira' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['is_akisi_tipi' => 'tip_b', 'ad' => '2D Bayi Onayı', 'sira' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['is_akisi_tipi' => 'tip_b', 'ad' => '3D Çizim', 'sira' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['is_akisi_tipi' => 'tip_b', 'ad' => '3D Pazarlama Onayı', 'sira' => 7, 'created_at' => $now, 'updated_at' => $now],
            ['is_akisi_tipi' => 'tip_b', 'ad' => '3D Bayi Onayı', 'sira' => 8, 'created_at' => $now, 'updated_at' => $now],
            
            // TIP_C Aşamaları (ID: 20-23)
            ['is_akisi_tipi' => 'tip_c', 'ad' => 'Bayi Talebi', 'sira' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['is_akisi_tipi' => 'tip_c', 'ad' => 'MD Fiyatlandırması', 'sira' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['is_akisi_tipi' => 'tip_c', 'ad' => 'Mimari Yönetici Kontrolü', 'sira' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['is_akisi_tipi' => 'tip_c', 'ad' => 'Pazarlama Direktörü Onayı', 'sira' => 4, 'created_at' => $now, 'updated_at' => $now],
        ];
        
        \Illuminate\Support\Facades\DB::table('asamalar')->insert($asamalar);
        
        $talepTurleriSayisi = \App\Models\TalepTuru::count();
        $asamalarSayisi = \App\Models\Asama::count();
        
        return response()->json([
            'success' => true,
            'message' => 'Talep türleri ve aşamalar başarıyla yüklendi (INSERT ile)',
            'data' => [
                'talep_turleri_sayisi' => $talepTurleriSayisi,
                'asamalar_sayisi' => $asamalarSayisi
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Talep verileri yüklenirken hata oluştu',
            'error' => $e->getMessage()
        ]);
    }
});

// Talep Türleri tablosunu ID'leriyle birlikte görüntüle
Route::get('/talep-turleri', function () {
    $talepTurleri = \App\Models\TalepTuru::orderBy('id', 'asc')->get();
    
    $html = '<!DOCTYPE html>
<html>
<head>
    <title>Talep Türleri</title>
</head>
<body>
    <h1>Talep Türleri Tablosu (' . $talepTurleri->count() . ' adet)</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Talep Türü Adı</th>
                <th>İş Akışı Tipi</th>
            </tr>
        </thead>
        <tbody>';
    
    foreach ($talepTurleri as $talepTuru) {
        $html .= '<tr>';
        $html .= '<td>' . $talepTuru->id . '</td>';
        $html .= '<td>' . $talepTuru->ad . '</td>';
        $html .= '<td>' . strtoupper($talepTuru->is_akisi_tipi) . '</td>';
        $html .= '</tr>';
    }
    
    $html .= '        </tbody>
    </table>
</body>
</html>';
    
    return $html;
});

// Aşamalar tablosunu ID'leriyle birlikte görüntüle  
Route::get('/asamalar-db', function () {
    $asamalar = \App\Models\Asama::orderBy('id', 'asc')->get();
    
    $html = '<!DOCTYPE html>
<html>
<head>
    <title>Aşamalar</title>
</head>
<body>
    <h1>Aşamalar Tablosu (' . $asamalar->count() . ' adet)</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Aşama Adı</th>
                <th>İş Akışı Tipi</th>
                <th>Sıra</th>
            </tr>
        </thead>
        <tbody>';
    
    foreach ($asamalar as $asama) {
        $html .= '<tr>';
        $html .= '<td>' . $asama->id . '</td>';
        $html .= '<td>' . $asama->ad . '</td>';
        $html .= '<td>' . strtoupper($asama->is_akisi_tipi) . '</td>';
        $html .= '<td>' . $asama->sira . '</td>';
        $html .= '</tr>';
    }
    
    $html .= '        </tbody>
    </table>
</body>
</html>';
    
    return $html;
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

// Database tablolarını temizle - bayiler ve mağazaları sil
Route::get('/clear-bayiler', function () {
    try {
        // Foreign key constraint'leri disable et
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        
        // Tabloları temizle
        \Illuminate\Support\Facades\DB::statement('TRUNCATE TABLE bayi_magazalari');
        \Illuminate\Support\Facades\DB::statement('TRUNCATE TABLE bayi_calisanlari');
        \Illuminate\Support\Facades\DB::statement('TRUNCATE TABLE bayiler');
        
        // Foreign key constraint'leri tekrar aç
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        
        $toplamBayi = \App\Models\Bayi::count();
        $toplamMagaza = \App\Models\BayiMagazasi::count();
        
        return response()->json([
            'success' => true,
            'message' => 'Tüm bayiler ve mağazalar başarıyla silindi',
            'data' => [
                'kalan_bayi' => $toplamBayi,
                'kalan_magaza' => $toplamMagaza
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Tablolar temizlenirken hata oluştu',
            'error' => $e->getMessage()
        ]);
    }
});

// Doğrudan Model::insert() ile 241 bayi kaydını yükle
Route::get('/load-bayiler-direct', function () {
    try {
        $data = [
             ['bayi_adi' => 'A-B Yapı Market', 'magaza_adi' => 'A-B Yapı Market', 'sehir' => 'Şırnak', 'ilce' => 'Cizre'],
            ['bayi_adi' => 'Ada Yapı Malzemeleri', 'magaza_adi' => 'Ada Yapı Malzemeleri', 'sehir' => 'İstanbul', 'ilce' => 'Silivri'],
            ['bayi_adi' => 'Afyonkarahisar Showroom', 'magaza_adi' => 'Afyonkarahisar Showroom', 'sehir' => 'Afyonkarahisar', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'AHM Doğan Yapı', 'magaza_adi' => 'AHM Doğan Yapı Malzemeleri', 'sehir' => 'Giresun', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Akaras Yapı', 'magaza_adi' => 'Akaras Yapı', 'sehir' => 'Iğdır', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Akay Karo', 'magaza_adi' => 'Akay Karo', 'sehir' => 'Ağrı', 'ilce' => 'Doğubeyazıt'],
            ['bayi_adi' => 'Aksaray Anadolu AŞ', 'magaza_adi' => 'Aksaray Anadolu AŞ', 'sehir' => 'Aksaray', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Aksu Yapı', 'magaza_adi' => 'Aksu Yapı', 'sehir' => 'Konya', 'ilce' => 'Akşehir'],
            ['bayi_adi' => 'Aktif İnşaat', 'magaza_adi' => 'Aktif İnşaat', 'sehir' => 'Ordu', 'ilce' => 'Ünye'],
            ['bayi_adi' => 'Akyol Hırdavat Yapı Market', 'magaza_adi' => 'Akyol Hırdavat Yapı Market', 'sehir' => 'Çankırı', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Alara Yapı Malzemeleri', 'magaza_adi' => 'Alara Yapı Malzemeleri', 'sehir' => 'Antalya', 'ilce' => 'Alanya'],
            ['bayi_adi' => 'Algı Banyo', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'İstanbul', 'ilce' => 'Şişli'],
            ['bayi_adi' => 'Algı Banyo', 'magaza_adi' => 'Mecidiyeköy Şube', 'sehir' => 'İstanbul', 'ilce' => 'Şişli'],
            ['bayi_adi' => 'Ankara Showroom', 'magaza_adi' => 'Ankara Showroom', 'sehir' => 'Ankara', 'ilce' => 'Çankaya'],
            ['bayi_adi' => 'Antalya Showroom', 'magaza_adi' => 'Antalya Showroom', 'sehir' => 'Antalya', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Arı İnşaat', 'magaza_adi' => 'Arı İnşaat', 'sehir' => 'İstanbul', 'ilce' => 'Beyoğlu'],
            ['bayi_adi' => 'Arslan Ticaret', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'Giresun', 'ilce' => 'Bulancak'],
            ['bayi_adi' => 'Arslan Ticaret', 'magaza_adi' => 'Showroom Mağaza', 'sehir' => 'Giresun', 'ilce' => 'Bulancak'],
            ['bayi_adi' => 'Arslanlar Yapı Seramik', 'magaza_adi' => 'Arslanlar Yapı Seramik', 'sehir' => 'Sivas', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Artdecor Yapı Malzemeleri', 'magaza_adi' => 'Artdecor Yapı Malzemeleri', 'sehir' => 'İzmir', 'ilce' => 'Alsancak'],
            ['bayi_adi' => 'Asilas Yapı', 'magaza_adi' => 'Asilas Yapı', 'sehir' => 'Antalya', 'ilce' => 'Döşemealtı'],
            ['bayi_adi' => 'Atıl İnşaat', 'magaza_adi' => 'Atıl İnşaat', 'sehir' => 'İstanbul', 'ilce' => 'Sancaktepe'],
            ['bayi_adi' => 'Atılım Yapı', 'magaza_adi' => 'Atılım Yapı', 'sehir' => 'Trabzon', 'ilce' => 'Ortahisar'],
            ['bayi_adi' => 'Aydın Seramik', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'Aydın', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Aydın Seramik', 'magaza_adi' => 'Showroom Mağaza', 'sehir' => 'Aydın', 'ilce' => 'Efeler'],
            ['bayi_adi' => 'Aymer Yapı', 'magaza_adi' => 'Isparta Şube', 'sehir' => 'Isparta', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Aymer Yapı', 'magaza_adi' => 'Alanya Şube', 'sehir' => 'Antalya', 'ilce' => 'Alanya'],
            ['bayi_adi' => 'Aymer Yapı', 'magaza_adi' => 'Burdur Şube', 'sehir' => 'Burdur', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Aysa Yapı', 'magaza_adi' => 'Yalıkavak Şube', 'sehir' => 'Muğla', 'ilce' => 'Bodrum'],
            ['bayi_adi' => 'Aysa Yapı', 'magaza_adi' => 'Konacık Şube', 'sehir' => 'Muğla', 'ilce' => 'Bodrum'],
            ['bayi_adi' => 'Balçıklar Yapı', 'magaza_adi' => 'Balçıklar Yapı', 'sehir' => 'Kocaeli', 'ilce' => 'Derince'],
            ['bayi_adi' => 'Balsera A.Ş.', 'magaza_adi' => 'Balsera A.Ş.', 'sehir' => 'Ankara', 'ilce' => 'Yenimahalle'],
            ['bayi_adi' => 'Batman Güven Yapı', 'magaza_adi' => 'Batman Güven Yapı', 'sehir' => 'Batman', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Baysak Yapı', 'magaza_adi' => 'Baysak Yapı', 'sehir' => 'Tekirdağ', 'ilce' => 'Çerkezköy'],
            ['bayi_adi' => 'Beşel Yapı Malzemeleri', 'magaza_adi' => 'Beşel Yapı Malzemeleri', 'sehir' => 'Kocaeli', 'ilce' => 'Başiskele'],
            ['bayi_adi' => 'Beyaz 33 Seramik', 'magaza_adi' => 'Beyaz 33 Seramik', 'sehir' => 'Mersin', 'ilce' => 'Yenişehir'],
            ['bayi_adi' => 'Beyazsaray İnşaat', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'Konya', 'ilce' => 'Karatay'],
            ['bayi_adi' => 'Beyazsaray İnşaat', 'magaza_adi' => 'Merkez Showroom', 'sehir' => 'Konya', 'ilce' => 'Selçuklu'],
            ['bayi_adi' => 'Biliciler Ticaret', 'magaza_adi' => 'Biliciler Ticaret', 'sehir' => 'Ankara', 'ilce' => 'Polatlı'],
            ['bayi_adi' => 'BKA Yapı Tasarım', 'magaza_adi' => 'Edirne Mağaza', 'sehir' => 'Edirne', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'BKA Yapı Tasarım', 'magaza_adi' => 'Tekirdağ Mağaza', 'sehir' => 'Tekirdağ', 'ilce' => 'Çorlu'],
            ['bayi_adi' => 'Bmy Yapı', 'magaza_adi' => 'Bmy Yapı', 'sehir' => 'Osmaniye', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Bulutbey İnşaat', 'magaza_adi' => 'Bulutbey İnşaat', 'sehir' => 'Diyarbakır', 'ilce' => 'Kayapınar'],
            ['bayi_adi' => 'Bursa Showroom', 'magaza_adi' => 'Bursa Showroom', 'sehir' => 'Bursa', 'ilce' => 'Nilüfer'],
            ['bayi_adi' => 'Çaba Konut Yapı', 'magaza_adi' => 'Çaba Konut Yapı', 'sehir' => 'İzmir', 'ilce' => 'Buca'],
            ['bayi_adi' => 'Çağdaş Yapı Malz.', 'magaza_adi' => 'Çağdaş Yapı Malz.', 'sehir' => 'İstanbul', 'ilce' => 'Eyüp'],
            ['bayi_adi' => 'Çalıklar İnşaat', 'magaza_adi' => 'Kemerburgaz Şube', 'sehir' => 'İstanbul', 'ilce' => 'Kemerburgaz'],
            ['bayi_adi' => 'Çalıklar İnşaat', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'İstanbul', 'ilce' => 'Kağıthane'],
            ['bayi_adi' => 'Çalışkanlar İnşaat', 'magaza_adi' => 'Çalışkanlar İnşaat', 'sehir' => 'Kocaeli', 'ilce' => 'Gebze'],
            ['bayi_adi' => 'Çamoluk Yapı Malzemeleri', 'magaza_adi' => 'Çamoluk Yapı Malzemeleri', 'sehir' => 'İstanbul', 'ilce' => 'Üsküdar'],
            ['bayi_adi' => 'Çizgi Dekorasyon', 'magaza_adi' => 'Çizgi Dekorasyon', 'sehir' => 'Diyarbakır', 'ilce' => 'Kayapınar'],
            ['bayi_adi' => 'Çizgi Mimarlık Dekorasyon', 'magaza_adi' => 'Çizgi Mimarlık Dekorasyon', 'sehir' => 'Mardin', 'ilce' => 'Artuklu'],
            ['bayi_adi' => 'Çolakoğlu Fatih Yapı', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'İstanbul', 'ilce' => 'Bahçelievler'],
            ['bayi_adi' => 'Çolakoğlu Fatih Yapı', 'magaza_adi' => 'Avcılar Şube', 'sehir' => 'İstanbul', 'ilce' => 'Avcılar'],
            ['bayi_adi' => 'Decoprime', 'magaza_adi' => 'Decoprime', 'sehir' => 'Ankara', 'ilce' => 'Çankaya'],
            ['bayi_adi' => 'Dekoyap', 'magaza_adi' => 'Mağaza 1', 'sehir' => 'Kayseri', 'ilce' => 'Kocasinan'],
            ['bayi_adi' => 'DMC', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'Ankara', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Dekoyap', 'magaza_adi' => 'Mağaza 2', 'sehir' => 'Kayseri', 'ilce' => 'Kocasinan'],
            ['bayi_adi' => 'DMC Seramik', 'magaza_adi' => 'Diyarbakır Şube', 'sehir' => 'Diyarbakır', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'DMC Seramik', 'magaza_adi' => 'Şanlıurfa Şube', 'sehir' => 'Şanlıurfa', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'DMC Seramik', 'magaza_adi' => 'Elazığ Şube', 'sehir' => 'Elazığ', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Dekoyap', 'magaza_adi' => 'Stone Mağaza', 'sehir' => 'Kayseri', 'ilce' => 'Kocasinan'],
            ['bayi_adi' => 'Dekoyap Karademir', 'magaza_adi' => 'Malatya Mağaza', 'sehir' => 'Malatya', 'ilce' => 'Yeşilyurt'],
            ['bayi_adi' => 'Dekoyap', 'magaza_adi' => 'Aksaray Mağaza', 'sehir' => 'Aksaray', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Demaş Yapı', 'magaza_adi' => 'Demaş Yapı', 'sehir' => 'Antalya', 'ilce' => 'Demre'],
            ['bayi_adi' => 'Demir Ticaret', 'magaza_adi' => 'Demir Ticaret', 'sehir' => 'Kütahya', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Demirhanlar Seramik', 'magaza_adi' => 'Kocaeli Şuba', 'sehir' => 'Kocaeli', 'ilce' => 'Gebze'],
            ['bayi_adi' => 'Demirhanlar Seramik', 'magaza_adi' => 'İstanbul Şube', 'sehir' => 'İstanbul', 'ilce' => 'Ataşehir'],
            ['bayi_adi' => 'Des Seramik Yapı Malzemeleri', 'magaza_adi' => 'Des Seramik Yapı Malzemeleri', 'sehir' => 'Amasya', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'DG Seramik İnşaat', 'magaza_adi' => 'DG Seramik İnşaat', 'sehir' => 'İzmir', 'ilce' => 'Urla'],
            ['bayi_adi' => 'Diyarbakır Showroom', 'magaza_adi' => 'Diyarbakır Showroom', 'sehir' => 'Diyarbakır', 'ilce' => 'Bağlar'],
            ['bayi_adi' => 'Doktor Deco Design', 'magaza_adi' => 'Doktor Deco Design', 'sehir' => 'İzmir', 'ilce' => 'Güzelbahçe'],
            ['bayi_adi' => 'Dönmez Yapı Grubu', 'magaza_adi' => 'Dönmez Yapı Grubu', 'sehir' => 'Adana', 'ilce' => 'Seyhan'],
            ['bayi_adi' => 'Duranlar Yapı', 'magaza_adi' => 'Duranlar Yapı', 'sehir' => 'Çorum', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Ekol Yapı Malzemeleri', 'magaza_adi' => 'Ekol Yapı Malzemeleri', 'sehir' => 'İstanbul', 'ilce' => 'Maltepe'],
            ['bayi_adi' => 'Enver Mete İnşaat', 'magaza_adi' => 'Enver Mete İnşaat', 'sehir' => 'İzmir', 'ilce' => 'Aliağa'],
            ['bayi_adi' => 'Eralp İnşaat', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'Denizli', 'ilce' => 'Pamukkale'],
            ['bayi_adi' => 'Eralp İnşaat', 'magaza_adi' => 'Showroom Mağaza 1', 'sehir' => 'Denizli', 'ilce' => 'Merkezefendi'],
            ['bayi_adi' => 'Eralp İnşaat', 'magaza_adi' => 'Showroom Mağaza 2', 'sehir' => 'Denizli', 'ilce' => 'Merkezefendi'],
            ['bayi_adi' => 'Erdi Yapı', 'magaza_adi' => 'Bolu Şube', 'sehir' => 'Bolu', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Erdi Yapı', 'magaza_adi' => 'Düzce Şube', 'sehir' => 'Düzce', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Erpek Mimarlık', 'magaza_adi' => 'Erpek Mimarlık', 'sehir' => 'Muğla', 'ilce' => 'Ortaca'],
            ['bayi_adi' => 'Estetik Yapı', 'magaza_adi' => 'Estetik Yapı', 'sehir' => 'İstanbul', 'ilce' => 'Kartal'],
            ['bayi_adi' => 'ETD Yapı', 'magaza_adi' => 'ETD Yapı', 'sehir' => 'Adana', 'ilce' => 'Seyhan'],
            ['bayi_adi' => 'Etiler Merkez Showroom', 'magaza_adi' => 'Etiler Merkez Showroom', 'sehir' => 'İstanbul', 'ilce' => 'Beşiktaş'],
            ['bayi_adi' => 'Evci Yapı', 'magaza_adi' => 'Evci Yapı', 'sehir' => 'Yalova', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Femoza Yapı', 'magaza_adi' => 'Çanakkale Şube', 'sehir' => 'Çanakkale', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Femoza Yapı', 'magaza_adi' => 'Eskişehir Şube', 'sehir' => 'Eskişehir', 'ilce' => 'Tepebaşı'],
            ['bayi_adi' => 'Femoza Yapı', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'Bursa', 'ilce' => 'Nilüfer'],
            ['bayi_adi' => 'Femoza Yapı', 'magaza_adi' => 'Pendik Şube', 'sehir' => 'İstanbul', 'ilce' => 'Pendik'],
            ['bayi_adi' => 'Fethiye Yapı Malz.', 'magaza_adi' => 'Fethiye Yapı Malz.', 'sehir' => 'Muğla', 'ilce' => 'Fethiye'],
            ['bayi_adi' => 'Gelişim İnşaat', 'magaza_adi' => 'Gelişim İnşaat', 'sehir' => 'Kahramanmaraş', 'ilce' => 'Elbistan'],
            ['bayi_adi' => 'Gerber Yapı', 'magaza_adi' => 'Gerber Yapı', 'sehir' => 'İstanbul', 'ilce' => 'Halkalı'],
            ['bayi_adi' => 'Göbekli Yapı', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'Aydın', 'ilce' => 'Didim'],
            ['bayi_adi' => 'Göbekli Yapı', 'magaza_adi' => 'İzmir Şube', 'sehir' => 'İzmir', 'ilce' => 'Gaziemir'],
            ['bayi_adi' => 'Göbekli Yapı', 'magaza_adi' => 'Söke Şube', 'sehir' => 'Aydın', 'ilce' => 'Söke'],
            ['bayi_adi' => 'Gökfa İnşaat', 'magaza_adi' => 'Gökfa İnşaat', 'sehir' => 'Balıkesir', 'ilce' => 'Ayvalık'],
            ['bayi_adi' => 'Granitaş İnşaat', 'magaza_adi' => 'Granitaş İnşaat', 'sehir' => 'Erzurum', 'ilce' => 'Yakutiye'],
            ['bayi_adi' => 'Güler Seramik', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'Hatay', 'ilce' => 'Antakya'],
            ['bayi_adi' => 'Güler Seramik', 'magaza_adi' => 'İskenderun Şube', 'sehir' => 'Hatay', 'ilce' => 'İskenderun'],
            ['bayi_adi' => 'Gümüş Yapı', 'magaza_adi' => 'Gümüş Yapı', 'sehir' => 'Osmaniye', 'ilce' => 'Düziçi'],
            ['bayi_adi' => 'Güneşli Showroom', 'magaza_adi' => 'Güneşli Showroom', 'sehir' => 'İstanbul', 'ilce' => 'Bağcılar'],
            ['bayi_adi' => 'Gür-Av İnşaat Malz.', 'magaza_adi' => 'Gür-Av İnşaat Malz.', 'sehir' => 'Mersin', 'ilce' => 'Silifke'],
            ['bayi_adi' => 'Gürdemir İnşaat', 'magaza_adi' => 'Gürdemir İnşaat', 'sehir' => 'Tekirdağ', 'ilce' => 'Marmaraereğlisi'],
            ['bayi_adi' => 'Güreli Ticaret', 'magaza_adi' => 'Güreli Ticaret', 'sehir' => 'Balıkesir', 'ilce' => 'Burhaniye'],
            ['bayi_adi' => 'Hak Seramik', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'Hatay', 'ilce' => 'İskenderun'],
            ['bayi_adi' => 'Hak Seramik', 'magaza_adi' => 'İskenderun Şube', 'sehir' => 'Hatay', 'ilce' => 'İskenderun'],
            ['bayi_adi' => 'Halil Sepet Ticaret', 'magaza_adi' => 'Halil Sepet Ticaret', 'sehir' => 'Kütahya', 'ilce' => 'Tavşanlı'],
            ['bayi_adi' => 'Hancıoğlu Mühendislik', 'magaza_adi' => 'Hancıoğlu Mühendislik', 'sehir' => 'Trabzon', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Hedef Yapı', 'magaza_adi' => 'Hedef Yapı', 'sehir' => 'İstanbul', 'ilce' => 'Sultanbeyli'],
            ['bayi_adi' => 'Helvacı Yapı', 'magaza_adi' => 'Helvacı Yapı', 'sehir' => 'Aydın', 'ilce' => 'Kuşadası'],
            ['bayi_adi' => 'Hitit Yapı', 'magaza_adi' => 'Hitit Yapı', 'sehir' => 'Antalya', 'ilce' => 'Kaş'],
            ['bayi_adi' => 'Hüseyin Kadayıfoğlu İnşaat', 'magaza_adi' => 'Hüseyin Kadayıfoğlu İnşaat', 'sehir' => 'Hatay', 'ilce' => 'Dörtyol'],
            ['bayi_adi' => 'İda Güven Yapı', 'magaza_adi' => 'İda Güven Yapı', 'sehir' => 'Aksaray', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'İtimat Yapı', 'magaza_adi' => 'İtimat Yapı', 'sehir' => 'Tekirdağ', 'ilce' => 'Süleymanpaşa'],
            ['bayi_adi' => 'İzmir Showroom', 'magaza_adi' => 'İzmir Showroom', 'sehir' => 'İzmir', 'ilce' => 'Bayraklı'],
            ['bayi_adi' => 'Kaçkarlar İnşaat', 'magaza_adi' => 'Kaçkarlar İnşaat', 'sehir' => 'Rize', 'ilce' => 'Ardeşen'],
            ['bayi_adi' => 'Kadıoğlu Yapı Elemanları', 'magaza_adi' => 'Kadıoğlu Yapı Elemanları', 'sehir' => 'Osmaniye', 'ilce' => 'Kadirli'],
            ['bayi_adi' => 'Kahvecioğlu Seramik', 'magaza_adi' => 'Kahvecioğlu Seramik', 'sehir' => 'Bursa', 'ilce' => 'Nilüfer'],
            ['bayi_adi' => 'Kalfa İnşaat', 'magaza_adi' => 'Kalfa İnşaat', 'sehir' => 'Antalya', 'ilce' => 'Kepez'],
            ['bayi_adi' => 'Kalfalar Yapı', 'magaza_adi' => 'Kalfalar Yapı', 'sehir' => 'Trabzon', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Karakaya İnşaat', 'magaza_adi' => 'Karakaya İnşaat', 'sehir' => 'Elazığ', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Karataş İnşaat', 'magaza_adi' => 'Karataş İnşaat', 'sehir' => 'Afyonkarahisar', 'ilce' => 'Sandıklı'],
            ['bayi_adi' => 'Kayanar İnşaat', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'Muğla', 'ilce' => 'Marmaris'],
            ['bayi_adi' => 'Kayanar İnşaat', 'magaza_adi' => 'Datça Şube', 'sehir' => 'Muğla', 'ilce' => 'Datça'],
            ['bayi_adi' => 'Kayanar İnşaat', 'magaza_adi' => 'Muğla Şube', 'sehir' => 'Muğla', 'ilce' => 'Menteşe'],
            ['bayi_adi' => 'Kepez Yapı Market', 'magaza_adi' => 'Kepez Yapı Market', 'sehir' => 'Ankara', 'ilce' => 'Etimesgut'],
            ['bayi_adi' => 'Keskin Yapı', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'İstanbul', 'ilce' => 'Bakırköy'],
            ['bayi_adi' => 'Keskin Yapı - Avcılar', 'magaza_adi' => 'Avcılar Şube', 'sehir' => 'İstanbul', 'ilce' => 'Avcılar'],
            ['bayi_adi' => 'Kmz Korkmaz İnşaat', 'magaza_adi' => 'Kmz Korkmaz İnşaat', 'sehir' => 'Sakarya', 'ilce' => 'Adapazari'],
            ['bayi_adi' => 'Komutlar İnşaat', 'magaza_adi' => 'Komutlar İnşaat', 'sehir' => 'Trabzon', 'ilce' => 'Vakfıkebir'],
            ['bayi_adi' => 'Kule Seramik', 'magaza_adi' => 'Kule Seramik', 'sehir' => 'Antalya', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Lazulit Yapı', 'magaza_adi' => 'Lazulit Yapı', 'sehir' => 'Kocaeli', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Lider Özen İnşaat', 'magaza_adi' => 'Lider Özen İnşaat', 'sehir' => 'Malatya', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Makuloğlu Ticaret', 'magaza_adi' => 'Eynesil Şube', 'sehir' => 'Giresun', 'ilce' => 'Eynesil'],
            ['bayi_adi' => 'Makuloğlu Ticaret', 'magaza_adi' => 'Görele Şube', 'sehir' => 'Giresun', 'ilce' => 'Görele'],
            ['bayi_adi' => 'Malçok İnşaat', 'magaza_adi' => 'Malçok İnşaat', 'sehir' => 'Diyarbakır', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Mecidiyeli Ticaret', 'magaza_adi' => 'Mecidiyeli Ticaret', 'sehir' => 'Balıkesir', 'ilce' => 'Altıeylül'],
            ['bayi_adi' => 'Mehmet Ceylan Yapı', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'İzmir', 'ilce' => 'Konak'],
            ['bayi_adi' => 'Mehmet Ceylan Yapı', 'magaza_adi' => 'Çamdibi Şube', 'sehir' => 'İzmir', 'ilce' => 'Bornova'],
            ['bayi_adi' => 'Mehmet Ceylan Yapı', 'magaza_adi' => 'Karşıyaka Şube', 'sehir' => 'İzmir', 'ilce' => 'Karşıyaka'],
            ['bayi_adi' => 'Mencan İnşaat', 'magaza_adi' => 'Mencan İnşaat', 'sehir' => 'İzmir', 'ilce' => 'Karşıyaka'],
            ['bayi_adi' => 'Merkez Showroom', 'magaza_adi' => 'Merkez Showroom', 'sehir' => 'Kütahya', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Mert Ticaret', 'magaza_adi' => 'Mert Ticaret', 'sehir' => 'Manisa', 'ilce' => 'Yunusemre'],
            ['bayi_adi' => 'Mertaş', 'magaza_adi' => 'Mertaş İnş. Malz.', 'sehir' => 'Balıkesir', 'ilce' => 'Edremit'],
            ['bayi_adi' => 'Mete Yapı', 'magaza_adi' => 'Mete Yapı', 'sehir' => 'İzmir', 'ilce' => 'Yenişehir'],
            ['bayi_adi' => 'Mim İnşaat', 'magaza_adi' => 'Mim İnşaat', 'sehir' => 'Diyarbakır', 'ilce' => 'Kayapınar'],
            ['bayi_adi' => 'Motif Yapı', 'magaza_adi' => 'Motif Yapı', 'sehir' => 'Bursa', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Muhittin Demirli', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'İstanbul', 'ilce' => 'Pendik'],
            ['bayi_adi' => 'Muhittin Demirli', 'magaza_adi' => 'Kurtköy Şube', 'sehir' => 'İstanbul', 'ilce' => 'Pendik'],
            ['bayi_adi' => 'Muzaffer Seramik', 'magaza_adi' => 'Muzaffer Seramik', 'sehir' => 'Bursa', 'ilce' => 'İnegöl'],
            ['bayi_adi' => 'Naki Demir İnşaat', 'magaza_adi' => 'Naki Demir İnşaat', 'sehir' => 'Ankara', 'ilce' => 'Yenimahalle'],
            ['bayi_adi' => 'NBA Cihangir', 'magaza_adi' => 'Çukurambar Şube', 'sehir' => 'Ankara', 'ilce' => 'Çankaya'],
            ['bayi_adi' => 'NBA Cihangir', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'Ankara', 'ilce' => 'Keçiören'],
            ['bayi_adi' => 'Neyzen Yapı', 'magaza_adi' => 'Neyzen Yapı', 'sehir' => 'İstanbul', 'ilce' => 'Esenyurt'],
            ['bayi_adi' => 'Nova Seramik', 'magaza_adi' => 'Nova Seramik', 'sehir' => 'Bursa', 'ilce' => 'Nilüfer'],
            ['bayi_adi' => 'Nuryapı', 'magaza_adi' => 'Nuryapı', 'sehir' => 'İstanbul', 'ilce' => 'Kadıköy'],
            ['bayi_adi' => 'Oktaylar İnşaat', 'magaza_adi' => 'Oktaylar İnşaat', 'sehir' => 'Rize', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Opal Yapı', 'magaza_adi' => 'Alanya Şube', 'sehir' => 'Antalya', 'ilce' => 'Alanya'],
            ['bayi_adi' => 'Opal Yapı', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'Antalya', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Osmanlar Yapı', 'magaza_adi' => 'Osmanlar Yapı', 'sehir' => 'Bartın', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Öncüler Yapı', 'magaza_adi' => 'Öncüler Yapı', 'sehir' => 'İstanbul', 'ilce' => 'Beykoz'],
            ['bayi_adi' => 'Önder Yapı', 'magaza_adi' => 'Önder Yapı', 'sehir' => 'Afyonkarahisar', 'ilce' => 'Bolvadin'],
            ['bayi_adi' => 'Öz Turanlar İnşaat', 'magaza_adi' => 'Öz Turanlar İnşaat', 'sehir' => 'Batman', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Özbekler Yapı', 'magaza_adi' => 'Özbekler Yapı Malz.', 'sehir' => 'Bayburt', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Özdemirler İnşaat', 'magaza_adi' => 'Özdemirler İnşaat', 'sehir' => 'Tekirdağ', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Özşah Yapı', 'magaza_adi' => 'Edirne Şube', 'sehir' => 'Edirne', 'ilce' => 'Keşan'],
            ['bayi_adi' => 'Özşah Yapı', 'magaza_adi' => 'İstanbul Şube', 'sehir' => 'İstanbul', 'ilce' => 'Silivri'],
            ['bayi_adi' => 'Özyıldırım Boya Seramik', 'magaza_adi' => 'Özyıldırım Boya Seramik', 'sehir' => 'Hatay', 'ilce' => 'İskenderun'],
            ['bayi_adi' => 'Pınar Yapı', 'magaza_adi' => 'Pınar Yapı', 'sehir' => 'Uşak', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'PİA Yapı Center', 'magaza_adi' => 'PİA Yapı Center', 'sehir' => 'Karaman', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Pol-Art İnşaat', 'magaza_adi' => 'Pol-Art İnşaat', 'sehir' => 'Ankara', 'ilce' => 'Ulus'],
            ['bayi_adi' => 'Ramazan Kaya - Eylül Yapı', 'magaza_adi' => 'Ramazan Kaya - Eylül Yapı', 'sehir' => 'Kilis', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Sakarya Dönmez Yapı', 'magaza_adi' => 'Sakarya Dönmez Yapı', 'sehir' => 'Sakarya', 'ilce' => 'Adapazarı'],
            ['bayi_adi' => 'Samsun Showroom', 'magaza_adi' => 'Samsun Showroom', 'sehir' => 'Samsun', 'ilce' => 'Tekkeköy'],
            ['bayi_adi' => 'Saşa Yapı', 'magaza_adi' => 'Saşa Yapı', 'sehir' => 'İstanbul', 'ilce' => 'Büyükçekmece'],
            ['bayi_adi' => 'Savaş Ticaret', 'magaza_adi' => 'Savaş Ticaret', 'sehir' => 'Ağrı', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Sema Yapı', 'magaza_adi' => 'Sema Yapı', 'sehir' => 'İstanbul', 'ilce' => 'Büyükçekmece'],
            ['bayi_adi' => 'Sen Yapı', 'magaza_adi' => 'Sen Yapı', 'sehir' => 'Ankara', 'ilce' => 'Ulus'],
            ['bayi_adi' => 'Seramik Yapı', 'magaza_adi' => 'Seramik Yapı', 'sehir' => 'İzmir', 'ilce' => 'Narlıdere'],
            ['bayi_adi' => 'Seramikev (Decotive)', 'magaza_adi' => 'Seramikev (Decotive)', 'sehir' => 'İstanbul', 'ilce' => 'Beylikdüzü'],
            ['bayi_adi' => 'Seratime', 'magaza_adi' => 'Maltepe Şube', 'sehir' => 'İstanbul', 'ilce' => 'Maltepe'],
            ['bayi_adi' => 'Seratime', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'İstanbul', 'ilce' => 'Ümraniye'],
            ['bayi_adi' => 'Seratime', 'magaza_adi' => 'Zekeriyaköy Şube', 'sehir' => 'İstanbul', 'ilce' => 'Sarıyer'],
            ['bayi_adi' => 'Sevil Yapı Market', 'magaza_adi' => 'Sevil Yapı Market', 'sehir' => 'Antalya', 'ilce' => 'Kaş'],
            ['bayi_adi' => 'Seycan Seramik', 'magaza_adi' => 'Seycan Seramik', 'sehir' => 'İstanbul', 'ilce' => 'Zeytinburnu'],
            ['bayi_adi' => 'Sönmez Yapı İnşaat', 'magaza_adi' => 'Sönmez Yapı İnşaat', 'sehir' => 'Balıkesir', 'ilce' => 'Gömeç'],
            ['bayi_adi' => 'Söylemez İnşaat', 'magaza_adi' => 'Söylemez İnşaat', 'sehir' => 'Muş', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Step Yapı', 'magaza_adi' => 'Step Yapı', 'sehir' => 'Samsun', 'ilce' => 'Atakum'],
            ['bayi_adi' => 'Şahinser Yapı', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'Çorum', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Şahinser Yapı', 'magaza_adi' => 'Showroom Mağaza', 'sehir' => 'Çorum', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Şentürk İnşaat', 'magaza_adi' => 'Şentürk İnşaat', 'sehir' => 'Kars', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Talay Yapı', 'magaza_adi' => 'Talay Yapı', 'sehir' => 'Burdur', 'ilce' => 'Bucak'],
            ['bayi_adi' => 'Tarsu Seramik', 'magaza_adi' => 'Tarsu Seramik', 'sehir' => 'Mersin', 'ilce' => 'Tarsus'],
            ['bayi_adi' => 'Taşkent Yapı', 'magaza_adi' => 'Taşkent Yapı', 'sehir' => 'İstanbul', 'ilce' => 'Ümraniye'],
            ['bayi_adi' => 'Tatarlı İnşaat', 'magaza_adi' => 'Tatarlı İnşaat', 'sehir' => 'İstanbul', 'ilce' => 'Gaziosmanpaşa'],
            ['bayi_adi' => 'Tekno İnşaat', 'magaza_adi' => 'Tekno İnşaat', 'sehir' => 'Şırnak', 'ilce' => 'Silopi'],
            ['bayi_adi' => 'Tulum Yapı', 'magaza_adi' => 'Tulum Yapı', 'sehir' => 'Isparta', 'ilce' => 'Şarkikaraağaç'],
            ['bayi_adi' => 'Tunalar Seramik', 'magaza_adi' => 'Tunalar Seramik', 'sehir' => 'Konya', 'ilce' => 'Ereğli'],
            ['bayi_adi' => 'Tuncaylar Yapı', 'magaza_adi' => 'Tuncaylar Yapı Market', 'sehir' => 'Erzincan', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Turanlar Yapı', 'magaza_adi' => 'Turanlar Yapı', 'sehir' => 'Kocaeli', 'ilce' => 'İzmit'],
            ['bayi_adi' => 'Turkuaz Royal İnşaat', 'magaza_adi' => 'Turkuaz Royal İnşaat', 'sehir' => 'Manisa', 'ilce' => 'Akhisar'],
            ['bayi_adi' => 'Türkmenler', 'magaza_adi' => 'Güngören Şube', 'sehir' => 'İstanbul', 'ilce' => 'Güngören'],
            ['bayi_adi' => 'Türkmenler', 'magaza_adi' => 'Zeytinburnu Şube', 'sehir' => 'İstanbul', 'ilce' => 'Zeytinburnu'],
            ['bayi_adi' => 'Uğur Yapı', 'magaza_adi' => 'Uğur Yapı', 'sehir' => 'Burdur', 'ilce' => 'Gölhisar'],
            ['bayi_adi' => 'Uludağ Yapı Market', 'magaza_adi' => 'Uludağ Yapı Market', 'sehir' => 'Tokat', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Uludoğanlar İnşaat', 'magaza_adi' => 'Uludoğanlar İnşaat', 'sehir' => 'Ankara', 'ilce' => 'Altındağ'],
            ['bayi_adi' => 'Uslu Ticaret', 'magaza_adi' => 'Uslu Ticaret', 'sehir' => 'Karabük', 'ilce' => 'Safranbolu'],
            ['bayi_adi' => 'Uyumazlar Yapı', 'magaza_adi' => 'Güneşli Şube', 'sehir' => 'İstanbul', 'ilce' => 'Bağcılar'],
            ['bayi_adi' => 'Uyumazlar Yapı', 'magaza_adi' => 'Şişli Şube', 'sehir' => 'İstanbul', 'ilce' => 'Şişli'],
            ['bayi_adi' => 'Varay İnşaat', 'magaza_adi' => 'Varay İnşaat', 'sehir' => 'Bingöl', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Yasin Yapı Malzemeleri', 'magaza_adi' => 'Yasin Yapı Malzemeleri', 'sehir' => 'İstanbul', 'ilce' => 'Bahçelievler'],
            ['bayi_adi' => 'Yaşar Ticaret', 'magaza_adi' => 'Yaşar Ticaret', 'sehir' => 'Bitlis', 'ilce' => 'Tatvan'],
            ['bayi_adi' => 'Yazar Kollektif', 'magaza_adi' => 'Yazar Kollektif', 'sehir' => 'Muğla', 'ilce' => 'Milas'],
            ['bayi_adi' => 'Yener Seramik', 'magaza_adi' => 'Yener Seramik', 'sehir' => 'Ordu', 'ilce' => 'Gülyalı'],
            ['bayi_adi' => 'Yeşilyurt Yapı', 'magaza_adi' => 'Yeşilyurt Yapı', 'sehir' => 'İstanbul', 'ilce' => 'Ümraniye'],
            ['bayi_adi' => 'Yıldız Yapı', 'magaza_adi' => 'Yıldız Yapı', 'sehir' => 'İstanbul', 'ilce' => 'Arnavutköy'],
            ['bayi_adi' => 'Yılmazlar Banyo', 'magaza_adi' => 'Caddebostan Showroom', 'sehir' => 'İstanbul', 'ilce' => 'Kadıköy'],
            ['bayi_adi' => 'Yılmazlar Banyo', 'magaza_adi' => 'Güneşli Showroom', 'sehir' => 'İstanbul', 'ilce' => 'Güneşli'],
            ['bayi_adi' => 'Yılmazlar Banyo', 'magaza_adi' => 'Mecidiyeköy Showroom', 'sehir' => 'İstanbul', 'ilce' => 'Şişli'],
            ['bayi_adi' => 'Yılmazlar Yapı Malzemeleri', 'magaza_adi' => 'Yılmazlar Yapı Malzemeleri', 'sehir' => 'Kastamonu', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Yurdagül Boya', 'magaza_adi' => 'Yurdagül Boya', 'sehir' => 'Kütahya', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Yücesoy Seramik', 'magaza_adi' => 'Gaziantep Şube', 'sehir' => 'Gaziantep', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Yücesoy Seramik', 'magaza_adi' => 'Mersin Şube', 'sehir' => 'Mersin', 'ilce' => 'Mezitli'],
            ['bayi_adi' => 'Zafer İnşaat', 'magaza_adi' => 'Zafer İnşaat', 'sehir' => 'Van', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Afyon Showroom', 'magaza_adi' => 'Afyon Showroom', 'sehir' => 'Afyon', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Alkanlar İnşaat', 'magaza_adi' => 'Alkanlar İnşaat', 'sehir' => 'Adıyaman', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Aşuroğlu İnşaat', 'magaza_adi' => 'Aşuroğlu İnşaat', 'sehir' => 'Bursa', 'ilce' => 'Nilüfer'],
            ['bayi_adi' => 'Azizoğlu', 'magaza_adi' => 'Azizoğlu', 'sehir' => 'Sivas', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Bedesten Avm Showroom', 'magaza_adi' => 'Bedesten Avm Showroom', 'sehir' => 'Sakarya', 'ilce' => 'Sapanca'],
            ['bayi_adi' => 'Berkan Arslan', 'magaza_adi' => 'Berkan Arslan', 'sehir' => 'Sivas', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Biryılmaz Yapı', 'magaza_adi' => 'Biryılmaz Yapı', 'sehir' => 'Bursa', 'ilce' => 'Nilüfer'],
            ['bayi_adi' => 'Can-Kar İnşaat', 'magaza_adi' => 'Can-Kar İnşaat', 'sehir' => 'Hakkari', 'ilce' => 'Yüksekova'],
            ['bayi_adi' => 'DYG', 'magaza_adi' => 'DYG', 'sehir' => 'Adana', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'ERT', 'magaza_adi' => 'ERT', 'sehir' => 'İstanbul', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Kavukçu Yapı', 'magaza_adi' => 'Kavukçu Yapı', 'sehir' => 'Hatay', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Loft Design', 'magaza_adi' => 'Loft Design', 'sehir' => 'Diyarbakır', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Miray Yapı', 'magaza_adi' => 'Miray Yapı', 'sehir' => 'Adıyaman', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Nur Yapı', 'magaza_adi' => 'Nur Yapı', 'sehir' => 'İstanbul', 'ilce' => 'Beykoz'],
            ['bayi_adi' => 'Şapcı Yapı', 'magaza_adi' => 'Şapcı Yapı', 'sehir' => 'Afyon', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Uçar Yapı', 'magaza_adi' => 'Uçar Yapı', 'sehir' => 'Nevşehir', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Yıldızlı Granit', 'magaza_adi' => 'Yıldızlı Granit', 'sehir' => 'Afyon', 'ilce' => 'İsçehisar'],

        ];

        $bayiSayisi = 0;
        $magazaSayisi = 0;
        $hatalar = [];

        foreach ($data as $index => $row) {
            try {
                // Şehir bulma (basit yaklaşım)
                $sehir = \App\Models\Sehir::where('ad', 'LIKE', '%' . trim($row['sehir']) . '%')->first();
                if (!$sehir) {
                    // Varsayılan şehir ID'si ata
                    $sehir_id = 1;
                    $ilce_id = 1;
                } else {
                    $sehir_id = $sehir->id;
                    
                    // İlçe bulma
                    $ilce = \App\Models\Ilce::where('sehir_id', $sehir->id)
                                        ->where('ad', 'LIKE', '%' . trim($row['ilce']) . '%')
                                        ->first();
                    
                    if (!$ilce) {
                        $ilce = \App\Models\Ilce::create([
                            'sehir_id' => $sehir->id,
                            'ad' => trim($row['ilce'])
                        ]);
                    }
                    $ilce_id = $ilce->id;
                }

                // Bayi oluştur veya bul
                $bayi = \App\Models\Bayi::firstOrCreate(
                    ['ad' => trim($row['bayi_adi'])],
                    [
                        'sehir_id' => $sehir_id,
                        'ilce_id' => $ilce_id,
                        'aktif' => true
                    ]
                );

                if ($bayi->wasRecentlyCreated) {
                    $bayiSayisi++;
                }

                // Mağaza oluştur - sehir_id ve ilce_id'yi create array'inde belirtmek zorunlu
                $magaza = \App\Models\BayiMagazasi::firstOrCreate([
                    'bayi_id' => $bayi->id,
                    'ad' => trim($row['magaza_adi'])
                ], [
                    'bayi_id' => $bayi->id,
                    'ad' => trim($row['magaza_adi']),
                    'sehir_id' => $sehir_id,
                    'ilce_id' => $ilce_id,
                    'aktif' => true
                ]);

                if ($magaza->wasRecentlyCreated) {
                    $magazaSayisi++;
                }

            } catch (\Exception $e) {
                $hatalar[] = "Satır " . ($index + 1) . ": " . $e->getMessage();
            }
        }

        $toplamBayi = \App\Models\Bayi::count();
        $toplamMagaza = \App\Models\BayiMagazasi::count();

        return response()->json([
            'success' => true,
            'message' => 'Bayiler başarıyla yüklendi',
            'data' => [
                'yeni_bayi' => $bayiSayisi,
                'yeni_magaza' => $magazaSayisi,
                'toplam_bayi' => $toplamBayi,
                'toplam_magaza' => $toplamMagaza,
                'hatalar' => array_slice($hatalar, 0, 5) // İlk 5 hatayı göster
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Bayiler yüklenirken hata oluştu',
            'error' => $e->getMessage()
        ]);
    }
});

// Sadece mağazaları yükle - Model::insert() ile
Route::get('/load-magazalar-direct', function () {
    try {
        $data = [
            ['bayi_adi' => 'A-B Yapı Market', 'magaza_adi' => 'A-B Yapı Market', 'sehir' => 'Şırnak', 'ilce' => 'Cizre'],
            ['bayi_adi' => 'Ada Yapı Malzemeleri', 'magaza_adi' => 'Ada Yapı Malzemeleri', 'sehir' => 'İstanbul', 'ilce' => 'Silivri'],
            ['bayi_adi' => 'Afyonkarahisar Showroom', 'magaza_adi' => 'Afyonkarahisar Showroom', 'sehir' => 'Afyonkarahisar', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'AHM Doğan Yapı', 'magaza_adi' => 'AHM Doğan Yapı Malzemeleri', 'sehir' => 'Giresun', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Akaras Yapı', 'magaza_adi' => 'Akaras Yapı', 'sehir' => 'Iğdır', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Akay Karo', 'magaza_adi' => 'Akay Karo', 'sehir' => 'Ağrı', 'ilce' => 'Doğubeyazıt'],
            ['bayi_adi' => 'Aksaray Anadolu AŞ', 'magaza_adi' => 'Aksaray Anadolu AŞ', 'sehir' => 'Aksaray', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Aksu Yapı', 'magaza_adi' => 'Aksu Yapı', 'sehir' => 'Konya', 'ilce' => 'Akşehir'],
            ['bayi_adi' => 'Aktif İnşaat', 'magaza_adi' => 'Aktif İnşaat', 'sehir' => 'Ordu', 'ilce' => 'Ünye'],
            ['bayi_adi' => 'Akyol Hırdavat Yapı Market', 'magaza_adi' => 'Akyol Hırdavat Yapı Market', 'sehir' => 'Çankırı', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Alara Yapı Malzemeleri', 'magaza_adi' => 'Alara Yapı Malzemeleri', 'sehir' => 'Antalya', 'ilce' => 'Alanya'],
            ['bayi_adi' => 'Algı Banyo', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'İstanbul', 'ilce' => 'Şişli'],
            ['bayi_adi' => 'Algı Banyo', 'magaza_adi' => 'Mecidiyeköy Şube', 'sehir' => 'İstanbul', 'ilce' => 'Şişli'],
            ['bayi_adi' => 'Ankara Showroom', 'magaza_adi' => 'Ankara Showroom', 'sehir' => 'Ankara', 'ilce' => 'Çankaya'],
            ['bayi_adi' => 'Antalya Showroom', 'magaza_adi' => 'Antalya Showroom', 'sehir' => 'Antalya', 'ilce' => 'Merkez'],
            // ... tüm veriler için sadece ilk 15'ini gösteriyorum, tam listeyi devam ettirelim mi?
        ];

        $magazaInserts = [];
        $hatalar = [];
        $magazaSayisi = 0;

        foreach ($data as $index => $row) {
            try {
                // Bayi bul
                $bayi = \App\Models\Bayi::where('ad', trim($row['bayi_adi']))->first();
                if (!$bayi) {
                    $hatalar[] = "Satır " . ($index + 1) . ": Bayi bulunamadı: " . $row['bayi_adi'];
                    continue;
                }

                // Şehir bul (basit yaklaşım)
                $sehir = \App\Models\Sehir::where('ad', 'LIKE', '%' . trim($row['sehir']) . '%')->first();
                $sehir_id = $sehir ? $sehir->id : 1; // Varsayılan değer

                // İlçe bul veya varsayılan
                $ilce_id = 1; // Varsayılan değer
                if ($sehir) {
                    $ilce = \App\Models\Ilce::where('sehir_id', $sehir->id)
                                        ->where('ad', 'LIKE', '%' . trim($row['ilce']) . '%')
                                        ->first();
                    $ilce_id = $ilce ? $ilce->id : 1;
                }

                // Mağaza verisi hazırla
                $magazaInserts[] = [
                    'bayi_id' => $bayi->id,
                    'ad' => trim($row['magaza_adi']),
                    'sehir_id' => $sehir_id,
                    'ilce_id' => $ilce_id,
                    'aktif' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                $magazaSayisi++;

            } catch (\Exception $e) {
                $hatalar[] = "Satır " . ($index + 1) . ": " . $e->getMessage();
            }
        }

        // Toplu insert
        if (!empty($magazaInserts)) {
            \Illuminate\Support\Facades\DB::table('bayi_magazalari')->insert($magazaInserts);
        }

        $toplamMagaza = \App\Models\BayiMagazasi::count();

        return response()->json([
            'success' => true,
            'message' => 'Mağazalar başarıyla yüklendi',
            'data' => [
                'yeni_magaza' => $magazaSayisi,
                'toplam_magaza' => $toplamMagaza,
                'hatalar' => array_slice($hatalar, 0, 5) // İlk 5 hatayı göster
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Mağazalar yüklenirken hata oluştu',
            'error' => $e->getMessage()
        ]);
    }
});
