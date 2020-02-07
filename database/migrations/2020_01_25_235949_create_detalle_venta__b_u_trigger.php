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
            IF ( NEW.cantidad_fallido IS NOT NULL ) THEN 
                IF ( OLD.cantidad_fallido IS NULL ) THEN 
                    UPDATE detalle_producto	
                    SET cantidad_fallido = cantidad_fallido + NEW.cantidad_fallido 
                    WHERE id = OLD.detalle_producto_id; 
                ELSE 
                    IF ( NEW.cantidad_fallido > OLD.cantidad_fallido ) THEN 
                        UPDATE detalle_producto	
                        SET cantidad_fallido = cantidad_fallido + NEW.cantidad_fallido - OLD.cantidad_fallido 
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
