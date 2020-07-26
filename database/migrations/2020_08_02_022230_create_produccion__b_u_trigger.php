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
                IF ( NEW.fecha_fin IS NOT NULL ) THEN
                    UPDATE detalle_producto dproducto
                    JOIN detalle_produccion dproduccion
                        ON dproduccion.subproducto_id = dproducto.subproducto_id
                            AND dproduccion.produccion_id = NEW.id
                            AND dproducto.centro_id = NEW.almacen_id
                    SET dproducto.stock = dproducto.stock + dproduccion.cantidad;
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
