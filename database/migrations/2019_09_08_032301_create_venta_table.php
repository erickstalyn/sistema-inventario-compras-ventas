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
            $table->mediumIncrements('id'); // usa 3 bytes, Cantiada max: 8 388 607
            $table->decimal('total', 8, 2);
            $table->decimal('total_faltante', 8, 2)->nullable();
            $table->char('codigo', 15)->nullable();
            $table->char('tipo', 3);
            /*  -> 1er digito: (1) Contado, (2) Credito
                -> 2do digito: (1) Prepago, (2) Postpago
                -> 3er digito: (1) Menor, (2) Mayor */
            
            $table->unsignedSmallInteger('cliente_id')->nullable();
            $table->foreign('cliente_id')->references('id')->on('persona');
            $table->unsignedTinyInteger('centro_id');
            $table->foreign('centro_id')->references('id')->on('centro');

            $table->timestamps(); //Fecha con hora automática
            $table->softDeletes(); // Fecha de anulacion automática
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
