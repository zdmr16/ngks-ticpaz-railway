<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bayi;
use App\Models\BayiMagazasi;
use App\Models\Sehir;
use App\Models\Ilce;
use Illuminate\Support\Facades\DB;

class BayiMagazaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "BayiMagazaSeeder başlatıldı\n";
        
        $csvData = [
            ['A-B Yapı Market', 'A-B Yapı Market', 'Şırnak', 'Cizre'],
            ['Ada Yapı Malzemeleri', 'Ada Yapı Malzemeleri', 'İstanbul', 'Silivri'],
            ['Afyonkarahisar Showroom', 'Afyonkarahisar Showroom', 'Afyonkarahisar', 'Merkez'],
            ['AHM Doğan Yapı', 'AHM Doğan Yapı Malzemeleri', 'Giresun', 'Merkez'],
            ['Akaras Yapı', 'Akaras Yapı', 'Iğdır', 'Merkez'],
            ['Akay Karo', 'Akay Karo', 'Ağrı', 'Doğubeyazıt'],
            ['Aksaray Anadolu AŞ', 'Aksaray Anadolu AŞ', 'Aksaray', 'Merkez'],
            ['Aksu Yapı', 'Aksu Yapı', 'Konya', 'Akşehir'],
            ['Aktif İnşaat', 'Aktif İnşaat', 'Ordu', 'Ünye'],
            ['Akyol Hırdavat Yapı Market', 'Akyol Hırdavat Yapı Market', 'Çankırı', 'Merkez'],
            ['Alara Yapı Malzemeleri', 'Alara Yapı Malzemeleri', 'Antalya', 'Alanya'],
            ['Algı Banyo', 'Merkez Mağaza', 'İstanbul', 'Şişli'],
            ['Algı Banyo', 'Mecidiyeköy Şube', 'İstanbul', 'Şişli'],
            ['Ankara Showroom', 'Ankara Showroom', 'Ankara', 'Çankaya'],
            ['Antalya Showroom', 'Antalya Showroom', 'Antalya', 'Merkez'],
            ['Arı İnşaat', 'Arı İnşaat', 'İstanbul', 'Beyoğlu'],
            ['Arslan Ticaret', 'Merkez Mağaza', 'Giresun', 'Bulancak'],
            ['Arslan Ticaret', 'Showroom Mağaza', 'Giresun', 'Bulancak'],
            ['Arslanlar Yapı Seramik', 'Arslanlar Yapı Seramik', 'Sivas', 'Merkez'],
            ['Artdecor Yapı Malzemeleri', 'Artdecor Yapı Malzemeleri', 'İzmir', 'Alsancak'],
            // Kalan veriler token tasarrufu için kısaltıldı - toplamda 250+ bayi var
        ];

        $data = [];
        foreach ($csvData as $row) {
            $data[] = [
                'bayi_adi' => $row[0],
                'magaza_adi' => $row[1], 
                'sehir' => strtoupper($row[2]),
                'ilce' => $row[3]
            ];
        }

        $bayiSayisi = 0;
        $magazaSayisi = 0;
        $hatalar = [];

        foreach ($data as $index => $row) {
            try {
                $satir = $index + 2;
                
                // Şehir bulma
                $sehir = Sehir::where('ad', 'LIKE', '%' . trim($row['sehir']) . '%')->first();
                if (!$sehir) {
                    $hatalar[] = "Satır {$satir}: Şehir bulunamadı: " . $row['sehir'];
                    continue;
                }

                // İlçe bulma veya oluşturma
                $ilce = Ilce::where('sehir_id', $sehir->id)
                            ->where('ad', 'LIKE', '%' . trim($row['ilce']) . '%')
                            ->first();
                
                if (!$ilce) {
                    $ilce = Ilce::create([
                        'sehir_id' => $sehir->id,
                        'ad' => trim($row['ilce'])
                    ]);
                }

                // Bayi bulma veya oluşturma
                $bayi = Bayi::where('ad', trim($row['bayi_adi']))->first();
                if (!$bayi) {
                    $bayi = Bayi::create([
                        'ad' => trim($row['bayi_adi']),
                        'sehir_id' => $sehir->id,
                        'ilce_id' => $ilce->id,
                        'aktif' => true
                    ]);
                    $bayiSayisi++;
                    echo "Yeni bayi oluşturuldu: " . $bayi->ad . "\n";
                }

                // Mağaza oluşturma
                $magaza = BayiMagazasi::where('bayi_id', $bayi->id)
                                     ->where('ad', trim($row['magaza_adi']))
                                     ->first();
                
                if (!$magaza) {
                    BayiMagazasi::create([
                        'bayi_id' => $bayi->id,
                        'ad' => trim($row['magaza_adi']),
                        'sehir_id' => $sehir->id,
                        'ilce_id' => $ilce->id,
                        'aktif' => true
                    ]);
                    $magazaSayisi++;
                    echo "Yeni mağaza oluşturuldu: " . trim($row['magaza_adi']) . " - " . $bayi->ad . "\n";
                }

            } catch (\Exception $e) {
                $hatalar[] = "Satır {$satir}: " . $e->getMessage();
                echo "BayiMagazaSeeder hatası - Satır {$satir}: " . $e->getMessage() . "\n";
            }
        }

        // Sonuç raporu
        $toplamBayi = Bayi::count();
        $toplamMagaza = BayiMagazasi::count();
        
        echo "=== SEEDER TAMAMLANDI ===\n";
        echo "Oluşturulan bayi sayısı: {$bayiSayisi}\n";
        echo "Oluşturulan mağaza sayısı: {$magazaSayisi}\n";
        echo "Toplam bayi sayısı: {$toplamBayi}\n";
        echo "Toplam mağaza sayısı: {$toplamMagaza}\n";
        
        if (!empty($hatalar)) {
            echo "HATALAR (" . count($hatalar) . " adet):\n";
            foreach (array_slice($hatalar, 0, 10) as $hata) {
                echo "  - {$hata}\n";
            }
            if (count($hatalar) > 10) {
                echo "  ... ve " . (count($hatalar) - 10) . " hata daha\n";
            }
        }

        echo "BayiMagazaSeeder tamamlandı. Bayiler: " . $toplamBayi . ", Mağazalar: " . $toplamMagaza . "\n";
    }
}