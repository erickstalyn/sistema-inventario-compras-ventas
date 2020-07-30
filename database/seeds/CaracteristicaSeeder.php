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
        $tipo_caracteristica_id = App\TipoCaracteristica::select('id')->where('nombre', '=', 'tamaño')->first()['id'];
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'grande']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'mediano']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'pequeño']);

        $tipo_caracteristica_id = App\TipoCaracteristica::select('id')->where('nombre', '=', 'color')->first()['id'];
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'rojo']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'negro']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'blanco']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'azul']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'celeste']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'verde']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'naranja']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'amarillo']);
        Caracteristica::create(['tipo_caracteristica_id' => $tipo_caracteristica_id, 'nombre' => 'violeta']);
    }
}
