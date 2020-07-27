<?php

use Illuminate\Database\Seeder;

class FuncionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('funcion')->insert(array(
            'descripcion' => 'CLIENTE',
        ));
        DB::table('funcion')->insert(array(
            'descripcion' => 'PROVEEDOR',
        ));
        DB::table('funcion')->insert(array(
            'descripcion' => 'TRABAJADOR',
        ));
    }
}
