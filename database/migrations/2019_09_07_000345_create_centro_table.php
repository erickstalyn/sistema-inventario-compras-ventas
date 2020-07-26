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
            $table->tinyIncrements('id');   // Por ser tinyint y unsigned tiene como valor maximo 255
            $table->unsignedTinyInteger('numero_serie');    // Por ser tinyint y unsigned tiene como valor maximo 255
            $table->char('tipo', 1);    // Para saber que rol cumple este centro. Por ejemplo: (P: Puesto), (A: Almacén)
            $table->longText('numeros_correlativos'); // Aqui se guardaran los numeros correlativos de todos los comprobantes
            $table->string('nombre', 100)->unique();
            $table->string('tipo_anexo', 100)->nullable();  // El tipo con el que lo guardan en la sunat
            $table->string('ubigeo', 300)->nullable();
            $table->string('domicilio', 300)->nullable();
            $table->longText('telefonos')->nullable();
            $table->unsignedTinyInteger('empresa_id');
            $table->boolean('estado')->default(1);  // Estado en el que se encuentra este centro. Ejemplos: (0: dado de baja, 1: aun trabaja)
            $table->timestamps();
            $table->softDeletes();

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
