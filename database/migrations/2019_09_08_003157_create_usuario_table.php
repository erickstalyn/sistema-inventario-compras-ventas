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
            $table->unsignedBigInteger('persona_id');
            $table->string('usuario', 25);
            $table->string('clave', 25);
            $table->boolean('estado')->default(1);

            $table->foreign('rol_id')->references('id')->on('rol');
            $table->foreign('persona_id')->references('id')->on('persona');

            $table->timestamps();

            
        });

        DB::table('usuario')->insert(array('rol_id'=> 1, 'persona_id'=>1 ,'usuario'=>'silmar', 'clave'=>'silmar'));
        DB::table('usuario')->insert(array('rol_id'=> 2, 'persona_id'=>2 ,'usuario'=>'puestoa', 'clave'=>'puestoa'));
        DB::table('usuario')->insert(array('rol_id'=> 3, 'persona_id'=>3 ,'usuario'=>'almacena', 'clave'=>'almacena'));
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
