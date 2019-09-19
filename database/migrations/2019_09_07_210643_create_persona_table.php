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
            $table->bigIncrements('id');

            $table->string('nombre', 60)->unique();
            $table->char('dni', 8)->nullable();
            $table->char('ruc', 11)->nullable();
            $table->string('direccion', 120)->nullable();
            $table->char('telefono', 15)->nullable();
            $table->string('email', 30)->nullable();
            $table->date('birthday', 30)->nullable();
            $table->text('observacion')->nullable();
            $table->char('tipo', 1);

            //N -> persona NATURAL
            //J -> persona JURIDICA
            //P -> PUESTO
            //A -> ALMACEN

            $table->timestamps();
            $table->softDeletes();
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
