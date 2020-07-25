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
            $table->smallIncrements('id');  // Por ser smallint y unsigned tiene como valor maximo 65,535
            $table->unsignedSmallInteger('persona_id');
            $table->unsignedInteger('venta_usada_id')->nullable();
            $table->unsignedInteger('venta_generada_id');
            $table->decimal('monto', 11, 2);
            $table->timestamps(); //hora y fecha automaticaS

            $table->foreign('persona_id')->references('id')->on('persona');
            $table->foreign('venta_usada_id')->references('id')->on('venta');
            $table->foreign('venta_generada_id')->references('id')->on('venta');
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
