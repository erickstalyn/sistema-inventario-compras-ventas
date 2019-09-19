<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PersonaSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now('America/Lima')->toDateTimeString();

        DB::table('persona')->insert(array(
            'nombre'=>'Silmar Gutierrez',
            'created_at' => $now,
            'tipo' => 'N'
        ));
        DB::table('persona')->insert(array(
            'nombre' => 'Mi puesto A',
            'created_at' => $now,
            'tipo' => 'P'
        ));
        DB::table('persona')->insert(array(
            'nombre' => 'Mi almacen 1',
            'created_at' => $now,
            'tipo' => 'A'
        ));
        DB::table('persona')->insert(array(
            'nombre' => 'Mi almacen 2',
            'created_at' => $now,
            'tipo' => 'A'
        ));
    }
}
