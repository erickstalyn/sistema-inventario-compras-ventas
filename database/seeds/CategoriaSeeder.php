<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        $now = Carbon::now('America/Lima')->toDateTimeString();

        DB::table('categoria')->insert(array(
            'nombre' => 'Maletas',
            'created_at' => $now
        ));
        DB::table('categoria')->insert(array(
            'nombre' => 'Correas',
            'created_at' => $now
        ));
        DB::table('categoria')->insert(array(
            'nombre' => 'Mochilas',
            'created_at' => $now
        ));
        DB::table('categoria')->insert(array(
            'nombre' => 'Carteras',
            'created_at' => $now
        ));
        DB::table('categoria')->insert(array(
            'nombre' => 'Trajes',
            'created_at' => $now
        ));
        DB::table('categoria')->insert(array(
            'nombre' => 'Maletines',
            'created_at' => $now
        ));
        DB::table('categoria')->insert(array(
            'nombre' => 'Polos',
            'estado' => 0,
            'created_at' => $now
        ));
        DB::table('categoria')->insert(array(
            'nombre' => 'Pantalones',
            'estado' => 0,
            'created_at' => $now
        ));

    }
}
