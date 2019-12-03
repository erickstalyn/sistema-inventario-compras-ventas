<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material', function (Blueprint $table) {
            $table->smallIncrements('id'); // usa 2 bytes - máximo 32767 datos
            $table->string('nombre', 50)->unique();
            $table->string('subtipo', 25);
            $table->string('unidad', 20); //Centimetro, Metro, Gramo, Kilogramo
            $table->decimal('costo', 8, 2); //Es el costo por unidad
            $table->boolean('estado')->default(1); //1: Activado, 0: Desactivado

            //No es necesario fechas en esta tabla
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material');
    }
}
