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
            $table->mediumIncrements('id'); // Por ser mediuminteger y unsigned tiene como valor maximo 16'777,215
            $table->unsignedInteger('abasto_id')->nullable();
            $table->unsignedInteger('venta_id')->nullable();
            $table->decimal('monto', 11, 2);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable(); 

            $table->foreign('venta_id')->references('id')->on('venta');
            $table->foreign('abasto_id')->references('id')->on('abasto')->onDelete('cascade');
            // $table->timestamps(); //Fecha y hora de creacion y actualiación manual
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
