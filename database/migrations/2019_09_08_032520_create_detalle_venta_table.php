<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('detalle_venta', function (Blueprint $table) {
            $table->increments('id'); // usa 4 bytes. Maximo valor: 2 147 483 647
            $table->string('nombre_producto', 100);
            $table->smallInteger('cantidad'); // usa 2 bytes - máximo 32767 datos
            $table->decimal('precio', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->smallInteger('fallidos')->nullable(); // usa 2 bytes - máximo 32767 datos

            $table->unsignedMediumInteger('venta_id');
            $table->foreign('venta_id')->references('id')->on('venta');
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
        Schema::dropIfExists('detalle_venta');
    }
}
