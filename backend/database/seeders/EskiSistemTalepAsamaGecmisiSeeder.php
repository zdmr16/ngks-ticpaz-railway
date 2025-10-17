<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Talep;
use App\Models\TalepAsamaGecmisi;
use App\Models\Kullanici;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EskiSistemTalepAsamaGecmisiSeeder extends Seeder
{
    /**
     * Aktarılan talepler için aşama geçmişi oluşturan seeder
     * 
     * Her talep için:
     * 1. "Talep Oluşturuldu" başlangıç kaydı
     * 2. Mevcut durum kaydı  
     * 3. Gerekirse ara aşama kayıtları
     */
    public function run()
    {
        Log::info('Eski sistem talep aşama geçmişi aktarım başladı');
        
        try {
            DB::beginTransaction();
            
            // Mevcut aşama geçmişi verilerini temizle
            Log::info('Mevcut aşama geçmişi verileri temizleniyor...');
            DB::table('talep_asama_gecmisi')->truncate();
            
            // Sistem kullanıcısını al (eski sistem aktarımı için)
            $sistemKullanicisi = Kullanici::where('email', 'system@ngkutahyaseramik.com.tr')->first();
            if (!$sistemKullanicisi) {
                // Sistem kullanıcısı yoksa oluştur
                $sistemKullanicisi = Kullanici::create([
                    'ad' => 'Sistem',
                    'soyad' => 'Aktarım',
                    'email' => 'system@ngkutahyaseramik.com.tr',
                    'telefon' => '0000000000',
                    'aktif' => true
                ]);
            }
            
            // Aktarılan tüm talepleri al
            $talepler = Talep::all();
            
            $olusturulanSayisi = 0;
            $hataSayisi = 0;
            
            foreach ($talepler as $talep) {
                try {
                    // Her talep için aşama geçmişi oluştur
                    $this->createAsamaGecmisiForTalep($talep, $sistemKullanicisi->id);
                    $olusturulanSayisi++;
                    
                } catch (\Exception $e) {
                    Log::error("Talep aşama geçmişi oluşturma hatası: {$e->getMessage()}", [
                        'talep_id' => $talep->id
                    ]);
                    $hataSayisi++;
                }
            }
            
            DB::commit();
            
            Log::info("Eski sistem talep aşama geçmişi aktarım tamamlandı", [
                'olusturulan_sayisi' => $olusturulanSayisi,
                'hata_sayisi' => $hataSayisi,
                'toplam_talep' => count($talepler)
            ]);
            
            $this->command->info("✅ Aşama geçmişi aktarım tamamlandı: {$olusturulanSayisi} talep için geçmiş oluşturuldu, {$hataSayisi} hata");
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Eski sistem aşama geçmişi aktarım genel hatası: {$e->getMessage()}");
            $this->command->error("❌ Aşama geçmişi aktarım başarısız: {$e->getMessage()}");
            throw $e;
        }
    }
    
    /**
     * Belirli bir talep için aşama geçmişi oluşturur
     */
    private function createAsamaGecmisiForTalep($talep, $sistemKullaniciId)
    {
        $talepTuru = $talep->talepTuru;
        $isAkisiTipi = $talepTuru->is_akisi_tipi;
        
        // İş akışı tipine göre aşama dizilimi
        $asamaDizilimleri = [
            'tip_a' => [1, 2, 3, 4, 5, 6, 7], // Kayar Pano, Dijital Baskı, Tabela
            'tip_b' => [8, 9, 10, 11, 12, 13], // Teşhir Yenileme, Mağaza Projelendirme
            'tip_c' => [14, 15, 16] // Teşhir İade
        ];
        
        $asamalar = $asamaDizilimleri[$isAkisiTipi] ?? $asamaDizilimleri['tip_a'];
        $guncelAsamaIndex = array_search($talep->guncel_asama_id, $asamalar);
        
        // Eğer güncel aşama bulunamazsa, varsayılan olarak ilk aşamayı al
        if ($guncelAsamaIndex === false) {
            $guncelAsamaIndex = 0;
            $talep->guncel_asama_id = $asamalar[0];
            $talep->save();
        }
        
        $baseTarih = Carbon::parse($talep->created_at);
        
        // 1. Başlangıç kaydı: "Talep Oluşturuldu"
        TalepAsamaGecmisi::create([
            'talep_id' => $talep->id,
            'asama_id' => $asamalar[0], // İlk aşama
            'aciklama' => 'Talep eski sistemden aktarıldı ve oluşturuldu',
            'degistirilme_tarihi' => $baseTarih,
            'degistiren_kullanici_id' => $sistemKullaniciId,
            'created_at' => $baseTarih,
            'updated_at' => $baseTarih
        ]);
        
        // 2. Ara aşamalar (varsa)
        for ($i = 1; $i <= $guncelAsamaIndex; $i++) {
            $asamaTarihi = $baseTarih->copy()->addDays($i * 2); // Her aşama 2 gün arayla
            
            TalepAsamaGecmisi::create([
                'talep_id' => $talep->id,
                'asama_id' => $asamalar[$i],
                'aciklama' => $this->getAsamaAciklamasi($asamalar[$i]),
                'degistirilme_tarihi' => $asamaTarihi,
                'degistiren_kullanici_id' => $sistemKullaniciId,
                'created_at' => $asamaTarihi,
                'updated_at' => $asamaTarihi
            ]);
        }
        
        // 3. Güncel aşama kaydı (eğer ilk aşama değilse)
        if ($guncelAsamaIndex > 0) {
            $guncelTarih = $talep->guncel_asama_tarihi ?: $baseTarih->copy()->addDays(($guncelAsamaIndex + 1) * 2);
            
            TalepAsamaGecmisi::create([
                'talep_id' => $talep->id,
                'asama_id' => $talep->guncel_asama_id,
                'aciklama' => $talep->guncel_asama_aciklamasi ?: $this->getAsamaAciklamasi($talep->guncel_asama_id),
                'degistirilme_tarihi' => $guncelTarih,
                'degistiren_kullanici_id' => $sistemKullaniciId,
                'created_at' => $guncelTarih,
                'updated_at' => $guncelTarih
            ]);
        }
    }
    
    /**
     * Aşama ID'sine göre varsayılan açıklama döner
     */
    private function getAsamaAciklamasi($asamaId)
    {
        $aciklamalar = [
            // Tip A Aşamaları
            1 => 'Talep alındı ve değerlendirme yapıldı',
            2 => 'Sahada ölçü alım işlemi tamamlandı',
            3 => 'Tasarım çalışması tamamlandı',
            4 => 'Müşteri onayı bekleniyor',
            5 => 'Üretim aşamasına geçildi',
            6 => 'Montaj işlemi gerçekleştirildi',
            7 => 'Proje başarıyla tamamlandı',
            
            // Tip B Aşamaları  
            8 => 'Proje başlatıldı ve planlama yapıldı',
            9 => 'Sahada keşif çalışması tamamlandı',
            10 => 'Proje detayları hazırlandı',
            11 => 'Proje onayı bekleniyor',
            12 => 'Uygulama aşamasında',
            13 => 'Proje teslim edildi',
            
            // Tip C Aşamaları
            14 => 'İade talebi alındı',
            15 => 'İade işlemi devam ediyor',
            16 => 'İade işlemi tamamlandı'
        ];
        
        return $aciklamalar[$asamaId] ?? 'Aşama güncellendi';
    }
}