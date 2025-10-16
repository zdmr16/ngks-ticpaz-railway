<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            BolgelerSeeder::class,
            SehirlerSeeder::class,
            IlcelerSeeder::class,
            TalepTurleriSeeder::class,
            AsamalarSeeder::class,
            BolgeMimarlariSeeder::class,
            BayiMagazaSeeder::class, // Gerçek bayi ve mağaza verilerini yükler (CSV verileri dahili)
            AdminSeeder::class,
        ]);
    }
}
