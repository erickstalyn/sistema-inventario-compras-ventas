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
        DB::unprepared("
            CREATE TRIGGER producto_BU
            BEFORE UPDATE 
            ON producto
            FOR EACH ROW
            BEGIN
                IF ( OLD.nombre <> NEW.nombre )  THEN
                    UPDATE subproducto sp
                    SET sp.nombre = CONCAT(NEW.nombre, ' ', sp.caracteristicas)
                    WHERE sp.producto_id = NEW.id;
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
        DB::unprepared('DROP TRIGGER IF EXISTS producto_BU');
    }
}
