<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbastoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('abasto', function (Blueprint $table) {
            $table->mediumIncrements('id'); // usa 3 bytes, Cantiada max: 8 388 607

            $table->decimal('total', 8, 2);
            $table->decimal('total_faltante', 8, 2)->nullable();
            $table->boolean('tipo'); // 0: Contado, 1: Credito
            $table->string('proveedor_nombre', 120);

            $table->unsignedSmallInteger('proveedor_id')->nullable();
            $table->foreign('proveedor_id')->references('id')->on('persona');
            $table->unsignedSmallInteger('administrador_id')->nullable();
            $table->foreign('administrador_id')->references('id')->on('persona');
            $table->unsignedTinyInteger('centro_id')->nullable();
            $table->foreign('centro_id')->references('id')->on('centro');

            $table->date('created_at'); //Fecha de creación manual

            //Abasto tiene una eliminación física, y solo se puede eliminar un abasto si tiene su estado de envio en ENVIADO
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abasto');
    }
}
