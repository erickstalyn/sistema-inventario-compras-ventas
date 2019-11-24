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
            'unidad' => 'Centímetro',
            'costo' => 8.50
        ));
        DB::table('material')->insert(array(
            'nombre' => 'Cierre Rey grande',
            'unidad' => 'Unidad (1)',
            'costo' => 2.90
        ));
    }
}
