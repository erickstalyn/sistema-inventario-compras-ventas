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
            $table->mediumIncrements('id'); // usa 3 bytes, Cantiada max: 8 388 607
            $table->char('estado', 1)->default('0');
            /*
                0 -> Enviado
                1 -> Recibido
                2 -> Rechazado
                3 -> Anulado
            */
            $table->unsignedTinyInteger('centro_from_id')->nullable();
            $table->foreign('centro_from_id')->references('id')->on('centro');
            $table->unsignedTinyInteger('centro_to_id');
            $table->foreign('centro_to_id')->references('id')->on('centro');
            $table->unsignedMediumInteger('abasto_id')->nullable();
            $table->foreign('abasto_id')->references('id')->on('abasto');
            $table->date('created_at'); // Fecha de ENVIO manual
            $table->date('updated_at'); // Fecha de RECIBIDO O RECHAZO manual
            $table->date('deleted_at'); // Fecha de ANULACION manual
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