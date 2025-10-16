<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAktifToBayilerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bayiler', function (Blueprint $table) {
            $table->boolean('aktif')->default(true)->comment('Bayi aktif durumu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bayiler', function (Blueprint $table) {
            $table->dropColumn('aktif');
        });
    }
}
