<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleFuncionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_funcion', function (Blueprint $table) {
            $table->unsignedSmallInteger('persona_id'); // Por ser smallint y unsigned tiene como valor maximo 65,535
            $table->unsignedTinyInteger('funcion_id');  // Por ser tinyint y unsigned tiene como valor maximo 255
            $table->boolean('estado')->default(1);  // Significa que la persona puede tener o no esa funcion activada. Por ejemplo: (1: activado), (0: desactivado)
            
            $table->primary(['persona_id', 'funcion_id']);  // Se convierte a las 2 llaves foraneas en una llave primaria compuesta

            $table->foreign('persona_id')->references('id')->on('persona');
            $table->foreign('funcion_id')->references('id')->on('funcion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_funcion');
    }
}
