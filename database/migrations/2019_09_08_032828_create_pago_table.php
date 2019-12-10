<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('pago', function (Blueprint $table) {
            $table->mediumIncrements('id'); // usa 3 bytes, Cantiada max: 8 388 607
            $table->decimal('monto', 8, 2);

            $table->unsignedMediumInteger('venta_id')->nullable();
            $table->foreign('venta_id')->references('id')->on('venta');
            $table->unsignedMediumInteger('abasto_id')->nullable();
            $table->foreign('abasto_id')->references('id')->on('abasto');
            // $table->timestamps(); //Fecha y hora de creacion y actualiación manual
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable(); 
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pago');
    }
}
