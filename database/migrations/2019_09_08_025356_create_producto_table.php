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
    public function up()
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->mediumIncrements('id'); // Por ser mediuminteger y unsigned tiene como valor maximo 16'777,215
            $table->string('nombre', 300);
            $table->string('modelo', 100);
            $table->string('descripcion', 100)->nullable();
            $table->unsignedMediumInteger('stock')->default(0); // Por ser mediuminteger y unsigned tiene como valor maximo 16'777,215
            $table->boolean('estado')->default(1);  // Define si el producto esta activo o no. Por ejemplo (1: activado), (0: desactivado)
            $table->unsignedSmallInteger('marca_id')->nullable();
            $table->unsignedSmallInteger('categoria_id');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['modelo', 'marca_id', 'categoria_id']);
            $table->foreign('marca_id')->references('id')->on('marca');
            $table->foreign('categoria_id')->references('id')->on('categoria');
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
