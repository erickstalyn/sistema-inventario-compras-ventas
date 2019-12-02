<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuperproductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('superproducto', function (Blueprint $table) {
            $table->mediumIncrements('id'); // usa 3 bytes, Cantiada max: 8 388 607
            $table->string('nombre', 100)->unique();
            $table->string('descripcion', 60)->nullable();
            $table->smallInteger('superstock')->default(0); // usa 2 bytes - máximo 32767 datos
            $table->boolean('estado')->default(1); //Activado: 1, Desactivado: 0

            $table->date('created_at'); //Fecha de creacion manual
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('superproducto');
    }
}
