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
        DB::unprepared("
        CREATE TRIGGER detalle_producto_BU
        BEFORE UPDATE 
        ON detalle_producto
        FOR EACH ROW
        BEGIN
            IF ( NEW.substock <> OLD.substock) THEN
                UPDATE producto 
                SET stock = stock + (NEW.substock-OLD.substock)
                WHERE id = NEW.producto_id;
            END IF;
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
        DB::unprepared('DROP TRIGGER IF EXISTS detalle_producto_BU');
    }
}
