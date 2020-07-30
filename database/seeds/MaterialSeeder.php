<?php

use Illuminate\Database\Seeder;
use App\Material;
class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Material::create(array(
            'nombre' => 'Hilo tipo 1',
            'subtipo' => 'longitud',
            'unidad' => 'centimetro',
            'costo' => 5.20
        ));
        Material::create(array(
            'nombre' => 'Gaza negra',
            'subtipo' => 'longitud',
            'unidad' => 'metro',
            'costo' => 2
        ));
        Material::create(array(
            'nombre' => 'Abrazadera',
            'subtipo' => 'longitud',
            'unidad' => 'centimetro',
            'costo' => 4.50
        ));
        Material::create(array(
            'nombre' => 'boton de alta calidad',
            'subtipo' => 'unidad',
            'unidad' => 'decena',
            'costo' => 2
        ));
        Material::create(array(
            'nombre' => 'Bollo de lana',
            'subtipo' => 'peso',
            'unidad' => 'gramo',
            'costo' => 8.60
        ));
    }
}
