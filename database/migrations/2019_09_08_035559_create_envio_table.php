<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnvioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up () {
        Schema::create('envio', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('usuario_to_id');
            $table->unsignedBigInteger('usuario_from_id')->nullable();
            $table->unsignedBigInteger('abasto_id')->nullable();

            $table->char('estado', 1)->default('E');
            $table->timestamps();

            //E -> estado ENVIADO
            //R -> estado RECIBIDO
            //C -> estado CANCELADO

            $table->foreign('usuario_from_id')->references('id')->on('usuario');
            $table->foreign('usuario_to_id')->references('id')->on('usuario');
            $table->foreign('abasto_id')->references('id')->on('abasto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('envio');
    }
}
