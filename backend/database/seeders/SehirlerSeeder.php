<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SehirlerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * CSV dosyasına göre şehir-bölge bağlantıları
     *
     * @return void
     */
    public function run()
    {
        $sehirler = [
            // İstanbul Bölgesi (bolge_id: 1)
            ['ad' => 'Edirne', 'bolge_id' => 1],
            ['ad' => 'Kırklareli', 'bolge_id' => 1],
            ['ad' => 'Tekirdağ', 'bolge_id' => 1],
            ['ad' => 'İstanbul', 'bolge_id' => 1],
            ['ad' => 'Kocaeli', 'bolge_id' => 1],
            
            // G.Marmara ve B.Karadeniz Bölgesi (bolge_id: 2)
            ['ad' => 'Çanakkale', 'bolge_id' => 2],
            ['ad' => 'Balıkesir', 'bolge_id' => 2],
            ['ad' => 'Bursa', 'bolge_id' => 2],
            ['ad' => 'Yalova', 'bolge_id' => 2],
            ['ad' => 'Bilecik', 'bolge_id' => 2],
            ['ad' => 'Sakarya', 'bolge_id' => 2],
            ['ad' => 'Eskişehir', 'bolge_id' => 2],
            ['ad' => 'Düzce', 'bolge_id' => 2],
            ['ad' => 'Bolu', 'bolge_id' => 2],
            ['ad' => 'Zonguldak', 'bolge_id' => 2],
            ['ad' => 'Karabük', 'bolge_id' => 2],
            ['ad' => 'Bartın', 'bolge_id' => 2],
            ['ad' => 'Kastamonu', 'bolge_id' => 2],
            
            // Karadeniz Bölgesi (bolge_id: 3)
            ['ad' => 'Sinop', 'bolge_id' => 3],
            ['ad' => 'Çorum', 'bolge_id' => 3],
            ['ad' => 'Samsun', 'bolge_id' => 3],
            ['ad' => 'Amasya', 'bolge_id' => 3],
            ['ad' => 'Tokat', 'bolge_id' => 3],
            ['ad' => 'Ordu', 'bolge_id' => 3],
            ['ad' => 'Giresun', 'bolge_id' => 3],
            ['ad' => 'Trabzon', 'bolge_id' => 3],
            ['ad' => 'Gümüşhane', 'bolge_id' => 3],
            ['ad' => 'Erzincan', 'bolge_id' => 3],
            ['ad' => 'Bayburt', 'bolge_id' => 3],
            ['ad' => 'Rize', 'bolge_id' => 3],
            ['ad' => 'Erzurum', 'bolge_id' => 3],
            ['ad' => 'Artvin', 'bolge_id' => 3],
            ['ad' => 'Ardahan', 'bolge_id' => 3],
            ['ad' => 'Kars', 'bolge_id' => 3],
            ['ad' => 'Ağrı', 'bolge_id' => 3],
            ['ad' => 'Iğdır', 'bolge_id' => 3],
            
            // Güney Anadolu Bölgesi (bolge_id: 4)
            ['ad' => 'Tunceli', 'bolge_id' => 4],
            ['ad' => 'Elazığ', 'bolge_id' => 4],
            ['ad' => 'Bingöl', 'bolge_id' => 4],
            ['ad' => 'Muş', 'bolge_id' => 4],
            ['ad' => 'Bitlis', 'bolge_id' => 4],
            ['ad' => 'Van', 'bolge_id' => 4],
            ['ad' => 'Diyarbakır', 'bolge_id' => 4],
            ['ad' => 'Batman', 'bolge_id' => 4],
            ['ad' => 'Siirt', 'bolge_id' => 4],
            ['ad' => 'Hakkari', 'bolge_id' => 4],
            ['ad' => 'Şırnak', 'bolge_id' => 4],
            ['ad' => 'Mardin', 'bolge_id' => 4],
            ['ad' => 'Adıyaman', 'bolge_id' => 4],
            ['ad' => 'Şanlıurfa', 'bolge_id' => 4],
            
            // D.Akdeniz Bölgesi (bolge_id: 5)
            ['ad' => 'Mersin', 'bolge_id' => 5],
            ['ad' => 'Adana', 'bolge_id' => 5],
            ['ad' => 'Osmaniye', 'bolge_id' => 5],
            ['ad' => 'Kahramanmaraş', 'bolge_id' => 5],
            ['ad' => 'Gaziantep', 'bolge_id' => 5],
            ['ad' => 'Hatay', 'bolge_id' => 5],
            ['ad' => 'Kilis', 'bolge_id' => 5],
            
            // B.Akdeniz Bölgesi (bolge_id: 6)
            ['ad' => 'Antalya', 'bolge_id' => 6],
            ['ad' => 'Burdur', 'bolge_id' => 6],
            ['ad' => 'Isparta', 'bolge_id' => 6],
            
            // Ege Bölgesi (bolge_id: 7)
            ['ad' => 'Muğla', 'bolge_id' => 7],
            ['ad' => 'Denizli', 'bolge_id' => 7],
            ['ad' => 'Aydın', 'bolge_id' => 7],
            ['ad' => 'Uşak', 'bolge_id' => 7],
            ['ad' => 'Manisa', 'bolge_id' => 7],
            ['ad' => 'İzmir', 'bolge_id' => 7],
            
            // Merkez Bölgesi (bolge_id: 8)
            ['ad' => 'Kütahya', 'bolge_id' => 8],
            ['ad' => 'Afyon', 'bolge_id' => 8],
            
            // İç Anadolu Bölgesi (bolge_id: 9)
            ['ad' => 'Ankara', 'bolge_id' => 9],
            ['ad' => 'Çankırı', 'bolge_id' => 9],
            ['ad' => 'Kırıkkale', 'bolge_id' => 9],
            ['ad' => 'Kırşehir', 'bolge_id' => 9],
            ['ad' => 'Yozgat', 'bolge_id' => 9],
            ['ad' => 'Nevşehir', 'bolge_id' => 9],
            ['ad' => 'Sivas', 'bolge_id' => 9],
            ['ad' => 'Kayseri', 'bolge_id' => 9],
            ['ad' => 'Malatya', 'bolge_id' => 9],
            ['ad' => 'Aksaray', 'bolge_id' => 9],
            ['ad' => 'Niğde', 'bolge_id' => 9],
            ['ad' => 'Karaman', 'bolge_id' => 9],
            ['ad' => 'Konya', 'bolge_id' => 9],
        ];

        foreach ($sehirler as $sehir) {
            DB::table('sehirler')->insert([
                'ad' => $sehir['ad'],
                'bolge_id' => $sehir['bolge_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
