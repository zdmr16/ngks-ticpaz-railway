<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBayiMagazalariTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bayi_magazalari', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bayi_id')->comment('Hangi bayiye ait mağaza');
            $table->string('ad', 200)->comment('Mağaza adı');
            $table->unsignedBigInteger('sehir_id')->comment('Mağaza şehri');
            $table->unsignedBigInteger('ilce_id')->comment('Mağaza ilçesi');
            $table->text('aciklama')->nullable()->comment('Mağaza açıklaması');
            $table->boolean('aktif')->default(true)->comment('Mağaza aktif mi');
            $table->timestamps();
            
            $table->foreign('bayi_id')->references('id')->on('bayiler')->onDelete('cascade');
            $table->foreign('sehir_id')->references('id')->on('sehirler')->onDelete('cascade');
            $table->foreign('ilce_id')->references('id')->on('ilceler')->onDelete('cascade');
            $table->index(['bayi_id', 'aktif']);
            $table->index(['sehir_id', 'ilce_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bayi_magazalari');
    }
}
