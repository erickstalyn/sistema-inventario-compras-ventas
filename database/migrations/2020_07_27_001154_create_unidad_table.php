<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidad', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('nombre', 100);
            $table->boolean('estado')->default(1);  // Sirve para saber si esta unidad esta activa o no para su uso. Por ejemplo: (1: activo, 0: inactivo)
            $table->unsignedTinyInteger('tipo_unidad_id');

            $table->unique(['tipo_unidad_id', 'nombre']);
            $table->foreign('tipo_unidad_id')->references('id')->on('tipo_unidad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unidad');
    }
}
