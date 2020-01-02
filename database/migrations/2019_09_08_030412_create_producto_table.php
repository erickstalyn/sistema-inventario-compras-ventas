<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('producto', function (Blueprint $table) {
            $table->mediumIncrements('id'); // usa 3 bytes, Cantiada max: 8 388 607
            $table->string('codigo', 13)->nullable();
            $table->string('nombre', 130)->unique(); //Se obtendrá uniendo el nombre del superproducto, el tamaño y el color
            $table->string('size', 15);
            $table->string('color', 15);
            $table->decimal('precio_menor', 8, 2);
            $table->decimal('precio_mayor', 8, 2);
            $table->decimal('costo_produccion', 8, 2)->default(0);
            $table->smallInteger('stock')->default(0); // usa 2 bytes - máximo 32767 datos

            $table->date('created_at'); //Fecha de creacion manual

            $table->unsignedMediumInteger('superproducto_id');
            $table->foreign('superproducto_id')->references('id')->on('superproducto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('producto');
    }
}
