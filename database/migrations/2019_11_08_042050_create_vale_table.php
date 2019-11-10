<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vale', function (Blueprint $table) {
            $table->smallInteger('id'); //-32768 al 32767
            $table->char('codigo', 10);
            $table->decimal('monto', 8, 2);

            $table->unsignedSmallInteger('persona_id');
            $table->foreign('persona_id')->references('id')->on('persona');
            $table->unsignedMediumInteger('venta_usada')->nullable();
            $table->foreign('venta_usada')->references('id')->on('venta');
            $table->unsignedMediumInteger('venta_generada');
            $table->foreign('venta_generada')->references('id')->on('venta');

            $table->timestamps(); //hora y fecha automaticaS
            $table->softDeletes();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vale');
    }
}
