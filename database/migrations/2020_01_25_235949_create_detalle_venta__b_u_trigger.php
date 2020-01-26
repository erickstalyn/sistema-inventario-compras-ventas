<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleVentaBUTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::unprepared("DELIMITER //
        CREATE TRIGGER detalle_venta_BU
        BEFORE UPDATE 
        ON detalle_venta 
        FOR EACH ROW 
        BEGIN
            IF ( new.fallidos IS NOT NULL ) THEN 
                IF ( old.fallidos IS NULL ) THEN 
                    UPDATE detalle_producto	
                    SET fallidos = fallidos + new.fallidos 
                    WHERE id = old.detalle_producto_id; 
                ELSE 
                    IF ( new.fallidos > old.fallidos ) THEN 
                        UPDATE detalle_producto	
                        SET fallidos = fallidos + new.fallidos - old.fallidos 
                        WHERE id = old.detalle_producto_id; 
                    END IF;
                END IF;
            END IF;
            IF ( new.cantidad > old.cantidad ) THEN
                UPDATE detalle_producto
                SET substock = substock - (new.cantidad - old.cantidad)
                WHERE id = old.detalle_producto_id;
            END IF;
        END
        // DELIMITER ;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::unprepared('DROP TRIGGER IF EXISTS detalle_venta_BU');
    }
}
