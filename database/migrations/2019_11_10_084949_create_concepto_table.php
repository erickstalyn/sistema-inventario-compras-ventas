<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConceptoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('concepto', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->boolean('type');    // 1: ingreso, 0: egreso
            $table->string('descripcion', 200);
            $table->decimal('monto', 14, 2);
            $table->dateTime('created_at');

            $table->unsignedSmallInteger('caja_id');
            $table->foreign('caja_id')->references('id')->on('caja');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('concepto');
    }
}
