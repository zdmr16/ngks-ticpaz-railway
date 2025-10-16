<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBolgeMimarAtamalariTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bolge_mimar_atamalari', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bolge_id');
            $table->unsignedBigInteger('bolge_mimari_id');
            $table->timestamps();
            
            $table->foreign('bolge_id')->references('id')->on('bolgeler')->onDelete('cascade');
            $table->foreign('bolge_mimari_id')->references('id')->on('bolge_mimarlari')->onDelete('cascade');
            
            $table->unique(['bolge_id', 'bolge_mimari_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bolge_mimar_atamalari');
    }
}
