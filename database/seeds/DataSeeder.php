<?php

use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('data')->insert(array(
            'tipo' => 'U',
            'subtipo' => 'Longitud',
            'nombre' => 'Metro',
        ));
        DB::table('data')->insert(array(
            'tipo' => 'U',
            'subtipo' => 'Longitud',
            'nombre' => 'Centímetro',
        ));
        DB::table('data')->insert(array(
            'tipo' => 'U',
            'subtipo' => 'Longitud',
            'nombre' => 'Milímetro',
        ));
        DB::table('data')->insert(array(
            'tipo' => 'U',
            'subtipo' => 'Peso',
            'nombre' => 'Kilogramo',
        ));
        DB::table('data')->insert(array(
            'tipo' => 'U',
            'subtipo' => 'Peso',
            'nombre' => 'Gramo',
        ));
        DB::table('data')->insert(array(
            'tipo' => 'U',
            'subtipo' => 'Peso',
            'nombre' => 'Miligramo',
        ));
        DB::table('data')->insert(array(
            'tipo' => 'U',
            'subtipo' => 'Unidad',
            'nombre' => 'Unidad (1)',
        ));
        DB::table('data')->insert(array(
            'tipo' => 'U',
            'subtipo' => 'Unidad',
            'nombre' => 'Decena (10)',
        ));
        DB::table('data')->insert(array(
            'tipo' => 'U',
            'subtipo' => 'Unidad',
            'nombre' => 'Centena (100)',
        ));
        DB::table('data')->insert(array(
            'tipo' => 'U',
            'subtipo' => 'Unidad',
            'nombre' => 'Docena (12)',
        ));
        DB::table('data')->insert(array(
            'tipo' => 'T',
            'nombre' => 'Grande',
        ));
        DB::table('data')->insert(array(
            'tipo' => 'T',
            'nombre' => 'Mediano',
        ));
        DB::table('data')->insert(array(
            'tipo' => 'T',
            'nombre' => 'Pequeño',
        ));
        DB::table('data')->insert(array(
            'tipo' => 'C',
            'nombre' => 'Rojo'
        ));
        DB::table('data')->insert(array(
            'tipo' => 'C',
            'nombre' => 'Verde'
        ));
        DB::table('data')->insert(array(
            'tipo' => 'C',
            'nombre' => 'Negro'
        ));
        DB::table('data')->insert(array(
            'tipo' => 'C',
            'nombre' => 'Blanco'
        ));
        DB::table('data')->insert(array(
            'tipo' => 'C',
            'nombre' => 'Azul'
        ));
        DB::table('data')->insert(array(
            'tipo' => 'C',
            'nombre' => 'Amarillo'
        ));
        DB::table('data')->insert(array(
            'tipo' => 'C',
            'nombre' => 'Violeta'
        ));
    }
}
