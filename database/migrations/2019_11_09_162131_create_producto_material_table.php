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
            $table->smallIncrements('id');  // Por ser smallint y unsigned tiene como valor maximo 65,535
            $table->unsignedMediumInteger('subproducto_id');
            $table->unsignedSmallInteger('material_id');
            $table->string('nombre', 100); 
            $table->string('unidad', 45); //cm, m, gr, kg
            $table->decimal('costo_unitario', 11, 2); // se hace la conversion si es necesaria y se autocalcula
            $table->smallInteger('cantidad'); // usa 2 bytes - máximo 32767 datos
            $table->decimal('subtotal', 11, 2); // es: costo unitario x cantidad

            $table->foreign('subproducto_id')->references('id')->on('subproducto');
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
