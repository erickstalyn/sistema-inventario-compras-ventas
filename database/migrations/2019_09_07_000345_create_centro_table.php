<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCentroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centro', function (Blueprint $table) {
            $table->tinyIncrements('id'); // usa 1 byte , cantidad max: 127
            $table->string('nombre', 60)->unique();
            $table->string('direccion', 120)->nullable();
            $table->char('telefono', 30)->nullable();
            $table->char('tipo', 1); //P: Puesto, A: Almacén
            
            $table->date('created_at'); //Fecha de creacion manual
            $table->softDeletes(); //Fecha de eliminación automatica
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('centro');
    }
}
