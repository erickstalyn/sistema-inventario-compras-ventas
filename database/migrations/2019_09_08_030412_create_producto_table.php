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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('categoria_id');

            $table->string('nombre', 60)->unique();
            $table->string('codigo', 50)->nullable();
            $table->string('descripcion', 100)->nullable();
            $table->decimal('precio', 11, 2);
            $table->integer('stock')->default(0);
            $table->boolean('estado')->default(1);
            $table->timestamps();
            $table->softDeletes();

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
