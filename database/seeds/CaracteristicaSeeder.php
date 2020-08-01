<?php

use Illuminate\Database\Seeder;
use App\Caracteristica;

class CaracteristicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipo_caracteristica_id = App\TipoCaracteristica::select('id')->where('nombre', '=', 'Tamaño')->first()['id'];
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'Grande']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'Mediano']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'Pequeño']);

        $tipo_caracteristica_id = App\TipoCaracteristica::select('id')->where('nombre', '=', 'Color')->first()['id'];
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'Rojo']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'Negro']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'Blanco']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'Azul']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'Celeste']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'Verde']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'Naranja']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'Amarillo']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'Violeta']);
    }
}
