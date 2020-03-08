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
            'descripcion' => 'ADMINISTRADOR',
        ));
        DB::table('rol')->insert(array(
            'descripcion' => 'PUESTO',
        ));
        DB::table('rol')->insert(array(
            'descripcion' => 'ALMACÉN',
        ));
    }
}
