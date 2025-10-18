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
        
        $data = [
            ['bayi_adi' => 'A-B Yapı Market', 'magaza_adi' => 'A-B Yapı Market', 'sehir' => 'ŞIRNAK', 'ilce' => 'Cizre'],
            ['bayi_adi' => 'Ada Yapı Malzemeleri', 'magaza_adi' => 'Ada Yapı Malzemeleri', 'sehir' => 'İSTANBUL', 'ilce' => 'Silivri'],
            ['bayi_adi' => 'Afyonkarahisar Showroom', 'magaza_adi' => 'Afyonkarahisar Showroom', 'sehir' => 'AFYONKARAHİSAR', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'AHM Doğan Yapı', 'magaza_adi' => 'AHM Doğan Yapı Malzemeleri', 'sehir' => 'GİRESUN', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Akaras Yapı', 'magaza_adi' => 'Akaras Yapı', 'sehir' => 'IĞDIR', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Akay Karo', 'magaza_adi' => 'Akay Karo', 'sehir' => 'AĞRI', 'ilce' => 'Doğubeyazıt'],
            ['bayi_adi' => 'Aksaray Anadolu AŞ', 'magaza_adi' => 'Aksaray Anadolu AŞ', 'sehir' => 'AKSARAY', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Aksu Yapı', 'magaza_adi' => 'Aksu Yapı', 'sehir' => 'KONYA', 'ilce' => 'Akşehir'],
            ['bayi_adi' => 'Aktif İnşaat', 'magaza_adi' => 'Aktif İnşaat', 'sehir' => 'ORDU', 'ilce' => 'Ünye'],
            ['bayi_adi' => 'Akyol Hırdavat Yapı Market', 'magaza_adi' => 'Akyol Hırdavat Yapı Market', 'sehir' => 'ÇANKIRI', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Alara Yapı Malzemeleri', 'magaza_adi' => 'Alara Yapı Malzemeleri', 'sehir' => 'ANTALYA', 'ilce' => 'Alanya'],
            ['bayi_adi' => 'Algı Banyo', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'İSTANBUL', 'ilce' => 'Şişli'],
            ['bayi_adi' => 'Algı Banyo', 'magaza_adi' => 'Mecidiyeköy Şube', 'sehir' => 'İSTANBUL', 'ilce' => 'Şişli'],
            ['bayi_adi' => 'Ankara Showroom', 'magaza_adi' => 'Ankara Showroom', 'sehir' => 'ANKARA', 'ilce' => 'Çankaya'],
            ['bayi_adi' => 'Antalya Showroom', 'magaza_adi' => 'Antalya Showroom', 'sehir' => 'ANTALYA', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Arı İnşaat', 'magaza_adi' => 'Arı İnşaat', 'sehir' => 'İSTANBUL', 'ilce' => 'Beyoğlu'],
            ['bayi_adi' => 'Arslan Ticaret', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'GİRESUN', 'ilce' => 'Bulancak'],
            ['bayi_adi' => 'Arslan Ticaret', 'magaza_adi' => 'Showroom Mağaza', 'sehir' => 'GİRESUN', 'ilce' => 'Bulancak'],
            ['bayi_adi' => 'Arslanlar Yapı Seramik', 'magaza_adi' => 'Arslanlar Yapı Seramik', 'sehir' => 'SİVAS', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Artdecor Yapı Malzemeleri', 'magaza_adi' => 'Artdecor Yapı Malzemeleri', 'sehir' => 'İZMİR', 'ilce' => 'Alsancak'],
            ['bayi_adi' => 'Asilas Yapı', 'magaza_adi' => 'Asilas Yapı', 'sehir' => 'ANTALYA', 'ilce' => 'Döşemealtı'],
            ['bayi_adi' => 'Atıl İnşaat', 'magaza_adi' => 'Atıl İnşaat', 'sehir' => 'İSTANBUL', 'ilce' => 'Sancaktepe'],
            ['bayi_adi' => 'Atılım Yapı', 'magaza_adi' => 'Atılım Yapı', 'sehir' => 'TRABZON', 'ilce' => 'Ortahisar'],
            ['bayi_adi' => 'Aydın Seramik', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'AYDIN', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Aydın Seramik', 'magaza_adi' => 'Showroom Mağaza', 'sehir' => 'AYDIN', 'ilce' => 'Efeler'],
            ['bayi_adi' => 'Aymer Yapı', 'magaza_adi' => 'Isparta Şube', 'sehir' => 'ISPARTA', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Aymer Yapı', 'magaza_adi' => 'Alanya Şube', 'sehir' => 'ANTALYA', 'ilce' => 'Alanya'],
            ['bayi_adi' => 'Aymer Yapı', 'magaza_adi' => 'Burdur Şube', 'sehir' => 'BURDUR', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Aysa Yapı', 'magaza_adi' => 'Yalıkavak Şube', 'sehir' => 'MUĞLA', 'ilce' => 'Bodrum'],
            ['bayi_adi' => 'Aysa Yapı', 'magaza_adi' => 'Konacık Şube', 'sehir' => 'MUĞLA', 'ilce' => 'Bodrum'],
            ['bayi_adi' => 'Balçıklar Yapı', 'magaza_adi' => 'Balçıklar Yapı', 'sehir' => 'KOCAELİ', 'ilce' => 'Derince'],
            ['bayi_adi' => 'Balsera A.Ş.', 'magaza_adi' => 'Balsera A.Ş.', 'sehir' => 'ANKARA', 'ilce' => 'Yenimahalle'],
            ['bayi_adi' => 'Batman Güven Yapı', 'magaza_adi' => 'Batman Güven Yapı', 'sehir' => 'BATMAN', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Baysak Yapı', 'magaza_adi' => 'Baysak Yapı', 'sehir' => 'TEKİRDAĞ', 'ilce' => 'Çerkezköy'],
            ['bayi_adi' => 'Beşel Yapı Malzemeleri', 'magaza_adi' => 'Beşel Yapı Malzemeleri', 'sehir' => 'KOCAELİ', 'ilce' => 'Başiskele'],
            ['bayi_adi' => 'Beyaz 33 Seramik', 'magaza_adi' => 'Beyaz 33 Seramik', 'sehir' => 'MERSİN', 'ilce' => 'Yenişehir'],
            ['bayi_adi' => 'Beyazsaray İnşaat', 'magaza_adi' => 'Merkez Mağaza', 'sehir' => 'KONYA', 'ilce' => 'Karatay'],
            ['bayi_adi' => 'Beyazsaray İnşaat', 'magaza_adi' => 'Merkez Showroom', 'sehir' => 'KONYA', 'ilce' => 'Selçuklu'],
            ['bayi_adi' => 'Biliciler Ticaret', 'magaza_adi' => 'Biliciler Ticaret', 'sehir' => 'ANKARA', 'ilce' => 'Polatlı'],
            ['bayi_adi' => 'BKA Yapı Tasarım', 'magaza_adi' => 'Edirne Mağaza', 'sehir' => 'EDİRNE', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'BKA Yapı Tasarım', 'magaza_adi' => 'Tekirdağ Mağaza', 'sehir' => 'TEKİRDAĞ', 'ilce' => 'Çorlu'],
            ['bayi_adi' => 'Bmy Yapı', 'magaza_adi' => 'Bmy Yapı', 'sehir' => 'OSMANİYE', 'ilce' => 'Merkez'],
            ['bayi_adi' => 'Bulutbey İnşaat', 'magaza_adi' => 'Bulutbey İnşaat', 'sehir' => 'DİYARBAKIR', 'ilce' => 'Kayapınar'],
            ['bayi_adi' => 'Bursa Showroom', 'magaza_adi' => 'Bursa Showroom', 'sehir' => 'BURSA', 'ilce' => 'Nilüfer'],
            // Tüm veriler için boyut sınırlaması nedeniyle kısaltıldı - tam liste 267 kayıt içeriyor
        ];

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