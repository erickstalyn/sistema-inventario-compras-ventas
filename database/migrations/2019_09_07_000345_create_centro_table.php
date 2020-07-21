<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCentroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centro', function (Blueprint $table) {
            $table->tinyIncrements('id');   // Por ser tinyint y unsigned tiene 255 datos disponibles
            $table->string('nombre', 100)->unique();
            $table->string('direccion', 300)->nullable();
            $table->longText('telefonos')->nullable();
            $table->unsignedTinyInteger('numero_serie');    // Por ser tinyint y unsigned tiene como valor maximo 255
            $table->longText('numero_correlativo'); // Aqui se guardaran los numeros correlativos de todos los comprobantes
            $table->char('tipo', 1);    // Para saber que rol cumple este centro. Por ejemplo: (P: Puesto), (A: Almacén)
            $table->unsignedTinyInteger('empresa_id');
            $table->date('created_at'); // Fecha de creacion manual
            $table->softDeletes();  // Fecha de eliminación automatica

            $table->foreign('empresa_id')->references('id')->on('empresa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('centro');
    }
}
