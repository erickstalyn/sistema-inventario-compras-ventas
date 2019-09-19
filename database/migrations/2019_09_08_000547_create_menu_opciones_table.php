<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuOpcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_opciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rol_id')->unique();
            
            $table->boolean('productos')->default(0);
            $table->boolean('categorias')->default(0);
            $table->boolean('usuarios')->default(0);
            $table->boolean('abastecimientos')->default(0);
            $table->boolean('ventas')->default(0);
            $table->boolean('envios')->default(0);
            $table->boolean('proveedores')->default(0);
            $table->boolean('puestos')->default(0);
            $table->boolean('almacenes')->default(0);
            $table->boolean('estadisticas')->default(0);

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
        Schema::dropIfExists('menu_opciones');
    }
}
