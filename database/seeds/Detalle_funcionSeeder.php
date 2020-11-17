<?php

use Illuminate\Database\Seeder;

class Detalle_funcionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('detalle_funcion')->insert(array(
            'persona_id'=> 1,
            'funcion_id'=> 3,
        ));
        DB::table('detalle_funcion')->insert(array(
            'persona_id'=> 2,
            'funcion_id'=> 2,
        ));
        DB::table('detalle_funcion')->insert(array(
            'persona_id'=> 3,
            'funcion_id'=> 2,
        ));
    }
}
