<?php

/**
 * Railway database'den bayiler tablosunu CSV olarak export etme script'i
 * 
 * Kullanım:
 * php artisan tinker
 * include 'database/scripts/export_bayiler_csv.php';
 * 
 * Veya doğrudan:
 * cd backend && php database/scripts/export_bayiler_csv.php
 */

// Laravel bootstrap
require_once __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Bayi;
use Illuminate\Support\Facades\DB;

echo "🔍 Railway database'den bayiler tablosunu CSV'ye çıkarıyorum...\n";

try {
    // Tüm bayileri al (ID'ye göre sıralı)
    $bayiler = Bayi::select([
        'id',
        'ad', 
        'sahip_adi',
        'sahip_telefon',
        'sahip_email',
        'sehir_id',
        'ilce_id',
        'aktif',
        'created_at',
        'updated_at'
    ])->orderBy('id', 'asc')->get();

    echo "📊 Toplam bayi sayısı: " . $bayiler->count() . "\n";

    if ($bayiler->count() == 0) {
        echo "❌ Hiç bayi kaydı bulunamadı!\n";
        exit(1);
    }

    // CSV dosyası oluştur
    $csvFile = '/Users/ahmetozdemir/Documents/Projects/db-aktarim/railway_bayiler.csv';
    $csvHandle = fopen($csvFile, 'w');

    if (!$csvHandle) {
        echo "❌ CSV dosyası oluşturulamadı!\n";
        exit(1);
    }

    // UTF-8 BOM ekle (Excel için)
    fwrite($csvHandle, "\xEF\xBB\xBF");

    // CSV başlıkları
    $headers = [
        'id',
        'ad',
        'sahip_adi', 
        'sahip_telefon',
        'sahip_email',
        'sehir_id',
        'ilce_id',
        'aktif',
        'created_at',
        'updated_at'
    ];

    fputcsv($csvHandle, $headers, ',', '"', '\\');

    // Verileri yaz
    foreach ($bayiler as $bayi) {
        $row = [
            $bayi->id,
            $bayi->ad,
            $bayi->sahip_adi,
            $bayi->sahip_telefon,
            $bayi->sahip_email,
            $bayi->sehir_id,
            $bayi->ilce_id,
            $bayi->aktif ? 1 : 0,
            $bayi->created_at?->format('Y-m-d H:i:s'),
            $bayi->updated_at?->format('Y-m-d H:i:s')
        ];
        
        fputcsv($csvHandle, $row, ',', '"', '\\');
    }

    fclose($csvHandle);

    echo "\n✅ Bayiler CSV dosyası oluşturuldu: railway_bayiler.csv\n";
    echo "📁 Dosya konumu: $csvFile\n";

    // İlk 5 kaydı göster
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "📋 İLK 5 BAYİ KAYDI\n";
    echo str_repeat("=", 60) . "\n";

    foreach ($bayiler->take(5) as $index => $bayi) {
        echo "\n" . ($index + 1) . ". BAYİ:\n";
        echo "   ID: {$bayi->id}\n";
        echo "   Ad: {$bayi->ad}\n";
        echo "   Sahip: {$bayi->sahip_adi}\n";
        echo "   Telefon: {$bayi->sahip_telefon}\n";
        echo "   Email: {$bayi->sahip_email}\n";
        echo "   Aktif: " . ($bayi->aktif ? 'Evet' : 'Hayır') . "\n";
    }

    echo "\n" . str_repeat("=", 60) . "\n";
    echo "🎯 Railway bayiler tablosu CSV'ye aktarıldı!\n";

    // İstatistikleri göster
    $aktifBayiler = $bayiler->where('aktif', true)->count();
    $pasifBayiler = $bayiler->where('aktif', false)->count();
    
    echo "\n📊 İSTATİSTİKLER:\n";
    echo "- Toplam Bayi: " . $bayiler->count() . "\n";
    echo "- Aktif Bayi: $aktifBayiler\n";
    echo "- Pasif Bayi: $pasifBayiler\n";

} catch (Exception $e) {
    echo "❌ Hata oluştu: " . $e->getMessage() . "\n";
    echo "🔧 Database bağlantınızı kontrol edin.\n";
    exit(1);
}

?>