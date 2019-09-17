<?php

use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;
        DB::table('usuario')->insert(array(
            array(
                'rol_id'=> 1,
                'persona_id'=>1 ,
                'usuario'=>'silmar',
                'clave'=>'silmar',
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
        DB::table('usuario')->insert(array(
            array(
                'rol_id'=> 2,
                'persona_id'=>2 ,
                'usuario'=>'puestoa',
                'clave'=>'puestoa',
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
        DB::table('usuario')->insert(array(
            array(
                'rol_id'=> 3,
                'persona_id'=>3 ,
                'usuario'=>'almacen1',
                'clave'=>'almacen1',
                'created_at' => $now,
                'updated_at' => $now
            )
        ));
    }
}
