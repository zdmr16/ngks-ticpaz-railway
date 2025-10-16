<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBayilerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bayiler', function (Blueprint $table) {
            $table->id();
            $table->string('ad', 200)->comment('Bayi işletme adı');
            $table->string('sahip_adi', 100)->nullable();
            $table->string('sahip_telefon', 20)->nullable();
            $table->string('sahip_email', 100)->nullable();
            $table->unsignedBigInteger('sehir_id');
            $table->unsignedBigInteger('ilce_id');
            $table->timestamps();
            
            $table->foreign('sehir_id')->references('id')->on('sehirler')->onDelete('restrict');
            $table->foreign('ilce_id')->references('id')->on('ilceler')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bayiler');
    }
}
