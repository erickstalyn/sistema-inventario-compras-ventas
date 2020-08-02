<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubproductoAITrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER subproducto_AI
            AFTER INSERT
            ON subproducto
            FOR EACH ROW
            BEGIN
                INSERT INTO detalle_producto(precio_menor, precio_mayor, centro_id, subproducto_id)
                VALUES  (NEW.precio_menor, NEW.precio_mayor, 1, NEW.id);
                INSERT INTO detalle_producto(precio_menor, precio_mayor, centro_id, subproducto_id)
                VALUES  (NEW.precio_menor, NEW.precio_mayor, 2, NEW.id);
                INSERT INTO detalle_producto(precio_menor, precio_mayor, centro_id, subproducto_id)
                VALUES  (NEW.precio_menor, NEW.precio_mayor, 3, NEW.id);
                INSERT INTO detalle_producto(precio_menor, precio_mayor, centro_id, subproducto_id)
                VALUES  (NEW.precio_menor, NEW.precio_mayor, 4, NEW.id);
                INSERT INTO detalle_producto(precio_menor, precio_mayor, centro_id, subproducto_id)
                VALUES  (NEW.precio_menor, NEW.precio_mayor, 5, NEW.id);
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
        DB::unprepared('DROP TRIGGER IF EXISTS subproducto_AI');
    }
}
