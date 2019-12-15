<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class VentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){

        $now = Carbon::now('America/Lima')->toDateTimeString();

        DB::table('venta')->insert(array(
            'cliente_id' => NULL,
            'centro_id' => 1,
            'codigo' => '1234567891234',
            'total' => 100,
            'total_faltante' => NULL,
            'tipo' => 0,
            'created_at' => $now
        ));
        DB::table('venta')->insert(array(
            'cliente_id' => 1,
            'centro_id' => 1,
            'codigo' => '3641523498521',
            'total' => 200,
            'total_faltante' => NULL,
            'tipo' => 0,
            'created_at' => $now
        ));
        DB::table('venta')->insert(array(
            'cliente_id' => 5,
            'centro_id' => 1,
            'codigo' => '6714983264824',
            'total' => 300,
            'total_faltante' => 100,
            'tipo' => 1,
            'created_at' => $now
        ));
        DB::table('venta')->insert(array(
            'cliente_id' => 6,
            'centro_id' => 1,
            'codigo' => '1362798564285',
            'total' => 400,
            'total_faltante' => 200,
            'tipo' => 1,
            'created_at' => $now
        ));
    }
}