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
            $table->mediumIncrements('id'); // Por ser mediuminteger y unsigned tiene como valor maximo 16'777,215
            $table->string('nombre_producto', 500);
            $table->unsignedSmallInteger('cantidad');   // Por ser smallint y unsigned tiene como valor maximo 65,535
            $table->decimal('costo_abasto', 8, 2);  // Aqui ira el valor del costo del producto por unidad, se ingresa manualmente
            $table->decimal('subtotal', 8, 2);
            $table->unsignedMediumInteger('subproducto_id')->nullable();
            $table->unsignedInteger('abasto_id');

            $table->foreign('subproducto_id')->references('id')->on('subproducto');
            $table->foreign('abasto_id')->references('id')->on('abasto')->onDelete('cascade');
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
