<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConceptoAITrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER concepto_AI
            AFTER INSERT ON concepto
            FOR EACH ROW
            BEGIN
                IF ( NEW.type = 0 ) THEN
                    UPDATE caja
                    SET total_egreso = total_egreso + NEW.monto,
                        total_end = total_end - NEW.monto
                    WHERE id = NEW.caja_id;
                END IF;
                IF ( NEW.type = 1 ) THEN
                    UPDATE caja
                    SET total_ingreso = total_ingreso + NEW.monto,
                        total_end = total_end + NEW.monto
                    WHERE id = NEW.caja_id;
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
        DB::unprepared('DROP TRIGGER IF EXISTS concepto_AI');
    }
}
