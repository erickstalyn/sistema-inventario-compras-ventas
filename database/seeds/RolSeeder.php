<?php

use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rol')->insert(array(
            'nombre'=>'Administrador'
        ));
        DB::table('rol')->insert(array(
            'nombre'=>'Puesto'
        ));
        DB::table('rol')->insert(array(
            'nombre'=>'Almacén'
        ));
    }
}
