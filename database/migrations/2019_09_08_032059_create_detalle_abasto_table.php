<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleAbastoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('detalle_abasto', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('abasto_id');
            
            $table->integer('cantidad');
            $table->decimal('precio', 11, 2);
            $table->decimal('subtotal', 11, 2);

            $table->foreign('producto_id')->references('id')->on('producto');
            $table->foreign('abasto_id')->references('id')->on('abasto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_abasto');
    }
}
