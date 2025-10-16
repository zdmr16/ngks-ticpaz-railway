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
        $talepTurleri = [
            // TIP_A
            ['ad' => 'Kayar Pano', 'is_akisi_tipi' => 'tip_a'],
            ['ad' => 'Dijital Baskı', 'is_akisi_tipi' => 'tip_a'],
            ['ad' => 'Dış Dijital Baskı', 'is_akisi_tipi' => 'tip_a'],
            ['ad' => 'Tabela', 'is_akisi_tipi' => 'tip_a'],
            ['ad' => 'Totem', 'is_akisi_tipi' => 'tip_a'],
            
            // TIP_B
            ['ad' => 'Teşhir Yenileme', 'is_akisi_tipi' => 'tip_b'],
            ['ad' => 'Mağaza Projelendirme', 'is_akisi_tipi' => 'tip_b'],
            
            // TIP_C
            ['ad' => 'Teşhir İade', 'is_akisi_tipi' => 'tip_c'],
        ];

        DB::table('talep_turleri')->insert($talepTurleri);
    }
}
