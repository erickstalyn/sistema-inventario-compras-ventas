<?php

use Illuminate\Database\Seeder;
use App\Empresa;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Empresa::create(array(
            'ruc' => '10436183751',
            'razon_social' => 'SILVA TELLO JOSE GUZMAN'
        ));
        Empresa::create(array(
            'ruc' => '20601273307',
            'razon_social' => 'DISTRIBUIDORA & COMERCIAL SILMAR II SAC'
        ));
    }
}
