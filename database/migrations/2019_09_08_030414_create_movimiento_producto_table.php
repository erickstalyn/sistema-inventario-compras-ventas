<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimientoProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento_producto', function (Blueprint $table) {
            $table->bigIncrements('id');    // Ilimitado
            $table->unsignedInteger('detalle_producto_id');
            $table->unsignedSmallInteger('cantidad');   // Por ser smallint y unsigned tiene como valor maximo 65,535
            $table->char('estado_producto', 1); // El estado del producto. Valores admitidos: (N) stock normal, (R) stock reservado, (F) stock fallido
            $table->boolean('tipo');    // El producto entra o sale. Valores admitidos: (1) entra producto, (0) sale producto
            $table->string('descripcion', 300); // Descripcion o razon por la que entra o sale producto al centro
            $table->timestamps();

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
        Schema::dropIfExists('movimiento_producto');
    }
}
