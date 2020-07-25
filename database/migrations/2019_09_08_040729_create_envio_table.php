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
            $table->mediumIncrements('id'); // Por ser mediuminteger y unsigned tiene como valor maximo 16'777,215
            $table->char('estado', 1)->default('0');    // Valores admitidos: (0) enviado, (1) recibido
            $table->char('tipo', 1);    // (1) producto normal, (2) producto fallido
            $table->unsignedTinyInteger('centro_from_id')->nullable();
            $table->unsignedTinyInteger('centro_to_id');
            $table->unsignedInteger('abasto_id')->nullable();
            $table->date('created_at'); // Fecha de ENVIO manual
            $table->date('updated_at')->nullable(); // Fecha de ACEPTADO O RECHAZO manual (SE ACEPTARA CUANDO LA MERCADERÍA YA ESTÉ EN EL CENTRO)

            $table->foreign('centro_from_id')->references('id')->on('centro');
            $table->foreign('centro_to_id')->references('id')->on('centro');
            $table->foreign('abasto_id')->references('id')->on('abasto')->onDelete('cascade');
            // $table->date('deleted_at')->nullable(); // Fecha de ANULACION manual
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
