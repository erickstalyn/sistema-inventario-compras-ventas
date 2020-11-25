<?php

use Illuminate\Database\Seeder;
use App\Persona;
use App\Detalle_funcion;
use App\Funcion;

class Detalle_funcionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $personas = Persona::all();
        $cliente = Funcion::where('descripcion', 'CLIENTE')->first();
        $proveedor = Funcion::where('descripcion', 'PROVEEDOR')->first();

        foreach ($personas as $persona) {
            if ( $persona->dni !== '71736657' ) {
                Detalle_funcion::create([
                    'persona_id' => $persona->id,
                    'funcion_id' => $persona->tipo === 'E' ? $proveedor->id : $cliente->id
                ]);
            }
        }
    }
}
