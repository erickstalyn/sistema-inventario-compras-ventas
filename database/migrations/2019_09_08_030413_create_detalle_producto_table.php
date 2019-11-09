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
            $table->increments('id'); //usa 4 bytes. Maximo valor: 2 147 483 647
            $table->smallInteger('substock')->default(0); // usa 2 bytes - máximo 32767 datos
            $table->smallInteger('reservados')->nullable();// usa 2 bytes - máximo 32767 datos
            $table->smallInteger('fallidos')->nullable();// usa 2 bytes - máximo 32767 datos
            $table->decimal('precio_menor', 10,2);
            $table->decimal('precio_mayor', 10,2);

            $table->unsignedTinyInteger('centro_id');
            $table->foreign('centro_id')->references('id')->on('centro');
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
        Schema::dropIfExists('detalle_producto');
    }
}
