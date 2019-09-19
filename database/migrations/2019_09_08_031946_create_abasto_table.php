<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbastoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('abasto', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('proveedor_id')->nullable();

            $table->decimal('total', 11, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('proveedor_id')->references('id')->on('proveedor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abasto');
    }
}
