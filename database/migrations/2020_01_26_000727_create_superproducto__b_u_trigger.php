<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuperproductoBUTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
        CREATE TRIGGER superproducto_BU
        BEFORE UPDATE 
        ON superproducto
        FOR EACH ROW
        BEGIN
            IF ( old.nombre <> new.nombre )  THEN
                    UPDATE producto p
                    set p.nombre = CONCAT(new.nombre, ' ', p.size, ' ', p.color)
                    WHERE p.superproducto_id = new.id;
                END IF;
        end
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS superproducto_BU');
    }
}
