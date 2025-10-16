<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSehirlerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sehirler', function (Blueprint $table) {
            $table->id();
            $table->string('ad', 100);
            $table->unsignedBigInteger('bolge_id');
            $table->timestamps();
            
            $table->foreign('bolge_id')->references('id')->on('bolgeler')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sehirler');
    }
}
