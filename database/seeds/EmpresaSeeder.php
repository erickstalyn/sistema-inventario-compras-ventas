<?php

use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('empresa')->insert(array(
            'id' => 1,
            'ruc' => '10436183751',
            'razon_social' => 'SILVA TELLO JOSE GUZMAN'
        ), array(
            'id' => 2,
            'ruc' => '20601273307',
            'razon_social' => 'DISTRIBUIDORA & COMERCIAL SILMAR II SAC'
        ));
    }
}
