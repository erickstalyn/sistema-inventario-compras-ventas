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
        Schema::create('subproducto', function (Blueprint $table) {
            $table->mediumIncrements('id'); // Por ser mediuminteger y unsigned tiene como valor maximo 16'777,215
            $table->string('nombre', 500)->unique(); //Se obtendrá uniendo el nombre del superproducto, el tamaño y el color
            $table->string('codigo', 50);
            $table->descripcion('descripcion', 100)->nullable();
            $table->longText('caracteristicas');    // Aqui se guardaran las caracteristicas de este subproductos. Por ejemplo: tamaño, color
            $table->decimal('costo_produccion', 8, 2)->default(0);
            $table->decimal('precio_menor', 8, 2);
            $table->decimal('precio_mayor', 8, 2);
            $table->mediumInteger('stock')->default(0); // Por ser mediuminteger y unsigned tiene como valor maximo 16'777,215
            $table->boolean('estado')->default(1);  // Define si el producto esta activo o no. Por ejemplo (1: activado), (0: desactivado)
            $table->unsignedMediumInteger('producto_id');
            $table->date('created_at');
            $table->softDeletes();

            $table->foreign('producto_id')->references('id')->on('producto');
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
