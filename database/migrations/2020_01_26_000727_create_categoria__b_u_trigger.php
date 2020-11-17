<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriaBUTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
        CREATE TRIGGER categoria_BU
        BEFORE UPDATE 
        ON categoria
        FOR EACH ROW
        BEGIN
            IF ( old.nombre <> new.nombre )  THEN
                    UPDATE producto p
                    set p.nombre = CONCAT(new.nombre, ' ', p.size, ' ', p.color)
                    WHERE p.categoria_id = new.id;
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
        DB::unprepared('DROP TRIGGER IF EXISTS categoria_BU');
    }
}
