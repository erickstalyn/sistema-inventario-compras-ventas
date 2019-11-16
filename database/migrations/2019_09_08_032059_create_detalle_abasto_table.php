<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleAbastoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('detalle_abasto', function (Blueprint $table) {
            $table->mediumIncrements('id'); // usa 3 bytes, Cantiada max: 8 388 607
            $table->string('nombre_producto',100);
            $table->smallInteger('cantidad'); //-32768 al 32767
            $table->decimal('costo_abasto', 8, 2); // Aqui ira el valor del costo del abasto
            $table->decimal('subtotal', 8, 2);

            $table->unsignedMediumInteger('producto_id')->nullable();
            $table->foreign('producto_id')->references('id')->on('producto');
            $table->unsignedMediumInteger('abasto_id');
            $table->foreign('abasto_id')->references('id')->on('abasto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_abasto');
    }
}
