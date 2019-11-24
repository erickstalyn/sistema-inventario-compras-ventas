<?php

use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('material')->insert(array(
            'nombre' => 'Tela R3',
            'unidad' => 'cm',
            'costo' => 8.50
        ));
        DB::table('material')->insert(array(
            'nombre' => 'Cierre Rey grande',
            'unidad' => 'unidad',
            'costo' => 2.90
        ));
    }
}
