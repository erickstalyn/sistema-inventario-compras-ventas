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
                    IF ( OLD.abasto_id is NULL ) THEN
                        IF ( OLD.tipo = '1' ) THEN
                            UPDATE detalle_producto dproducto
                                JOIN detalle_envio denvio ON denvio.subproducto_id = dproducto.subproducto_id
                                    AND denvio.envio_id = NEW.id
                                    AND dproducto.centro_id = NEW.centro_to_id
                            SET dproducto.stock = dproducto.stock + denvio.cantidad;
                        END IF;
                        IF ( OLD.tipo = '2' ) THEN
                            UPDATE detalle_producto dproducto
                                JOIN detalle_envio denvio ON denvio.subproducto_id = dproducto.producto_id
                                    AND denvio.envio_id = NEW.id
                                    AND dproducto.centro_id = NEW.centro_to_id
                            SET dproducto.fallidos = dproducto.fallidos + denvio.cantidad;
                        END IF;
                        
                        UPDATE detalle_producto dproducto
                        JOIN detalle_envio denvio		
                            ON denvio.subproducto_id = dproducto.producto_id
                            AND denvio.envio_id = NEW.id
                            AND dproducto.centro_id = NEW.centro_from_id
                        SET dproducto.traslado = dproducto.traslado - denvio.cantidad;
                    ELSE
                        UPDATE detalle_producto dproducto
                        JOIN detalle_abasto dabasto 
                            ON dabasto.producto_id = dproducto.producto_id
                                AND dabasto.abasto_id = NEW.abasto_id
                                AND dproducto.centro_id = NEW.centro_to_id
                        SET dproducto.stock = dproducto.stock + dabasto.cantidad;
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
