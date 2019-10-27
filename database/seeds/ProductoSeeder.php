<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        $now = Carbon::now('America/Lima')->toDateTimeString();

        DB::table('producto')->insert(array(
            'categoria_id' => 3,
            'nombre' => 'Mochila roja de 3 bolsillos',
            'precio' => 55,
            'created_at' => $now
        ));
        DB::table('producto')->insert(array(
            'categoria_id' => 1,
            'nombre' => 'Maleta de con 3 espacios y 2 bolsillos ocultos',
            'precio' => 200,
            'created_at' => $now
        ));
        DB::table('producto')->insert(array(
            'categoria_id' => 1,
            'nombre' => 'Maleta Porta roja con stickers de MARVEL',
            'precio' => 75,
            'created_at' => $now
        ));
        DB::table('producto')->insert(array(
            'categoria_id' => 3,
            'nombre' => 'Mochila RipCurl celeste',
            'precio' => 55,
            'created_at' => $now
        ));
        DB::table('producto')->insert(array(
            'categoria_id' => 4,
            'nombre' => 'Cartera belinda con diamantes',
            'precio' => 87,
            'estado' => 0,
            'created_at' => $now
        ));
        DB::table('producto')->insert(array(
            'categoria_id' => 4,
            'nombre' => 'Cartera americana con cierre Rey',
            'precio' => 66,
            'estado' => 0,
            'created_at' => $now
        ));
        DB::table('producto')->insert(array(
            'categoria_id' => 3,
            'nombre' => 'Mochila azalea con brillo naranja',
            'precio' => 500,
            'estado' => 0,
            'created_at' => $now
        ));
    }
}
