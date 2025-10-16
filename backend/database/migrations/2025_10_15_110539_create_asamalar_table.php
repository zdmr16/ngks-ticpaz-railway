<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsamalarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asamalar', function (Blueprint $table) {
            $table->id();
            $table->enum('is_akisi_tipi', ['tip_a', 'tip_b', 'tip_c']);
            $table->string('ad', 100);
            $table->integer('sira')->comment('Workflow sırası');
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
        Schema::dropIfExists('asamalar');
    }
}
