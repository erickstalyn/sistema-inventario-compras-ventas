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
        $personas = Persona::where('dni', '<>', '71736657')->get();
        $cliente = Funcion::where('descripcion', 'like', '%cliente%')->first();
        $proveedor = Funcion::where('descripcion', 'like', '%proveedor%')->first();

        foreach ($personas as $persona) {
            if ( $persona->tipo === 'E' ) {
                Detalle_funcion::create([
                    'persona_id' => $persona->id,
                    'funcion_id' => $proveedor->id
                ]);
            } else {
                Detalle_funcion::create([
                    'persona_id' => $persona->id,
                    'funcion_id' => $cliente->id
                ]);
            }
        }
    }
}
