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
        Schema::create('detalle_produccion', function (Blueprint $table) {
            $table->mediumIncrements('id'); // Por ser mediumint y unsigned tiene valor maximo 16'777,215
            $table->unsignedMediumInteger('subproducto_id');
            $table->unsignedMediumInteger('produccion_id');
            $table->string('nombre_producto', 500);
            $table->decimal('costo_produccion', 11, 2);
            $table->unsignedSmallInteger('cantidad');   // Por ser smallint y unsigned tiene valor maximo de 16'777,215
            $table->decimal('subtotal', 11, 2);

            $table->foreign('subproducto_id')->references('id')->on('subproducto');
            $table->foreign('produccion_id')->references('id')->on('produccion')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_produccion');
    }
}
