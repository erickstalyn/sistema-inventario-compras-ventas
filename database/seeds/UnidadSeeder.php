<?php

use Illuminate\Database\Seeder;
use App\Unidad;

class UnidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipo_unidad_id = App\TipoUnidad::select('id')->where('nombre', '=', 'longitud')->first()['id'];
        Unidad::create(['tipo_unidad_id' => $tipo_unidad_id, 'nombre' => 'metro']);
        Unidad::create(['tipo_unidad_id' => $tipo_unidad_id, 'nombre' => 'centimetro']);
        Unidad::create(['tipo_unidad_id' => $tipo_unidad_id, 'nombre' => 'milimetro']);

        $tipo_unidad_id = App\TipoUnidad::select('id')->where('nombre', '=', 'peso')->first()['id'];
        Unidad::create(['tipo_unidad_id' => $tipo_unidad_id, 'nombre' => 'kilogramo']);
        Unidad::create(['tipo_unidad_id' => $tipo_unidad_id, 'nombre' => 'gramo']);
        Unidad::create(['tipo_unidad_id' => $tipo_unidad_id, 'nombre' => 'miligramo']);

        $tipo_unidad_id = App\TipoUnidad::select('id')->where('nombre', '=', 'unidad')->first()['id'];
        Unidad::create(['tipo_unidad_id' => $tipo_unidad_id, 'nombre' => 'unidad']);
        Unidad::create(['tipo_unidad_id' => $tipo_unidad_id, 'nombre' => 'decena']);
        Unidad::create(['tipo_unidad_id' => $tipo_unidad_id, 'nombre' => 'docena']);
        Unidad::create(['tipo_unidad_id' => $tipo_unidad_id, 'nombre' => 'centena']);
        Unidad::create(['tipo_unidad_id' => $tipo_unidad_id, 'nombre' => 'millar']);

    }
}
