<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Talep;
use App\Models\TalepAsamaGecmisi;
use App\Models\TalepTuru;
use App\Models\Asama;
use App\Models\BolgeMimari;
use App\Models\Bayi;
use App\Models\BayiMagazasi;
use Carbon\Carbon;

class DemoTaleplerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Her talep türünden birer demo talep oluştur
        $talepTurleri = TalepTuru::all();
        $bayiler = Bayi::with('magazalar')->get();
        $bolgeMimarlari = BolgeMimari::all();
        
        foreach ($talepTurleri as $index => $talepTuru) {
            // Random bayi seç
            $bayi = $bayiler->random();
            $magaza = $bayi->magazalar->first();
            
            // Random bölge mimarı seç
            $bolgeMimari = $bolgeMimarlari->random();
            
            // Talep oluşturma tarihi (son 30 gün içinde)
            $olusturulmaTarihi = Carbon::now()->subDays(rand(1, 30));
            
            // Demo açıklamalar
            $aciklamalar = [
                'Kayar Pano' => 'Mağaza vitrininde yer alacak kayar pano sistemi için talep. Ürün tanıtımları ve kampanya görselleri için kullanılacak.',
                'Dijital Baskı' => 'Yeni sezon ürünlerinin tanıtımı için dijital baskı materyalleri. A1 ebatında poster ve banner çalışmaları.',
                'Dış Dijital Baskı' => 'Mağaza dış cephesi için hava koşullarına dayanıklı dijital baskı. UV koruyuculu malzeme tercih edilmeli.',
                'Tabela' => 'Mağaza kimliği ve görünürlüğünü artırmak için LED tabela sistemi. Gece görünürlüğü önemli.',
                'Totem' => 'Mağaza girişinde konumlandırılacak dijital totem. İnteraktif özellikler ve dokunmatik ekran isteniyor.',
                'Teşhir Yenileme' => 'Mağaza içi ürün teşhir ünitelerinin yenilenmesi. Modern ve fonksiyonel tasarım beklentisi.',
                'Mağaza Projelendirme' => 'Mağaza iç mekan düzenlemesi ve konsept değişikliği. 3D görselleştirme ile sunulması bekleniyor.',
                'Teşhir İade' => 'Kullanılmayan teşhir malzemelerinin merkeze iadesine yönelik talep. Nakliye planlaması gerekli.'
            ];
            
            // İlk aşamayı al (Talep Oluşturuldu)
            $ilkAsama = Asama::where('is_akisi_tipi', $talepTuru->is_akisi_tipi)
                            ->where('sira', 0)
                            ->first();
            
            // Talep oluştur
            $talep = Talep::create([
                'bolge_id' => $magaza->sehir->bolge_id ?? 1,
                'bolge_mimari_id' => $bolgeMimari->id,
                'bayi_id' => $bayi->id,
                'magaza_tipi' => 'kendi_magazasi',
                'magaza_adi' => $magaza->ad,
                'sehir_id' => $magaza->sehir_id,
                'ilce_id' => $magaza->ilce_id,
                'talep_turu_id' => $talepTuru->id,
                'guncel_asama_id' => $ilkAsama ? $ilkAsama->id : 1,
                'guncel_asama_tarihi' => $olusturulmaTarihi,
                'guncel_asama_aciklamasi' => 'Talep sisteme başarıyla kaydedildi.',
                'aciklama' => $aciklamalar[$talepTuru->ad] ?? 'Demo talep açıklaması.',
                'created_at' => $olusturulmaTarihi,
                'updated_at' => $olusturulmaTarihi,
            ]);
            
            // Bu talep türü için aşamaları al
            $asamalar = Asama::where('is_akisi_tipi', $talepTuru->is_akisi_tipi)
                            ->orderBy('sira')
                            ->get();
            
            // Aşama geçmişi oluştur (rastgele sayıda aşama geçsin)
            $gecenAsamaSayisi = rand(2, min($asamalar->count(), 6)); // En az 2, en fazla 6 aşama
            $currentTime = $olusturulmaTarihi->copy();
            
            for ($i = 0; $i < $gecenAsamaSayisi; $i++) {
                $asama = $asamalar[$i];
                
                // Her aşama için gerçekçi açıklamalar
                $asamaAciklamalari = [
                    'Talep Oluşturuldu' => 'Talep sisteme başarıyla kaydedildi. Süreç başlatıldı.',
                    'Bayi Talep' => 'Bayi tarafından resmi talep oluşturuldu. Detaylar belirtildi.',
                    'Bayi Talep Etti' => 'Bayi tarafından proje talebi iletildi. Değerlendirme süreci başladı.',
                    'Bayi Talebi' => 'Bayi talebi detaylı olarak incelendi. Fizibilite çalışması yapıldı.',
                    'Satışçı Onay' => 'Satış temsilcisi tarafından talep onaylandı. Teknik değerlendirmeye geçildi.',
                    'Erhan Talep' => 'Erhan Bey\'in değerlendirmesi tamamlandı. Teknik uygunluk onaylandı.',
                    '2D Çizim' => '2D teknik çizimler hazırlandı. Ölçüler ve detaylar belirlendi.',
                    '2D Pazarlama Onayı' => 'Pazarlama ekibi 2D tasarımları onayladı. Kurumsal kimliğe uygunluk sağlandı.',
                    '2D Bayi Onayı' => 'Bayi 2D tasarımları beğendi ve onayladı. 3D çalışmaya geçilebilir.',
                    '3D Çizim' => '3D modellemeler tamamlandı. Gerçekçi görselleştirme hazır.',
                    '3D Pazarlama Onayı' => 'Pazarlama departmanı 3D tasarımları onayladı. Final versiyona hazır.',
                    '3D Bayi Onayı' => 'Bayi 3D tasarımları final olarak onayladı. Üretime geçilebilir.',
                    'MD Fiyatlandırması' => 'Müdürlük fiyatlandırması tamamlandı. Bütçe onaylandı.',
                    'Mimari Yönetici Kontrolü' => 'Mimari yönetici kontrolü yapıldı. Standartlara uygunluk onaylandı.',
                    'Pazarlama Direktörü Onayı' => 'Pazarlama direktörü final onayını verdi. Uygulama aşamasına geçildi.',
                    'Satın Alma Aşaması' => 'Satın alma süreci başlatıldı. Tedarikçi seçimi yapıldı.',
                    'Seçkin ÇAĞRICI Onay' => 'Seçkin ÇAĞRICI onayı alındı. Üretim sürecine başlandı.',
                    'Bayi Çek Alma' => 'Bayi ödeme çekini teslim aldı. Mali süreçler tamamlandı.',
                    'Tedarik Süreci' => 'Tedarik süreci devam ediyor. Malzemeler hazırlanıyor.',
                    'İş Teslimi' => 'İş başarıyla teslim edildi. Müşteri memnuniyeti sağlandı.'
                ];
                
                // Aşama geçiş tarihi (önceki aşamadan 1-5 gün sonra)
                if ($i > 0) {
                    $currentTime->addDays(rand(1, 5));
                }
                
                TalepAsamaGecmisi::create([
                    'talep_id' => $talep->id,
                    'asama_id' => $asama->id,
                    'degistiren_kullanici_id' => 1,
                    'degistirilme_tarihi' => $currentTime->copy(),
                    'aciklama' => $asamaAciklamalari[$asama->ad] ?? "Aşama güncellendi: {$asama->ad}",
                    'created_at' => $currentTime->copy(),
                    'updated_at' => $currentTime->copy(),
                ]);
            }
            
            // Talep'in güncel aşamasını son geçen aşama olarak güncelle
            if ($gecenAsamaSayisi > 0) {
                $sonAsama = $asamalar[$gecenAsamaSayisi - 1];
                $talep->update([
                    'guncel_asama_id' => $sonAsama->id,
                    'guncel_asama_tarihi' => $currentTime->copy(),
                    'guncel_asama_aciklamasi' => $asamaAciklamalari[$sonAsama->ad] ?? "Güncel aşama: {$sonAsama->ad}",
                    'updated_at' => $currentTime->copy(),
                ]);
            }
        }
        
        echo "Demo talepler oluşturuldu. Toplam " . $talepTurleri->count() . " demo talep kaydedildi.\n";
    }
}