<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagoBITrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
        CREATE TRIGGER pago_BI
        BEFORE INSERT 
        ON pago
        FOR EACH ROW
        BEGIN
            DECLARE tf integer;
            IF ( NEW.abasto_id is not NULL ) THEN
                UPDATE abasto
                SET total_faltante = total_faltante - NEW.monto
                WHERE id = NEW.abasto_id;
            ELSE
                UPDATE venta
                SET total_faltante = total_faltante - NEW.monto
                WHERE id = NEW.venta_id;
        
                SELECT total_faltante INTO tf FROM venta WHERE id = NEW.venta_id;
                IF ( tf = 0 ) THEN
                    UPDATE detalle_producto dproducto
                    JOIN detalle_venta dventa
                        ON dventa.detalle_producto_id = dproducto.id
                            AND dventa.venta_id = NEW.venta_id
                    SET dproducto.reservados = dproducto.reservados - dventa.cantidad;
                    UPDATE venta 
                    SET total_faltante = NULL
                    WHERE id = NEW.venta_id;
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
