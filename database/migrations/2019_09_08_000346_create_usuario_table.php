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
            $table->smallIncrements('id');  // Por ser smallint y unsigned tiene como valor maximo 65,535
            $table->string('usuario', 60);
            $table->string('password', 100);
            $table->unsignedTinyInteger('rol'); // Define el rol que este usuario cumplira.
            $table->boolean('estado')->default(1);    // Define si el usuario esta activo o no. Por ejemplo (1: activado), (0: desactivado)
            $table->unsignedSmallInteger('persona_id');
            $table->unsignedTinyInteger('centro_id')->nullable();
            $table->unsignedTinyInteger('rol_id');

            $table->foreign('persona_id')->references('id')->on('persona');
            $table->foreign('centro_id')->references('id')->on('centro');
            $table->foreign('rol_id')->references('id')->on('rol');
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
