<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_producto', function (Blueprint $table) {
            $table->increments('id'); // Por ser integer y unsigned tiene como valor maximo 4,294'967,295
            $table->unsignedSmallInteger('stock')->default(0);   // Por ser mediuminteger y unsigned tiene como valor maximo 16'777,215
            $table->unsignedSmallInteger('reservados')->default(0); // Por ser mediuminteger y unsigned tiene como valor maximo 16'777,215
            $table->unsignedSmallInteger('fallidos')->default(0);   // Por ser mediuminteger y unsigned tiene como valor maximo 16'777,215
            $table->unsignedSmallInteger('traslado')->default(0);   // Por ser mediuminteger y unsigned tiene como valor maximo 16'777,215
            $table->decimal('precio_menor', 8, 2);
            $table->decimal('precio_mayor', 8, 2);
            $table->unsignedTinyInteger('centro_id');
            $table->unsignedMediumInteger('producto_id');

            $table->foreign('centro_id')->references('id')->on('centro');
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
        Schema::dropIfExists('detalle_producto');
    }
}
