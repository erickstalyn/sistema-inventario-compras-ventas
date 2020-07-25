<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleEnvioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_envio', function (Blueprint $table) {
            $table->increments('id');   // Por ser integer y unsigned tiene como valor maximo 4,294'967,295
            $table->unsignedMediumInteger('envio_id');
            $table->unsignedMediumInteger('subproducto_id');
            $table->string('nombre_producto', 500);
            $table->unsignedSmallInteger('cantidad');   // Por ser smallint y unsigned tiene como valor maximo 65,535
            
            $table->foreign('envio_id')->references('id')->on('envio')->onDelete('cascade');
            $table->foreign('subproducto_id')->references('id')->on('subproducto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_envio');
    }
}
