<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsamalarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();
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

        DB::table('asamalar')->insert($asamalar);
    }
}
