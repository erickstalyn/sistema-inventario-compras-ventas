<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data', function (Blueprint $table) {
            $table->smallIncrements('id'); // usa 2 bytes - máximo 32767 datos
            $table->char('tipo',1);
            /*
                C -> color
                T -> tamaño
                U -> unidad
            */
            $table->string('subtipo',25)->nullable();
            /*
                Peso
                Longitud
                unidades propiamente dicha :v
            */
            $table->string('nombre', 20);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data');
    }
}
