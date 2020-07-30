<?php

use Illuminate\Database\Seeder;
use App\Rol;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rol::create(array(
            'descripcion' => 'ADMINISTRADOR',
        ));
        Rol::create(array(
            'descripcion' => 'PUESTO',
        ));
        Rol::create(array(
            'descripcion' => 'ALMACÉN',
        ));
    }
}
