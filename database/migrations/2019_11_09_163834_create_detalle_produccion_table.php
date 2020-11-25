<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleProduccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('detalle_produccion', function (Blueprint $table) {
        //     $table->mediumIncrements('id'); // usa 3 bytes, Cantiada max: 8 388 607
        //     $table->string('nombre_producto',100);
        //     $table->decimal('costo_produccion',8,2);
        //     $table->smallInteger('cantidad'); // usa 2 bytes - máximo 32767 datos
        //     $table->decimal('subtotal', 8,2);

        //     $table->unsignedMediumInteger('producto_id');
        //     $table->foreign('producto_id')->references('id')->on('producto');
        //     $table->unsignedMediumInteger('produccion_id');
        //     $table->foreign('produccion_id')->references('id')->on('produccion')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('detalle_produccion');
    }
}
