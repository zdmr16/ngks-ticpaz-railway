<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalepAsamaGecmisiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('talep_asama_gecmisi', function (Blueprint $table) {
            $table->id();
            
            // İlişkili tablolar
            $table->foreignId('talep_id')->constrained('talepler');
            $table->foreignId('asama_id')->constrained('asamalar');
            $table->foreignId('degistiren_kullanici_id')->constrained('kullanicilar');
            
            // Geçmiş bilgileri
            $table->text('aciklama');
            $table->timestamp('degistirilme_tarihi');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('talep_asama_gecmisi');
    }
}
