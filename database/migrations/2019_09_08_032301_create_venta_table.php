<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('venta', function (Blueprint $table) {
            $table->increments('id');   // Por ser int y unsigned tiene como valor maximo 4,294'967,295
            $table->char('codigo', 18); // Codigo de barras de la venta. Estructura: [empresa_id|2]-[centro_id|2]-[fecha-hora|14]
            $table->char('tipo', 3);    //  Estructura: [1: contado, 2: credito]-[1: prepago, 2: postpago]-[1: menor, 2: mayor]
            $table->decimal('total', 11, 2);    // Monto total o final
            $table->decimal('total_faltante', 11, 2)->nullable();    // En caso de ser venta al credito aqui se guardara el monto faltante
            $table->decimal('total_venta', 11, 2);   // Monto de la venta
            $table->decimal('total_descuento', 11, 2)->nullable();   // Monto de descuento de la venta
            $table->decimal('importe_impuesto', 11, 2)->nullable();    // Monto total del impuesto
            $table->string('direccion', 400);   // Direccion a la cual se enviara el pedido
            $table->unsignedTinyInteger('centro_id');
            $table->unsignedSmallInteger('cliente_id')->nullable();
            $table->unsignedSmallInteger('usuario_id');
            $table->timestamps(); //Fecha con hora automática
            $table->softDeletes(); // Fecha de anulacion automática

            $table->foreign('cliente_id')->references('id')->on('persona');
            $table->foreign('centro_id')->references('id')->on('centro');
            $table->foreign('usuario_id')->references('id')->on('usuario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('venta');
    }
}
