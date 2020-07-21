<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rol', function (Blueprint $table) {
            $table->tinyIncrements('id');   // Por ser tinyint y unsigned tiene como valor maximo 255
            $table->string('descripcion', 120); // Roles que los usuarios pueden cumplir. Por ejemplo: Administrador, Almacenero, Vendedor
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rol');
    }
}
