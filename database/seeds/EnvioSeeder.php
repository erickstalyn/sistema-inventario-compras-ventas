<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EnvioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now('America/Lima')->toDateString();
        DB::table('envio')->insert(array(
            'estado' => '0',//Enviado
            'centro_from_id' => 4,
            'centro_to_id' => 5,
            'created_at' => $now
        ));
        DB::table('envio')->insert(array(
            'estado' => '1',//Aceptado
            'centro_from_id' => 4,
            'centro_to_id' => 5,
            'centro_to_id' => 5,
            'created_at' => $now,
            'updated_at' => $now
        ));
        DB::table('envio')->insert(array(
            'estado' => '2',//Rechazado
            'centro_from_id' => 4,
            'centro_to_id' => 5,
            'centro_to_id' => 5,
            'created_at' => $now,
            'updated_at' => $now
        ));
        // DB::table('envio')->insert(array(
        //     'estado' => '1',//Aceptado
        //     'centro_to_id' => 4,
        //     'abasto_id' => 2,
        //     'created_at' => $now,
        //     'updated_at' => $now
        // ));
        // DB::table('envio')->insert(array(
        //     'estado' => '2', //Rechazado
        //     'centro_to_id' => 4,
        //     'abasto_id' => 2,
        //     'created_at' => $now,
        //     'updated_at' => $now
        // ));
    }
}
