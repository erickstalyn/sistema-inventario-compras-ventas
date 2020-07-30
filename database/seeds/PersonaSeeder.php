<?php

use Illuminate\Database\Seeder;
use App\Persona;

class PersonaSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon\Carbon::now('America/Lima')->toDateTimeString();

        Persona::create([
            'nombres'=>'Jose Guzman',
            'apellidos'=> 'Silva Tello',
            'tipo_documento' => 'dni',
            'numero_documento' => '12345678',
            'tipo' => 'P',
            'created_at' => $now
        ]);
        Persona::create(array(
            'nombres' => 'Jose Anderson',
            'apellidos' => 'Cespedes Diaz',
            'tipo_documento' => 'dni',
            'numero_documento' => '71736657',
            'tipo' => 'P',
            'created_at' => $now
        ));
        Persona::create(array(
            'nombres' => 'Erick Stalyn',
            'apellidos' => 'Pacherrez Puyén',
            'tipo_documento' => 'dni',
            'numero_documento' => '74757559',
            'tipo' => 'P',
            'created_at' => $now
        ));
    }
}
