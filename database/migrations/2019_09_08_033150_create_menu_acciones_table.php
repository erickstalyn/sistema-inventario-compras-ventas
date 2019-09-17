<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuAccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_acciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('menu_opciones_id');
            $table->string('nombre', 20);
            $table->boolean('listar')->default(1);
            $table->boolean('agregar')->default(1);
            $table->boolean('editar')->default(1);
            $table->boolean('eliminar')->default(1);

            $table->foreign('usuario_id')->references('id')->on('usuario');
            $table->foreign('menu_opciones_id')->references('id')->on('menu_opciones');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_acciones');
    }
}
