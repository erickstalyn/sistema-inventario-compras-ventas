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
            'codigo' => '1234567891234567',
            'total' => 100,
            'total_faltante' => NULL,
            'tipo' => 11,
            'created_at' => $now
        ));
        DB::table('venta')->insert(array(
            'cliente_id' => 1,
            'centro_id' => 1,
            'codigo' => '3641523498521123',
            'total' => 200,
            'total_faltante' => NULL,
            'tipo' => 11,
            'created_at' => $now
        ));
        DB::table('venta')->insert(array(
            'cliente_id' => 5,
            'centro_id' => 1,
            'codigo' => '6714983264824532',
            'total' => 300,
            'total_faltante' => 300,
            'tipo' => 21,
            'created_at' => $now
        ));
        DB::table('venta')->insert(array(
            'cliente_id' => 6,
            'centro_id' => 1,
            'codigo' => '13627985642853624',
            'total' => 400,
            'total_faltante' => 400,
            'tipo' => 21,
            'created_at' => $now
        ));
    }
}
