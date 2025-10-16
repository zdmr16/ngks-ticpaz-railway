<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kullanici;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Test admin kullanıcısı oluştur
        Kullanici::create([
            'ad_soyad' => 'Test Admin',
            'email' => 'admin@test.com',
            'sifre' => '123456', // Model'de otomatik hash'lenecek
            'rol' => 'admin'
        ]);

        // Test pazarlama uzmanı
        Kullanici::create([
            'ad_soyad' => 'Test Pazarlama Uzmanı',
            'email' => 'pazarlama@test.com',
            'sifre' => '123456',
            'rol' => 'pazarlama_uzmani'
        ]);

        // Test direktör
        Kullanici::create([
            'ad_soyad' => 'Test Direktör',
            'email' => 'direktor@test.com',
            'sifre' => '123456',
            'rol' => 'direktor'
        ]);

        echo "Admin kullanıcıları oluşturuldu.\n";
        echo "Test kullanıcıları:\n";
        echo "Admin: admin@test.com / 123456\n";
        echo "Pazarlama: pazarlama@test.com / 123456\n";
        echo "Direktör: direktor@test.com / 123456\n";
    }
}