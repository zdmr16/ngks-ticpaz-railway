<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIlcelerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ilceler', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sehir_id');
            $table->string('ad', 100);
            $table->timestamps();
            
            $table->foreign('sehir_id')->references('id')->on('sehirler')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ilceler');
    }
}
