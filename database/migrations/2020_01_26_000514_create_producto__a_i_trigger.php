<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductoAITrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::unprepared("
        // CREATE TRIGGER producto_AI
        // AFTER INSERT
        // ON producto
        // FOR EACH ROW
        // BEGIN
        //     INSERT INTO detalle_producto(substock, precio_menor, precio_mayor, centro_id, producto_id)
        //     values(0, new.precio_menor, new.precio_mayor, 1, new.id);
        
        //     INSERT INTO detalle_producto(substock, precio_menor, precio_mayor, centro_id, producto_id)
        //     values(0, new.precio_menor, new.precio_mayor, 2, new.id);
        // end
        // ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // DB::unprepared('DROP TRIGGER IF EXISTS producto_AI');
    }
}
