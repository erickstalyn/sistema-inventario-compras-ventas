<?php

use Illuminate\Database\Seeder;
use App\Abasto;
class AbastoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Abasto::create(array(//Un abasto realizado por un ADMIN y enviado a un almacen(centro)
            'proveedor_nombre' => 'Armando Casas Torres',
            'total' => '100',
            'tipo' => 0,
            'proveedor_id' => 4,
            'trabajador_id' => 1,
        ));
    }
}
