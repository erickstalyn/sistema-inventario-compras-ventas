<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubproductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('subproducto', function (Blueprint $table) {
            $table->mediumIncrements('id'); // Por ser mediuminteger y unsigned tiene como valor maximo 16'777,215
            $table->string('nombre', 500);  // Se obtendrá uniendo el nombre del superproducto, el tamaño y el color
            $table->string('caracteristicas', 300);
            $table->string('code_unique', 20);  // Codigo cifrado en base 36 para diferenciar los subproductos. Schema: [tipo_caracteristica_id|1]-[caracteristica_id|2]
            $table->char('codigo', 15); // Estructura: [fecha-hora|15]-[numero aleatorio|1]
            $table->string('descripcion', 100)->nullable();
            $table->longText('caracteristicas_json');    // Aqui se guardaran las caracteristicas de este subproductos. Por ejemplo: tamaño, color
            $table->decimal('costo_produccion', 11, 2)->default(0);
            $table->decimal('precio_menor', 11, 2);
            $table->decimal('precio_mayor', 11, 2);
            $table->unsignedMediumInteger('stock')->default(0); // Por ser mediumint y unsigned tiene como valor maximo 16'777,215
            $table->boolean('estado')->default(1);  // Define si el producto esta activo o no. Por ejemplo (1: activado), (0: desactivado)
            $table->unsignedMediumInteger('producto_id');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['producto_id', 'code_unique']);
            $table->foreign('producto_id')->references('id')->on('producto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subproducto');
    }
}
