<?php

use Illuminate\Database\Seeder;
use App\Detalle_funcion;

class Detalle_funcionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Detalle_funcion::create(array(
            'persona_id' => App\Persona::select('id')->where('nombres', '=', 'Jose Guzman')->first()['id'],
            'funcion_id' => App\Funcion::select('id')->where('descripcion', '=', 'TRABAJADOR')->first()['id']
        ));
    }
}
