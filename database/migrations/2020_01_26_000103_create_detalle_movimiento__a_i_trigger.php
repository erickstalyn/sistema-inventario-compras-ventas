<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleMovimientoAITrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER detalle_movimiento_AI
            AFTER INSERT 
            ON detalle_movimiento
            FOR EACH ROW
            BEGIN
                UPDATE detalle_producto
                SET substock = substock + (NEW.ingreso - NEW.egreso)
                WHERE id = NEW.detalle_producto_id;
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
