<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usuario')->insert(array(
            'rol_id'=> 1,
            'persona_id'=>1 ,
            'usuario'=>'silmar',
            'password'=>bcrypt('silmar')
        ));
        DB::table('usuario')->insert(array(
            'rol_id'=> 2,
            'persona_id'=>2 ,
            'usuario'=>'puestoa',
            'password'=>bcrypt('puestoa')
        ));
        DB::table('usuario')->insert(array(
            'rol_id'=> 3,
            'persona_id'=>3 ,
            'usuario'=>'almacen1',
            'password'=>bcrypt('almacen1')
        ));
        DB::table('usuario')->insert(array(
            'rol_id'=> 3,
            'persona_id'=> 4,
            'usuario'=>'almacen2',
            'password'=>bcrypt('almacen2'),
            'estado' => false
        ));
    }
}
