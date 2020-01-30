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
        DB::unprepared("
        CREATE TRIGGER detalle_venta_BU
        BEFORE UPDATE 
        ON detalle_venta 
        FOR EACH ROW 
        BEGIN
            IF ( NEW.fallidos IS NOT NULL ) THEN 
                IF ( OLD.fallidos IS NULL ) THEN 
                    UPDATE detalle_producto	
                    SET fallidos = fallidos + NEW.fallidos 
                    WHERE id = OLD.detalle_producto_id; 
                ELSE 
                    IF ( NEW.fallidos > OLD.fallidos ) THEN 
                        UPDATE detalle_producto	
                        SET fallidos = fallidos + NEW.fallidos - OLD.fallidos 
                        WHERE id = OLD.detalle_producto_id; 
                    END IF;
                END IF;
            END IF;
            IF ( NEW.cantidad > OLD.cantidad ) THEN
                UPDATE detalle_producto
                SET substock = substock - (NEW.cantidad - OLD.cantidad)
                WHERE id = OLD.detalle_producto_id;
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
        DB::unprepared('DROP TRIGGER IF EXISTS detalle_venta_BU');
    }
}
