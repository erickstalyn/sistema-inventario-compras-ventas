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
            $table->mediumIncrements('id'); // Por ser mediuminteger y unsigned tiene como valor maximo 16'777,215
            $table->decimal('total_faltante', 8, 2)->nullable(); //En caso de ser venta al credito aqui se guardara el monto faltante
            $table->decimal('total_venta', 8, 2); //Se guardara el verdadero valor de la venta
            $table->decimal('total_descuento', 8, 2)->nullable(); // Se guardara el descurnto toal de la venta
            $table->decimal('total_impuesto', 8, 2);    // El monto total del impuesto
            $table->decimal('total', 8, 2); // El monto total
            $table->char('codigo', 15)->nullable();
            $table->char('tipo', 3);
            /*  -> 1er digito: (1) Contado, (2) Credito
                -> 2do digito: (1) Prepago, (2) Postpago
                -> 3er digito: (1) Menor, (2) Mayor */
            
            $table->unsignedSmallInteger('cliente_id')->nullable();
            $table->foreign('cliente_id')->references('id')->on('persona');
            $table->unsignedTinyInteger('centro_id');
            $table->foreign('centro_id')->references('id')->on('centro');

            $table->timestamps(); //Fecha con hora automática
            $table->softDeletes(); // Fecha de anulacion automática
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
