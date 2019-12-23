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
        $now = Carbon::now('America/Lima')->toDateString();
        DB::table('usuario')->insert(array(
            'persona_id'=>1 ,
            'usuario'=>'silmar',
            'password'=>bcrypt('silmar'),
            'rol' => 'M',
        ));
        DB::table('usuario')->insert(array(
            'centro_id'=>1 ,
            'usuario'=>'puestoa',
            'password'=>bcrypt('puestoa'),
            'rol' => 'P',
        ));
        DB::table('usuario')->insert(array(
            'centro_id'=>2 ,
            'usuario'=>'puestob',
            'password'=>bcrypt('puestob'),
            'rol' => 'P',
        ));
        DB::table('usuario')->insert(array(
            'centro_id'=>3 ,
            'usuario'=>'puestoc',
            'password'=>bcrypt('puestoc'),
            'rol' => 'P',
        ));
        DB::table('usuario')->insert(array(
            'centro_id'=> 4,
            'usuario'=>'almacen1',
            'password'=>bcrypt('almacen1'),
            'rol' => 'A',
        ));
        DB::table('usuario')->insert(array(
            'centro_id'=> 5,
            'usuario'=>'almacen2',
            'password'=>bcrypt('almacen2'),
            'rol' => 'A',
            // 'estado' => false,
        ));
        DB::table('usuario')->insert(array(
            'centro_id'=> 6,
            'usuario'=>'almacen3',
            'password'=>bcrypt('almacen3'),
            'rol' => 'A',
            // 'estado' => false,
        ));
    }
}
