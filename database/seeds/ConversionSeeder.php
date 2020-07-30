<?php

use Illuminate\Database\Seeder;
use App\Conversion;
use App\Unidad;

class ConversionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // tipo de unidad = longitud
        Conversion::create([
            'unidad_from_id' => Unidad::select('id')->where('nombre', '=', 'milimetro')->first()['id'],
            'unidad_to_id' => Unidad::select('id')->where('nombre', '=', 'centimetro')->first()['id'],
            'factor' => 10
        ]);
        Conversion::create([
            'unidad_from_id' => Unidad::select('id')->where('nombre', '=', 'centimetro')->first()['id'],
            'unidad_to_id' => Unidad::select('id')->where('nombre', '=', 'metro')->first()['id'],
            'factor' => 100
        ]);

        // tipo de unidad = peso
        Conversion::create([
            'unidad_from_id' => Unidad::select('id')->where('nombre', '=', 'miligramo')->first()['id'],
            'unidad_to_id' => Unidad::select('id')->where('nombre', '=', 'gramo')->first()['id'],
            'factor' => 1000
        ]);
        Conversion::create([
            'unidad_from_id' => Unidad::select('id')->where('nombre', '=', 'gramo')->first()['id'],
            'unidad_to_id' => Unidad::select('id')->where('nombre', '=', 'kilogramo')->first()['id'],
            'factor' => 1000
        ]);

        // tipo de unidad = unidad
        Conversion::create([
            'unidad_from_id' => Unidad::select('id')->where('nombre', '=', 'unidad')->first()['id'],
            'unidad_to_id' => Unidad::select('id')->where('nombre', '=', 'decena')->first()['id'],
            'factor' => 10
        ]);
        Conversion::create([
            'unidad_from_id' => Unidad::select('id')->where('nombre', '=', 'unidad')->first()['id'],
            'unidad_to_id' => Unidad::select('id')->where('nombre', '=', 'docena')->first()['id'],
            'factor' => 12
        ]);
        Conversion::create([
            'unidad_from_id' => Unidad::select('id')->where('nombre', '=', 'decena')->first()['id'],
            'unidad_to_id' => Unidad::select('id')->where('nombre', '=', 'centena')->first()['id'],
            'factor' => 10
        ]);
        Conversion::create([
            'unidad_from_id' => Unidad::select('id')->where('nombre', '=', 'centena')->first()['id'],
            'unidad_to_id' => Unidad::select('id')->where('nombre', '=', 'millar')->first()['id'],
            'factor' => 10
        ]);
    }
}
