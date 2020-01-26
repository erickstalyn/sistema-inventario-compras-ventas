<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductoBUTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::unprepared("DELIMITER //
        CREATE TRIGGER producto_BU
        BEFORE UPDATE 
        ON producto
        FOR EACH ROW
        BEGIN
            IF(old.precio_menor != new.precio_menor) THEN
                    UPDATE detalle_producto dproducto
                    set dproducto.precio_menor = new.precio_menor
                    WHERE dproducto.producto_id = new.id and dproducto.precio_menor = old.precio_menor;
            END IF;
            IF(old.precio_mayor != new.precio_mayor) THEN
                    UPDATE detalle_producto dproducto
                    set dproducto.precio_mayor = new.precio_mayor
                    WHERE dproducto.producto_id = new.id and dproducto.precio_mayor = old.precio_mayor;
            END IF;
            IF(new.stock <> old.stock) THEN
                UPDATE superproducto
                set superstock = superstock + (new.stock-old.stock)
                where id = new.superproducto_id;
            END IF;
        end
        //
        DELIMITER ;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::unprepared('DROP TRIGGER IF EXISTS producto_BU');
    }
}
