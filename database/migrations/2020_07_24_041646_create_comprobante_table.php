<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprobanteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprobante', function (Blueprint $table) {
            $table->bigIncrements('id');    // Ilimitado
            $table->char('numero_serie', 4);
            $table->unsignedSmallInteger('numero_correlativo');
            $table->string('tipo_comprobante', 100);
            $table->unsignedInteger('venta_id');
            $table->unsignedTinyInteger('tipo_comprobante_id');

            $table->foreign('venta_id')->references('id')->on('venta');
            $table->foreign('tipo_comprobante_id')->references('id')->on('tipo_comprobante');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comprobante');
    }
}
