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
                'nombre'=>'Silmar',
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
        DB::table('persona')->insert(array(
            array(
                'nombre_negocio' => 'Puesto A',
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
        DB::table('persona')->insert(array(
            array(
                'nombre_negocio' => 'Almacen 1',
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
    }
}
