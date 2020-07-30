<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversion', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->decimal('factor', 12, 6);
            $table->unsignedTinyInteger('unidad_from_id');
            $table->unsignedTinyInteger('unidad_to_id');
            
            $table->foreign('unidad_from_id')->references('id')->on('unidad');
            $table->foreign('unidad_to_id')->references('id')->on('unidad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversion');
    }
}
