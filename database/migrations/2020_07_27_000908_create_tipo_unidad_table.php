<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoUnidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_unidad', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('nombre', 100)->unicque();
            $table->boolean('estado')->default(1);  // Indica si este tipo de unidad esta activa o no para su uso. Por ejemplo: (1:activo, 0:inactivo)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_unidad');
    }
}
