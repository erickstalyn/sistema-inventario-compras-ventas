<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persona', function (Blueprint $table) {
            $table->smallIncrements('id'); // usa 2 bytes - máximo 32767 datos

            $table->string('nombres', 90)->nullable();
            $table->string('apellidos', 90)->nullable();
            $table->string('razon_social', 350)->nullable();
            $table->char('dni', 8)->unique()->nullable(); // charc(8) equivale a 8 byte
            $table->char('ruc', 11)->unique()->nullable();
            $table->string('direccion', 120)->nullable();
            $table->char('telefono', 30)->nullable(); //Almacenará como máximo 3 telefonos o celulares
            $table->string('email', 100)->nullable();
            $table->date('birthday', 30)->nullable();
            // $table->text('observacion')->nullable();
            $table->char('tipo', 1); // P: Persona, E: Empresa
            // $table->boolean('cliente')->nullable();
            // $table->boolean('proveedor')->nullable();

            $table->unsignedSmallInteger('persona_id')->nullable();
            $table->foreign('persona_id')->references('id')->on('persona');

            // $table->date('created_at'); //Fecha de creacion manual
            // $table->softDeletes(); //Fecha de eliminación automática
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persona');
    }
}
