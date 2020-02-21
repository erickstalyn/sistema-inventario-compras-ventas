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
            // $table->tinyIncrements('id');// usa 1 byte , cantidad max: 127
            $table->unsignedSmallInteger('persona_id');
            $table->foreign('persona_id')->references('id')->on('persona');
            $table->unsignedTinyInteger('funcion_id');
            $table->foreign('funcion_id')->references('id')->on('funcion');
            $table->primary(['persona_id', 'funcion_id']);
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
