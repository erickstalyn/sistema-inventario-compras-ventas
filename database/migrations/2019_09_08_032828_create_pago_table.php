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
            $table->unsignedBigInteger('venta_id');
            
            $table->decimal('monto', 11, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('venta_id')->references('id')->on('venta');
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
