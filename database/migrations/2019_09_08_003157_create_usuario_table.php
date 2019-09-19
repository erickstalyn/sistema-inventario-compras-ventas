<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rol_id');
            $table->unsignedBigInteger('persona_id')->unique();

            $table->string('usuario', 25)->unique();
            $table->string('password');
            $table->boolean('estado')->default(1);

            $table->foreign('rol_id')->references('id')->on('rol');
            $table->foreign('persona_id')->references('id')->on('persona');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario');
    }
}
