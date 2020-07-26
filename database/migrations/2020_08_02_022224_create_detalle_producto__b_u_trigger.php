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
                IF ( NEW.stock <> OLD.stock OR NEW.reservados <> OLD.reservados OR NEW.fallidos <> OLD.fallidos OR NEW.traslado <> OLD.traslado ) THEN
                    UPDATE subproducto
                    SET stock = stock + (NEW.stock-OLD.stock) + (NEW.reservados-OLD.reservados) + (NEW.fallidos-OLD.fallidos) + (NEW.traslado-OLD.traslado)
                    WHERE id = NEW.subproducto_id;
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
