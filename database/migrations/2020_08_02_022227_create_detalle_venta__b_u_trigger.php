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
                    IF ( OLD.cantidad - (NEW.cantidad_fallido + NEW.cantidad) > 0 ) THEN
                        UPDATE detalle_producto	
                        SET fallidos = fallidos + NEW.cantidad_fallido,
                            stock = stock + (OLD.cantidad - (NEW.cantidad_fallido + NEW.cantidad))
                        WHERE id = OLD.detalle_producto_id; 
                    ELSE
                        UPDATE detalle_producto	
                        SET fallidos = fallidos + NEW.cantidad_fallido
                        WHERE id = OLD.detalle_producto_id; 
                    END IF;
                ELSE 
                    IF ( OLD.cantidad - NEW.cantidad > 0 ) THEN
                        UPDATE detalle_producto	
                        SET stock = stock + (OLD.cantidad - NEW.cantidad)
                        WHERE id = OLD.detalle_producto_id; 
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
        DB::unprepared('DROP TRIGGER IF EXISTS detalle_venta_BU');
    }
}
