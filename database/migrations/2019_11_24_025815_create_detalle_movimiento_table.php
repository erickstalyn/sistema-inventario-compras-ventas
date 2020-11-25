<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleMovimientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_movimiento', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion');
            $table->boolean('tipo');
            $table->unsignedInteger('cantidad');
            $table->date('fecha');
            $table->timestamps();
            $table->unsignedInteger('detalle_producto_id');

            $table->foreign('detalle_producto_id')->references('id')->on('detalle_producto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_movimiento');
    }
}
