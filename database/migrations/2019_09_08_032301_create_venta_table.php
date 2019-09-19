<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('venta', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('persona_id')->nullable();

            $table->decimal('total', 11, 2);
            $table->decimal('total_restante', 11, 2)->nullable();
            $table->decimal('total_minimo', 11, 2)->nullable();
            $table->char('tipo', 2)->default('VN');
            $table->timestamps();

            //VN -> VENTA NORMAL
            //CD -> CANCELADO por DEFECTO
            
            $table->foreign('persona_id')->references('id')->on('persona');
            $table->foreign('usuario_id')->references('id')->on('usuario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('venta');
    }
}
