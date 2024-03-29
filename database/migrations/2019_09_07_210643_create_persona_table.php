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
            $table->smallIncrements('id');  // Por ser smallint y unsigned tiene como valor maximo 65,535
            $table->string('nombres', 100)->nullable();
            $table->string('apellidos', 100)->nullable();
            $table->string('razon_social', 350)->nullable();
            $table->string('tipo_documento', 20);
            $table->string('numero_documento', 20)->unique();
            $table->longText('direcciones')->nullable();    // Aqui se guardaran todas las direcciones en formato JSON
            $table->longText('telefonos')->nullable();  // Aqui se guardaran todos los telefonos en formato JSON
            $table->longText('emails')->nullable(); // Aqui se guardaran todos los emails en formato JSON
            $table->date('birthday', 30)->nullable();
            $table->char('tipo', 1);    // Que tipo de cliente es. Por ejemplo: (P: persona), (E: empresa)
            $table->unsignedSmallInteger('persona_id')->nullable();
            $table->boolean('estado')->default(1);
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('persona');
    }
}
