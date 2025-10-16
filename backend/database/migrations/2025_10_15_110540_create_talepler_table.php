<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaleplerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('talepler', function (Blueprint $table) {
            $table->id();
            
            // İlişkili tablolar
            $table->foreignId('bolge_id')->constrained('bolgeler');
            $table->foreignId('bolge_mimari_id')->constrained('bolge_mimarlari');
            $table->foreignId('bayi_id')->constrained('bayiler');
            $table->foreignId('sehir_id')->constrained('sehirler');
            $table->foreignId('ilce_id')->constrained('ilceler');
            $table->foreignId('talep_turu_id')->constrained('talep_turleri');
            $table->foreignId('guncel_asama_id')->constrained('asamalar');
            
            // Talep bilgileri
            $table->enum('magaza_tipi', ['kendi_magazasi', 'tali_bayi']);
            $table->string('magaza_adi');
            $table->text('aciklama');
            
            // Aşama bilgileri
            $table->timestamp('guncel_asama_tarihi');
            $table->text('guncel_asama_aciklamasi')->nullable();
            
            // Durum bilgileri
            $table->boolean('arsivlendi_mi')->default(false);
            $table->timestamp('arsivlenme_tarihi')->nullable();
            
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
        Schema::dropIfExists('talepler');
    }
}
