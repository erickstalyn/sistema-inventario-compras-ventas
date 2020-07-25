<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProduccionBUTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
        CREATE TRIGGER produccion_BU
        BEFORE UPDATE 
        ON produccion
        FOR EACH ROW
        BEGIN
            IF ( NEW.fecha_fin is not NULL ) THEN
            UPDATE detalle_producto dproducto
            JOIN detalle_produccion dproduccion
                ON dproduccion.producto_id = dproducto.producto_id
                    AND dproduccion.produccion_id = NEW.id
                    AND dproducto.centro_id = NEW.almacen_id
            SET dproducto.substock = dproducto.substock + dproduccion.cantidad;
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
        DB::unprepared('DROP TRIGGER IF EXISTS produccion_BU');
    }
}
