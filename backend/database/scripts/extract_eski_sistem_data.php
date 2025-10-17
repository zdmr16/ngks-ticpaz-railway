<?php

/**
 * Eski Sistem SQL Veri Çıkarma Script'i
 * 
 * Bu script büyük SQL dosyasından sadece belirlenen 5 talep türüne ait
 * verileri çıkarır ve seeder için uygun formata çevirir.
 * 
 * Kullanım:
 * php extract_eski_sistem_data.php /path/to/urunngkutahyaser_pazarlama.sql
 */

if ($argc < 2) {
    echo "Kullanım: php extract_eski_sistem_data.php <sql_dosya_yolu>\n";
    exit(1);
}

$sqlFile = $argv[1];

if (!file_exists($sqlFile)) {
    echo "SQL dosyası bulunamadı: {$sqlFile}\n";
    exit(1);
}

echo "SQL dosyası okunuyor: {$sqlFile}\n";

// Aktarılacak talep türleri (HTML raporundan)
$hedefTalepTurleri = [
    'Kayar Pano',
    'Dijital Baskı', 
    'Cephe/Tabela',
    'Teşhir Yenileme',
    'Yeni Mağaza'
];

// SQL dosyasını oku
$sqlContent = file_get_contents($sqlFile);

if ($sqlContent === false) {
    echo "SQL dosyası okunamadı!\n";
    exit(1);
}

echo "SQL dosyası başarıyla okundu. Boyut: " . number_format(strlen($sqlContent)) . " karakter\n";

// Talepler tablosundaki INSERT verilerini bul
preg_match_all(
    '/INSERT INTO `talepler`.*?VALUES\s*\((.*?)\);/s',
    $sqlContent,
    $matches
);

if (empty($matches[1])) {
    echo "Talepler tablosunda INSERT verileri bulunamadı!\n";
    exit(1);
}

echo "Bulunan INSERT satırı sayısı: " . count($matches[1]) . "\n";

$filtrelenmisVeriler = [];
$toplamSayac = 0;
$filtrelenmisSayac = 0;

foreach ($matches[1] as $insertData) {
    $toplamSayac++;
    
    // INSERT verilerini parse et
    $values = parseInsertValues($insertData);
    
    if (empty($values)) {
        continue;
    }
    
    // urun_etiketi alanını bul (tabloya göre index'i ayarla)
    // Bu kısım gerçek tablo yapısına göre güncellenecek
    $urunEtiketi = isset($values[10]) ? trim($values[10], "'\"") : '';
    
    // Sadece hedef talep türlerini al
    if (in_array($urunEtiketi, $hedefTalepTurleri)) {
        $filtrelenmisVeriler[] = [
            'talep_id' => isset($values[0]) ? (int)$values[0] : 0,
            'talep_bayi' => isset($values[1]) ? (int)$values[1] : 0,
            'talep_bolge' => isset($values[2]) ? (int)$values[2] : 0,
            'talep_tali_bayi' => isset($values[3]) ? (int)$values[3] : 0,
            'urun_etiketi' => $urunEtiketi,
            'talep_durum' => isset($values[7]) ? (int)$values[7] : 1,
            'talep_not' => isset($values[8]) ? trim($values[8], "'\"") : '',
            'talep_cop' => isset($values[9]) ? (int)$values[9] : 0,
            'talep_tarihi' => isset($values[11]) ? trim($values[11], "'\"") : date('Y-m-d H:i:s'),
        ];
        $filtrelenmisSayac++;
    }
}

echo "\nFiltreleme Sonuçları:\n";
echo "- Toplam talep: {$toplamSayac}\n";
echo "- Filtrelenen talep: {$filtrelenmisSayac}\n";
echo "- Filtreleme oranı: " . round(($filtrelenmisSayac/$toplamSayac)*100, 2) . "%\n";

// Talep türü dağılımını göster
$turDagilimi = [];
foreach ($filtrelenmisVeriler as $veri) {
    $tur = $veri['urun_etiketi'];
    $turDagilimi[$tur] = ($turDagilimi[$tur] ?? 0) + 1;
}

echo "\nTalep Türü Dağılımı:\n";
foreach ($turDagilimi as $tur => $sayi) {
    echo "- {$tur}: {$sayi} adet\n";
}

// PHP array formatında kaydet
$outputFile = dirname(__FILE__) . '/extracted_data.php';
$phpArray = "<?php\n\n";
$phpArray .= "/**\n";
$phpArray .= " * Eski sistemden çıkarılan filtrelenmiş talep verileri\n";
$phpArray .= " * Oluşturulma tarihi: " . date('Y-m-d H:i:s') . "\n";
$phpArray .= " * Toplam kayıt: {$filtrelenmisSayac}\n";
$phpArray .= " */\n\n";
$phpArray .= "return " . var_export($filtrelenmisVeriler, true) . ";\n";

file_put_contents($outputFile, $phpArray);

echo "\nÇıkarılan veriler şuraya kaydedildi: {$outputFile}\n";
echo "Bu dosyayı EskiSistemTaleplerSeeder.php içinde kullanabilirsiniz.\n";

/**
 * INSERT VALUES verisini parse eder
 */
function parseInsertValues($insertData) {
    // Basit bir parser - gerçek dünyada daha karmaşık olabilir
    $values = [];
    
    // Virgül ile ayır ama string içindeki virgülleri göz ardı et
    $inString = false;
    $currentValue = '';
    $stringChar = null;
    
    for ($i = 0; $i < strlen($insertData); $i++) {
        $char = $insertData[$i];
        
        if (($char === '"' || $char === "'") && !$inString) {
            $inString = true;
            $stringChar = $char;
            $currentValue .= $char;
        } elseif ($char === $stringChar && $inString) {
            $inString = false;
            $stringChar = null;
            $currentValue .= $char;
        } elseif ($char === ',' && !$inString) {
            $values[] = trim($currentValue);
            $currentValue = '';
        } else {
            $currentValue .= $char;
        }
    }
    
    // Son değeri ekle
    if ($currentValue !== '') {
        $values[] = trim($currentValue);
    }
    
    return $values;
}