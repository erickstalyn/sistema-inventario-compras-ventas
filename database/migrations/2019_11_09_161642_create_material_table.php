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
            $table->smallIncrements('id');  // Por ser smallint y unsigned tiene como valor maximo 65,535
            $table->string('nombre', 100)->unique();
            $table->string('subtipo', 25);
            $table->string('unidad', 20); //Centimetro, Metro, Gramo, Kilogramo
            $table->decimal('costo', 11, 2); //Es el costo por unidad
            $table->boolean('estado')->default(1);  // Valores admitidos: (1) activado, (0) desactivado
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
