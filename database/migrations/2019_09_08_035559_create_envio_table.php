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
            $table->unsignedBigInteger('usuario_from_id')->nullable();
            $table->unsignedBigInteger('usuario_to_id');
            $table->unsignedBigInteger('id_abasto')->nullable();

            $table->boolean('estado')->default(0);

            $table->foreign('usuario_from_id')->references('id')->on('usuario');
            $table->foreign('usuario_to_id')->references('id')->on('usuario');
            
            $table->timestamps();
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
