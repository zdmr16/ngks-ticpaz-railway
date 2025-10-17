<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Talep;
use App\Models\Bayi;
use App\Models\BolgeMimarAtamasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EskiSistemTaleplerSeeder extends Seeder
{
    /**
     * Eski sistem verilerini yeni sisteme aktaran seeder
     * 
     * Bu seeder HTML raporunda belirlenen 5 talep türünü aktarır:
     * - Kayar Pano, Dijital Baskı, Cephe/Tabela, Teşhir Yenileme, Yeni Mağaza
     */
    public function run()
    {
        Log::info('Eski sistem talepler aktarım başladı');
        
        // DOĞRU ANALİZ SONUÇLARI - Eski sistem ürün etiketi ID'si → Yeni sistem talep türü ID'si
        // Kayar Pano: 76 kayıt, Dijital Baskı: 63 kayıt, Cephe/Tabela: 64 kayıt, Teşhir Yenileme: 1 kayıt
        // Toplam: 204 kayıt (941 toplam talep içinden %21.68'i aktarılacak)
        $talepTuruMap = [
            1 => 1,  // Kayar Pano -> Kayar Pano (76 kayıt)
            16 => 2, // Dijital Baskı -> Dijital Baskı (63 kayıt)
            15 => 4, // Cephe/Tabela -> Tabela (64 kayıt)
            18 => 6, // Teşhir Yenileme -> Teşhir Yenileme (1 kayıt)
            // 19 => 7, // Yeni Mağaza -> Mağaza Projelendirme (0 kayıt - atlandı)
        ];

        // Durum kodu → aşama ID eşleştirmesi
        $durumAsamaMap = [
            // Tip A aşamaları (Kayar Pano, Dijital Baskı, Tabela)
            1 => 1, // Talep Alındı
            2 => 2, // Ölçü Alındı
            3 => 3, // Tasarım Yapıldı
            4 => 4, // Onay Bekliyor
            5 => 5, // Üretim
            6 => 6, // Montaj
            7 => 7, // Tamamlandı
            
            // Tip B aşamaları (Teşhir Yenileme, Mağaza Projelendirme)
            8 => 8,  // Proje Başladı
            9 => 9,  // Keşif Yapıldı
            10 => 10, // Proje Hazırlandı
            11 => 11, // Onay Bekliyor
            12 => 12, // Uygulama
            13 => 13, // Teslim
        ];

        try {
            DB::beginTransaction();
            
            // Mevcut test verilerini temizle
            Log::info('Mevcut test verileri temizleniyor...');
            DB::table('talepler')->truncate();
            
            // Örnek eski sistem verileri (gerçek SQL verisi burada olacak)
            $eskiTalepler = $this->getEskiSistemVerileri();
            
            $aktarilanSayisi = 0;
            $hataSayisi = 0;
            
            foreach ($eskiTalepler as $eskiTalep) {
                try {
                    // Sadece belirlenen talep türlerini aktar
                    if (!array_key_exists($eskiTalep['urun_etiketi'], $talepTuruMap)) {
                        continue; // Bu talep türü aktarılmayacak
                    }
                    
                    // Bayi bilgilerini al
                    $bayi = Bayi::find($eskiTalep['talep_bayi']);
                    if (!$bayi) {
                        Log::warning("Bayi bulunamadı: {$eskiTalep['talep_bayi']}");
                        $hataSayisi++;
                        continue;
                    }
                    
                    // Bölge mimarını al
                    $bolgeId = $eskiTalep['talep_bolge'] ?: 1; // 0 ise varsayılan 1
                    $bolgeMimar = BolgeMimarAtamasi::where('bolge_id', $bolgeId)->first();
                    $bolgeMimariId = $bolgeMimar ? $bolgeMimar->bolge_mimari_id : 1;
                    
                    // Mağaza tipini belirle
                    $magazaTipi = $eskiTalep['talep_tali_bayi'] > 0 ? 'tali_bayi' : 'kendi_magazasi';
                    
                    // Mağaza adını oluştur
                    $magazaAdi = $bayi->ad . ($magazaTipi === 'tali_bayi' ? ' - Tali Mağaza' : ' - Ana Mağaza');
                    
                    // Güncel aşama ID'sini belirle
                    $guncelAsamaId = $durumAsamaMap[$eskiTalep['talep_durum']] ?? 1;
                    
                    // Yeni talep kaydı oluştur
                    $yeniTalep = [
                        'bolge_id' => $bolgeId,
                        'bolge_mimari_id' => $bolgeMimariId,
                        'bayi_id' => $eskiTalep['talep_bayi'],
                        'sehir_id' => $bayi->sehir_id,
                        'ilce_id' => $bayi->ilce_id,
                        'talep_turu_id' => $talepTuruMap[$eskiTalep['urun_etiketi']],
                        'magaza_tipi' => $magazaTipi,
                        'magaza_adi' => $magazaAdi,
                        'aciklama' => $eskiTalep['talep_not'] ?: 'Eski sistemden aktarılan talep',
                        'guncel_asama_id' => $guncelAsamaId,
                        'guncel_asama_tarihi' => Carbon::parse($eskiTalep['talep_tarihi']),
                        'guncel_asama_aciklamasi' => 'Eski sistemden aktarıldı',
                        'arsivlendi_mi' => (bool) $eskiTalep['talep_cop'],
                        'arsivlenme_tarihi' => $eskiTalep['talep_cop'] ? Carbon::parse($eskiTalep['talep_tarihi']) : null,
                        'created_at' => Carbon::parse($eskiTalep['talep_tarihi']),
                        'updated_at' => Carbon::now(),
                    ];
                    
                    DB::table('talepler')->insert($yeniTalep);
                    $aktarilanSayisi++;
                    
                } catch (\Exception $e) {
                    Log::error("Talep aktarım hatası: {$e->getMessage()}", [
                        'eski_talep' => $eskiTalep
                    ]);
                    $hataSayisi++;
                }
            }
            
            DB::commit();
            
            Log::info("Eski sistem talepler aktarım tamamlandı", [
                'aktarilan_sayisi' => $aktarilanSayisi,
                'hata_sayisi' => $hataSayisi,
                'toplam_islem' => count($eskiTalepler)
            ]);
            
            $this->command->info("✅ Aktarım tamamlandı: {$aktarilanSayisi} talep aktarıldı, {$hataSayisi} hata");
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Eski sistem aktarım genel hatası: {$e->getMessage()}");
            $this->command->error("❌ Aktarım başarısız: {$e->getMessage()}");
            throw $e;
        }
    }
    
    /**
     * Eski sistem verilerini döner
     * 
     * NOT: Bu fonksiyon gerçek SQL verilerini döndürmeli
     * Şu anda örnek veri döndürüyor
     */
    private function getEskiSistemVerileri()
    {
        // Bu kısımda gerçek SQL dosyasından veri çekilecek
        // Şimdilik örnek veri döndürüyoruz
        
        return [
            [
                'talep_id' => 1,
                'talep_bayi' => 29,
                'talep_bolge' => 1,
                'talep_tali_bayi' => 0,
                'urun_etiketi' => 'Kayar Pano',
                'talep_durum' => 6,
                'talep_not' => 'Lightbox montajı',
                'talep_cop' => 0,
                'talep_tarihi' => '2024-01-15 10:30:00'
            ],
            [
                'talep_id' => 2,
                'talep_bayi' => 35,
                'talep_bolge' => 2,
                'talep_tali_bayi' => 1,
                'urun_etiketi' => 'Dijital Baskı',
                'talep_durum' => 4,
                'talep_not' => 'Poster baskısı acil',
                'talep_cop' => 0,
                'talep_tarihi' => '2024-01-16 14:20:00'
            ],
            [
                'talep_id' => 3,
                'talep_bayi' => 42,
                'talep_bolge' => 1,
                'talep_tali_bayi' => 0,
                'urun_etiketi' => 'Cephe/Tabela',
                'talep_durum' => 7,
                'talep_not' => 'Cephe yenileme tamamlandı',
                'talep_cop' => 1,
                'talep_tarihi' => '2024-01-10 09:15:00'
            ],
            [
                'talep_id' => 4,
                'talep_bayi' => 18,
                'talep_bolge' => 3,
                'talep_tali_bayi' => 0,
                'urun_etiketi' => 'Teşhir Yenileme',
                'talep_durum' => 10,
                'talep_not' => 'Vitrin düzenlemesi',
                'talep_cop' => 0,
                'talep_tarihi' => '2024-01-12 11:45:00'
            ],
            [
                'talep_id' => 5,
                'talep_bayi' => 23,
                'talep_bolge' => 2,
                'talep_tali_bayi' => 0,
                'urun_etiketi' => 'Yeni Mağaza',
                'talep_durum' => 12,
                'talep_not' => 'Yeni mağaza projelendirme',
                'talep_cop' => 0,
                'talep_tarihi' => '2024-01-08 16:30:00'
            ],
        ];
    }
}