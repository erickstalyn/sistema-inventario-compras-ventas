<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProduccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produccion', function (Blueprint $table) {
            $table->mediumIncrements('id'); // usa 3 bytes, Cantiada max: 8 388 607
            $table->decimal('total', 8,2); // Costo total de la produccion
            $table->date('fecha_inicio'); //Fecha en la que se iniciará la produccion
            $table->date('fecha_programada'); //Fecha en la que esta programada su culminacion
            $table->date('fecha_fin'); //Fecha en la que se terminó
            $table->boolean('estado')->default(0); // 0 -> aun no ha sido completado, 1 -> ya esta completado
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produccion');
    }
}
