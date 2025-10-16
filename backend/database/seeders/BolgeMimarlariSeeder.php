<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BolgeMimarlariSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Eski verileri temizle (foreign key constraints nedeniyle DELETE kullanıyoruz)
        DB::table('bolge_mimar_atamalari')->delete();
        DB::table('bolge_mimarlari')->delete();
        
        // Gerçek bölge mimarları
        $bolgeMimarlari = [
            ['ad_soyad' => 'Ebru Bahşi', 'email' => 'ebru.bahsi@ngks.com', 'telefon' => '0532 123 45 67', 'aktif' => true],
            ['ad_soyad' => 'Gamze Ağca', 'email' => 'gamze.agca@ngks.com', 'telefon' => '0533 234 56 78', 'aktif' => true],
            ['ad_soyad' => 'Rümeysa Yıkar', 'email' => 'rumeysa.yikar@ngks.com', 'telefon' => '0534 345 67 89', 'aktif' => true],
            ['ad_soyad' => 'Hilal Babaoğlu', 'email' => 'hilal.babaoglu@ngks.com', 'telefon' => '0535 456 78 90', 'aktif' => true],
            ['ad_soyad' => 'Çağlar Uzun', 'email' => 'caglar.uzun@ngks.com', 'telefon' => '0536 567 89 01', 'aktif' => true],
            ['ad_soyad' => 'Hakan Saltürk', 'email' => 'hakan.salturk@ngks.com', 'telefon' => '0537 678 90 12', 'aktif' => true],
            ['ad_soyad' => 'Sidaser Özbek', 'email' => 'sidaser.ozbek@ngks.com', 'telefon' => '0538 789 01 23', 'aktif' => true],
            ['ad_soyad' => 'Fahri Dağ', 'email' => 'fahri.dag@ngks.com', 'telefon' => '0539 890 12 34', 'aktif' => true],
            ['ad_soyad' => 'Merve Çamlılar', 'email' => 'merve.camlilar@ngks.com', 'telefon' => '0540 901 23 45', 'aktif' => true],
        ];

        $mimarIdleri = [];
        foreach ($bolgeMimarlari as $index => $mimar) {
            $mimarId = DB::table('bolge_mimarlari')->insertGetId([
                'ad_soyad' => $mimar['ad_soyad'],
                'email' => $mimar['email'],
                'telefon' => $mimar['telefon'],
                'aktif' => $mimar['aktif'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $mimarIdleri[$index] = $mimarId;
        }

        // Gerçek bölge mimar atamalarını ekle
        // Bölge ID'leri: 1=İstanbul, 2=G.Marmara ve B.Karadeniz, 3=Karadeniz, 4=Güney Anadolu, 5=D.Akdeniz, 6=B.Akdeniz, 7=Ege, 8=Merkez, 9=İç Anadolu
        $atamaları = [
            ['bolge_id' => 1, 'bolge_mimari_id' => $mimarIdleri[0]], // İstanbul - Ebru Bahşi
            ['bolge_id' => 1, 'bolge_mimari_id' => $mimarIdleri[1]], // İstanbul - Gamze Ağca
            ['bolge_id' => 2, 'bolge_mimari_id' => $mimarIdleri[2]], // G.Marmara ve B.Karadeniz - Rümeysa Yıkar
            ['bolge_id' => 7, 'bolge_mimari_id' => $mimarIdleri[3]], // Ege - Hilal Babaoğlu
            ['bolge_id' => 3, 'bolge_mimari_id' => $mimarIdleri[4]], // Karadeniz - Çağlar Uzun
            ['bolge_id' => 9, 'bolge_mimari_id' => $mimarIdleri[5]], // İç Anadolu - Hakan Saltürk
            ['bolge_id' => 4, 'bolge_mimari_id' => $mimarIdleri[6]], // Güney Anadolu - Sidaser Özbek
            ['bolge_id' => 5, 'bolge_mimari_id' => $mimarIdleri[7]], // D.Akdeniz - Fahri Dağ
            ['bolge_id' => 6, 'bolge_mimari_id' => $mimarIdleri[8]], // B.Akdeniz - Merve Çamlılar
        ];

        foreach ($atamaları as $atama) {
            DB::table('bolge_mimar_atamalari')->insert([
                'bolge_id' => $atama['bolge_id'],
                'bolge_mimari_id' => $atama['bolge_mimari_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "Bölge mimarları ve atamaları oluşturuldu.\n";
    }
}