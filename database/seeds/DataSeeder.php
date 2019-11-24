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
            'subtipo' => 'L',
            'nombre' => 'Metro',
        ));
        DB::table('data')->insert(array(
            'tipo' => 'U',
            'subtipo' => 'L',
            'nombre' => 'Centímetro',
        ));
        DB::table('data')->insert(array(
            'tipo' => 'U',
            'subtipo' => 'L',
            'nombre' => 'Milímetro',
        ));
        DB::table('data')->insert(array(
            'tipo' => 'U',
            'subtipo' => 'P',
            'nombre' => 'Kilogramo',
        ));
        DB::table('data')->insert(array(
            'tipo' => 'U',
            'subtipo' => 'P',
            'nombre' => 'Gramo',
        ));
        DB::table('data')->insert(array(
            'tipo' => 'U',
            'subtipo' => 'P',
            'nombre' => 'Miligramo',
        ));
    }
}
