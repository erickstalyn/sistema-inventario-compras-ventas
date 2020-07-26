<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCajaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('caja', function (Blueprint $table) {
            $table->smallIncrements('id');  // Por ser smallint y unsigned tiene como valor maximo 65'535
            $table->decimal('total_start', 11, 2);  // Saldo al inicio del dia
            $table->decimal('total_end', 11, 2);    // Saldo al final del dia
            $table->decimal('total_ingreso', 11, 2)->default(0);    // Saldo al final del dia
            $table->decimal('total_egreso', 11, 2)->default(0);     // Saldo al final del dia
            $table->boolean('state')->default(1);   // Estado de la caja (0: cerrado, 1: abierto)
            $table->dateTime('start')->nullable();  // Fecha a la que abren la caja
            $table->dateTime('end')->nullable();    // Fecha a la que cierran la caja
            $table->unsignedTinyInteger('centro_id')->nullable();

            $table->foreign('centro_id')->references('id')->on('centro');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caja');
    }
}
