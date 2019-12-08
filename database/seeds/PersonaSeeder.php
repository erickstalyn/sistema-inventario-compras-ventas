<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PersonaSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now('America/Lima')->toDateString();

        DB::table('persona')->insert(array(
            'nombres'=>'Jose Guzman',
            'apellidos'=> 'Silva Tello',
            'created_at' => $now,
            'tipo' => 'P'
        ));
        //Proveedores
        DB::table('persona')->insert(array(
            'nombres' => 'Jose Anderson',
            'apellidos' => 'Cespedes Diaz',
            'created_at' => $now,
            'tipo' => 'P'
        ));
        DB::table('persona')->insert(array(
            'nombres' => 'Amelia Sofía',
            'apellidos' => 'Rojas Cordova',
            'dni' => '12345678',
            'created_at' => $now,
            'tipo' => 'P'
        ));
        DB::table('persona')->insert(array(
            'nombres' => 'Erick Stalyn',
            'apellidos' => 'Pacherrez Puyén',
            'dni' => '74757559',
            'created_at' => $now,
            'tipo' => 'P'
        ));
        
        DB::table('persona')->insert(array(
            'razon_social' => 'Gloria SAA',
            'ruc' => '27136842916',
            'created_at' => $now,
            'tipo' => 'P'
        ));
        DB::table('persona')->insert(array(
            'razon_social' => 'EasyCode SAC',
            'ruc' => '85721696932',
            'created_at' => $now,
            'tipo' => 'P'
        ));

    }
}
