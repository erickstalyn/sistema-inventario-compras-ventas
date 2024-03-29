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
            $table->mediumIncrements('id'); // Por ser mediumint y unsigned tiene valor maximo 16'777,215
            $table->unsignedTinyInteger('almacen_id');
            $table->decimal('total', 11, 2); // Costo total de la produccion
            $table->date('fecha_inicio'); //Fecha en la que se iniciará la produccion
            $table->date('fecha_programada'); //Fecha en la que esta programada su culminacion
            $table->date('fecha_fin')->nullable(); //Fecha en la que se terminó, con esta fecha sabré si se culminó o no una produccion

            $table->foreign('almacen_id')->references('id')->on('centro');
            //Los estados que tiene es: Sin Iniciar, En Proceso, Finalizado
            //Las producciones tiene un eliminado fisico
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
