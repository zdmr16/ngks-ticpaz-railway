<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TalepTurleriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();
        $talepTurleri = [
            // TIP_A (ID: 1-5)
            ['ad' => 'Kayar Pano', 'is_akisi_tipi' => 'tip_a', 'created_at' => $now, 'updated_at' => $now],
            ['ad' => 'Dijital Baskı', 'is_akisi_tipi' => 'tip_a', 'created_at' => $now, 'updated_at' => $now],
            ['ad' => 'Dış Dijital Baskı', 'is_akisi_tipi' => 'tip_a', 'created_at' => $now, 'updated_at' => $now],
            ['ad' => 'Tabela', 'is_akisi_tipi' => 'tip_a', 'created_at' => $now, 'updated_at' => $now],
            ['ad' => 'Totem', 'is_akisi_tipi' => 'tip_a', 'created_at' => $now, 'updated_at' => $now],
            
            // TIP_B (ID: 6-7)
            ['ad' => 'Teşhir Yenileme', 'is_akisi_tipi' => 'tip_b', 'created_at' => $now, 'updated_at' => $now],
            ['ad' => 'Mağaza Projelendirme', 'is_akisi_tipi' => 'tip_b', 'created_at' => $now, 'updated_at' => $now],
            
            // TIP_C (ID: 8)
            ['ad' => 'Teşhir İade', 'is_akisi_tipi' => 'tip_c', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('talep_turleri')->insert($talepTurleri);
    }
}
