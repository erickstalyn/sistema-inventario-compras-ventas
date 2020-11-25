<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Persona;
use App\Detalle_funcion;

class PersonaSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now('America/Lima')->toDateString();

        //Proveedores
        DB::table('persona')->insert(array(
            'nombres' => 'Jose Anderson',
            'apellidos' => 'Cespedes Diaz',
            'dni' => '71736657',
            'tipo' => 'P'
        ));
        DB::table('persona')->insert(array(
            'nombres' => 'Erick Stalyn',
            'apellidos' => 'Pacherrez Puyén',
            'dni' => '74757559',
            'tipo' => 'P'
        ));
        
        factory(Persona::class, 15)->create();
    }
}
