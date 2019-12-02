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
            $table->string('nombre', 100)->unique(); //Se obtendrá uniendo el nombre del superproducto, el tamaño y el color
            $table->string('descripcion', 60)->nullable(); // Es la misma que la del superproducto
            $table->char('size', 15)->nullable();
            $table->char('color', 15)->nullable();
            $table->decimal('precio_menor', 8, 2);
            $table->decimal('precio_mayor', 8, 2);
            $table->decimal('costo_produccion', 8, 2)->nullable();
            $table->smallInteger('stock')->default(120); // usa 2 bytes - máximo 32767 datos
            $table->boolean('estado')->default(1);

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
