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
                DECLARE envio_tipo CHAR(1);
                DECLARE envio_centro_from_id INTEGER;
                SELECT tipo INTO envio_tipo FROM envio WHERE id = NEW.envio_id;
                SELECT centro_from_id INTO envio_centro_from_id FROM envio WHERE id = NEW.envio_id;
                IF ( envio_tipo = '1') THEN
                    UPDATE detalle_producto dproducto
                    SET dproducto.stock = dproducto.stock - NEW.cantidad, dproducto.traslado = dproducto.traslado + NEW.cantidad
                    WHERE dproducto.subproducto_id = NEW.subproducto_id AND dproducto.centro_id = envio_centro_from_id;
                END IF;
                IF( envio_tipo = '2') THEN
                    UPDATE detalle_producto dproducto
                    SET dproducto.fallidos = dproducto.fallidos - NEW.cantidad, dproducto.traslado = dproducto.traslado + NEW.cantidad
                    WHERE dproducto.subproducto_id = NEW.subproducto_id AND dproducto.centro_id = envio_centro_from_id;
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
        Schema::dropIfExists('detalle_envio_a_i_trigger');
    }
}
