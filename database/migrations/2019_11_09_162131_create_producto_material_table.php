<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductoMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto_material', function (Blueprint $table) {
            $table->smallIncrements('id'); // usa 2 bytes - máximo 32767 datos
            $table->decimal('precio_unitario', 8,2);
            $table->smallInteger('cantidad'); // usa 2 bytes - máximo 32767 datos
            $table->string('unidad',45); //cm, m, gr, kg
            $table->decimal('subtotal', 8,2); // se hace la conversion si es necesaria y se autocalcula

            $table->unsignedMediumInteger('producto_id');
            $table->foreign('producto_id')->references('id')->on('producto');
            $table->unsignedSmallInteger('material_id');
            $table->foreign('material_id')->references('id')->on('material');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('producto_material');
    }
}
