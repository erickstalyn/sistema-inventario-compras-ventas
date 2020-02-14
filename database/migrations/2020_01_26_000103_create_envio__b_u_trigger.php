<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnvioBUTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
        CREATE TRIGGER envio_BU
        BEFORE UPDATE 
        ON envio
        FOR EACH ROW
        BEGIN
            IF ( NEW.estado = 1 ) THEN	
                IF ( OLD.abasto_id is not NULL ) THEN
                    UPDATE detalle_producto dproducto
                    JOIN detalle_abasto dabasto 
                        ON dabasto.producto_id = dproducto.producto_id
                            AND dabasto.abasto_id = NEW.abasto_id
                            AND dproducto.centro_id = NEW.centro_to_id
                    SET dproducto.substock = dproducto.substock + dabasto.cantidad;	
                ELSE
                    UPDATE detalle_producto dproducto
                        JOIN detalle_envio denvio ON denvio.producto_id = dproducto.producto_id
                                    AND denvio.envio_id = NEW.id
                            AND dproducto.centro_id = NEW.centro_to_id
                    SET dproducto.substock = dproducto.substock + denvio.cantidad;
                    UPDATE detalle_producto dproducto
                    JOIN detalle_envio denvio		
                        ON denvio.producto_id = dproducto.producto_id
                        AND denvio.envio_id = NEW.id
                        AND dproducto.centro_id = NEW.centro_from_id
                    SET dproducto.traslado = dproducto.traslado - denvio.cantidad;
                END IF;
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
        DB::unprepared('DROP TRIGGER IF EXISTS envio_BU');
    }
}
