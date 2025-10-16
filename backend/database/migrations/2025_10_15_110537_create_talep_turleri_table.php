<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalepTurleriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('talep_turleri', function (Blueprint $table) {
            $table->id();
            $table->string('ad', 100);
            $table->enum('is_akisi_tipi', ['tip_a', 'tip_b', 'tip_c']);
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
        Schema::dropIfExists('talep_turleri');
    }
}
