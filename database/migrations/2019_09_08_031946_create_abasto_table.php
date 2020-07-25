<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbastoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('abasto', function (Blueprint $table) {
            $table->increments('id'); // Por ser integer y unsigned tiene como valor maximo 4,294'967,295
            $table->string('proveedor_nombre', 120)->nullable();    // Nombre del proveedor
            $table->decimal('total', 11, 2);
            $table->decimal('total_faltante', 11, 2)->nullable();
            $table->boolean('tipo');    // Tipo de pago que se realiza en este abasto. Por ejemplo: (0: contado), (1: credito)
            $table->unsignedTinyInteger('centro_id')->nullable();
            $table->unsignedSmallInteger('proveedor_id')->nullable();
            $table->unsignedSmallInteger('trabajador_id')->nullable();  // Para saber que usuario hizo esta accion
            $table->dateTime('created_at');
            //Abasto tiene una eliminación física, y solo se puede eliminar un abasto si tiene su estado de envio en ENVIADO

            $table->foreign('proveedor_id')->references('id')->on('persona');
            $table->foreign('trabajador_id')->references('id')->on('usuario');
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
        Schema::dropIfExists('abasto');
    }
}
