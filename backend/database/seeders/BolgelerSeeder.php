<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BolgelerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bolgeler = [
            ['ad' => 'İstanbul'],
            ['ad' => 'G.Marmara ve B.Karadeniz'],
            ['ad' => 'Karadeniz'],
            ['ad' => 'Güney Anadolu'],
            ['ad' => 'D.Akdeniz'],
            ['ad' => 'B.Akdeniz'],
            ['ad' => 'Ege'],
            ['ad' => 'Merkez'],
            ['ad' => 'İç Anadolu'],
        ];

        DB::table('bolgeler')->insert($bolgeler);
    }
}
