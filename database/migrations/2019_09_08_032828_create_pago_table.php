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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('venta_credito_id');
            
            $table->decimal('monto', 11, 2);
            $table->timestamps();

            $table->foreign('venta_credito_id')->references('id')->on('venta_credito');
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
