<?php

use Illuminate\Database\Seeder;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;
        DB::table('persona')->insert(array(
            array(
                'nombre'=>'Silmar Gutierrez',
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
        DB::table('persona')->insert(array(
            array(
                'nombre_negocio' => 'Mi puesto A',
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
        DB::table('persona')->insert(array(
            array(
                'nombre_negocio' => 'Mi almacen 1',
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
        DB::table('persona')->insert(array(
            array(
                'nombre_negocio' => 'Mi almacen 2',
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
    }
}
