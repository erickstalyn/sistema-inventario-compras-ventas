<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleEnvioAITrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
        CREATE TRIGGER detalle_envio_AI
        AFTER INSERT 
        ON detalle_envio
        FOR EACH ROW
        BEGIN
            UPDATE detalle_producto dproducto
            JOIN detalle_envio denvio ON denvio.producto_id = dproducto.producto_id
            JOIN envio  ON envio.id = NEW.envio_id
                AND denvio.id = NEW.id
                AND dproducto.centro_id = envio.centro_from_id
            SET dproducto.substock = dproducto.substock - denvio.cantidad, dproducto.traslado = dproducto.traslado + denvio.cantidad;
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
        Schema::dropIfExists('detalle_envio_a_i_trigger');
    }
}
