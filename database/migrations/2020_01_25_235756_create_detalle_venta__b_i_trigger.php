<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleVentaBITrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::unprepared("DELIMITER //
        CREATE TRIGGER detalle_venta_BI
        BEFORE INSERT 
        ON detalle_venta
        FOR EACH ROW
        BEGIN
            UPDATE detalle_producto dp
            set substock = substock - new.cantidad
            where id = new.detalle_producto_id;
        end
        //
        DELIMITER ;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::unprepared('DROP TRIGGER IF EXISTS detalle_venta_BI');
    }
}
