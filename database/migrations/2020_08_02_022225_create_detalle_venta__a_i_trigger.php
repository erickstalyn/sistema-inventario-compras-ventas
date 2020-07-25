<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleVentaAITrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        DB::unprepared("
            CREATE TRIGGER detalle_venta_AI
            AFTER INSERT 
            ON detalle_venta
            FOR EACH ROW
            BEGIN
                DECLARE modo CHAR(1);
                SELECT SUBSTRING(tipo, 2, 1) INTO modo FROM venta WHERE id = NEW.venta_id;
                IF ( modo = '2' ) THEN
                    UPDATE detalle_producto
                    SET reservados = reservados + NEW.cantidad
                    WHERE id = NEW.detalle_producto_id;
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
        DB::unprepared('DROP TRIGGER IF EXISTS detalle_venta_AI');
    }
}
