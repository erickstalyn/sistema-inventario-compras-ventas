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
            DECLARE modo TINYINT(2);
            SELECT SUBSTRING(tipo, 1, 1) INTO modo FROM venta WHERE id = new.venta_id;
            IF ( modo = '2' ) THEN
                UPDATE detalle_producto dproducto
                SET dproducto.reservados = dproducto.reservados + new.cantidad
                WHERE dproducto.id = new.detalle_producto_id;
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