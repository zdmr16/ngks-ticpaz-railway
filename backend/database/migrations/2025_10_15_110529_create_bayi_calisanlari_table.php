<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBayiCalisanlariTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bayi_calisanlari', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bayi_id');
            $table->string('ad_soyad', 100);
            $table->string('telefon', 20);
            $table->string('email', 100);
            $table->timestamps();
            
            $table->foreign('bayi_id')->references('id')->on('bayiler')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bayi_calisanlari');
    }
}
