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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('envio_id');
            $table->unsignedBigInteger('producto_id');

            $table->integer('cantidad');

            $table->foreign('envio_id')->references('id')->on('envio');
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
