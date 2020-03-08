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
        DB::table('funcion')->insert(array(//id = 1
            'descripcion' => 'CLIENTE',
        ));
        DB::table('funcion')->insert(array(//id = 2
            'descripcion' => 'PROVEEDOR',
        ));
        DB::table('funcion')->insert(array(//id = 3
            'descripcion' => 'TRABAJADOR',
        ));
    }
}
