<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleEnvioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_envio', function (Blueprint $table) {
            $table->increments('id'); //usa 4 bytes. Maximo valor: 2 147 483 647
            $table->string('nombre_producto', 100);
            $table->smallInteger('cantidad'); //-32768 al 32767

            $table->unsignedMediumInteger('envio_id');
            $table->foreign('envio_id')->references('id')->on('envio');
            $table->unsignedMediumInteger('producto_id');
            $table->foreign('producto_id')->references('id')->on('producto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_envio');
    }
}
