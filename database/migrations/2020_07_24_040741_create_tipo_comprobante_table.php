<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoComprobanteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_comprobante', function (Blueprint $table) {
            $table->tinyIncrements('id');   // Por ser tinyint y unsigned tiene como valor maximo 255
            $table->string('descripcion', 100);
            $table->unsignedSmallInteger('numero_correlativo_max')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_comprobante');
    }
}
