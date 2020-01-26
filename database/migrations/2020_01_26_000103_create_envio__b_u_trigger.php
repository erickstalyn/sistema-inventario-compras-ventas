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
        Schema::unprepared("DELIMITER //
        CREATE TRIGGER envio_BU
        BEFORE UPDATE 
        ON envio
        FOR EACH ROW
        BEGIN
            IF(new.estado = 1) THEN	
                IF(old.abasto_id is not NULL) THEN
                            UPDATE detalle_producto dproducto
                            JOIN detalle_abasto dabasto
                                ON dabasto.producto_id = dproducto.producto_id
                                AND dabasto.abasto_id = new.abasto_id
                                AND dproducto.centro_id = new.centro_to_id
                            SET dproducto.substock = dproducto.substock + dabasto.cantidad;	
                ELSE
                    UPDATE detalle_producto dproducto
                        JOIN detalle_envio denvio ON denvio.producto_id = dproducto.producto_id
                                    AND denvio.envio_id = new.id
                            AND dproducto.centro_id = new.centro_to_id
                    SET dproducto.substock = dproducto.substock + denvio.cantidad;
                    UPDATE detalle_producto dproducto
                    JOIN detalle_envio denvio		
                        ON denvio.producto_id = dproducto.producto_id
                        AND denvio.envio_id = new.id
                        AND dproducto.centro_id = new.centro_from_id
                    SET dproducto.substock = dproducto.substock - denvio.cantidad;
                END IF;
            END IF;
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
        Schema::unprepared('DROP TRIGGER IF EXISTS envio_BU');
    }
}
