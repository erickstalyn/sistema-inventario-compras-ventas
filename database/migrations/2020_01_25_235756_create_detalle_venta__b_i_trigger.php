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
        DB::unprepared("
        CREATE TRIGGER detalle_venta_BI
        BEFORE INSERT 
        ON detalle_venta
        FOR EACH ROW
        BEGIN
            UPDATE detalle_producto 
            SET substock = substock - NEW.cantidad
            WHERE id = NEW.detalle_producto_id;
        END
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS detalle_venta_BI');
    }
}
