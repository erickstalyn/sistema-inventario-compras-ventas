<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubproductoBUTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER subproducto_BU
            BEFORE UPDATE 
            ON subproducto
            FOR EACH ROW
            BEGIN
                IF ( OLD.precio_menor != NEW.precio_menor ) THEN
                    UPDATE detalle_producto dproducto
                    SET dproducto.precio_menor = NEW.precio_menor
                    WHERE dproducto.subproducto_id = NEW.id AND dproducto.precio_menor = OLD.precio_menor;
                END IF;
                IF ( OLD.precio_mayor != NEW.precio_mayor ) THEN
                    UPDATE detalle_producto dproducto
                    SET dproducto.precio_mayor = NEW.precio_mayor
                    WHERE dproducto.subproducto_id = NEW.id AND dproducto.precio_mayor = OLD.precio_mayor;
                END IF;
                IF ( NEW.stock <> OLD.stock ) THEN
                    UPDATE producto
                    SET stock = stock + (NEW.stock - OLD.stock)
                    WHERE id = NEW.producto_id;
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
        DB::unprepared('DROP TRIGGER IF EXISTS subproducto_BU');
    }
}
