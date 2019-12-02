<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->tinyIncrements('id');// usa 1 byte , cantidad max: 127
            $table->string('usuario', 60);
            $table->string('password', 100);
            $table->char('rol',1);
            /*
                M: Administrador
                P: Puesto
                A: Almacén
            */
            $table->boolean('estado')->default('1'); // 1: Activado, 0: Desactivado

            $table->unsignedSmallInteger('persona_id')->nullable();
            $table->foreign('persona_id')->references('id')->on('persona');
            $table->unsignedTinyInteger('centro_id')->nullable();
            $table->foreign('centro_id')->references('id')->on('centro');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario');
    }
}
