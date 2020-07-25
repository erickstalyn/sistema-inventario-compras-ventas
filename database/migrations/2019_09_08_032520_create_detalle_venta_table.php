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
            $table->increments('id');   // Por ser integer y unsigned tiene como valor maximo 4,294'967,295
            $table->unsignedInteger('venta_id');
            $table->unsignedInteger('detalle_producto_id')->nullable();
            $table->string('nombre_producto', 500);
            $table->smallInteger('cantidad');   // Por ser smallint y unsigned tiene como valor maximo 65,535
            $table->smallInteger('cantidad_fallido')->nullable();   // Por ser smallint y unsigned tiene como valor maximo 65,535
            $table->char('tipo_precio', 1); // Valores admitidos: (U) al por menor o por unidad, (M) al por mayor
            $table->decimal('precio', 11, 2);
            $table->decimal('subtotal', 11, 2);

            $table->foreign('venta_id')->references('id')->on('venta');
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
