<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleProductoBUTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::unprepared("DELIMITER //
        CREATE TRIGGER detalle_producto_BU
        BEFORE UPDATE 
        ON detalle_producto
        FOR EACH ROW
        BEGIN
            IF(new.substock <> old.substock) THEN
                UPDATE producto 
                set stock = stock + (new.substock-old.substock)
                where id = new.producto_id;
            END IF;
        end
        //
        DELIMITER;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::unprepared('DROP TRIGGER IF EXISTS detalle_producto_BU');
    }
}
