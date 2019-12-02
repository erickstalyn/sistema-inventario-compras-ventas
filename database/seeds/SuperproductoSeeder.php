<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class SuperproductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now('America/Lima')->toDateString();
        DB::table('superproducto')->insert(array(
            'nombre' => 'Campera SILMAR R3D',
            'descripcion' => 'Ideal para viajeros extranjeros',
            'created_at' => $now
        ));
        DB::table('superproducto')->insert(array(
            'nombre' => 'Mochila Porta',
            'created_at' => $now
        ));
        DB::table('superproducto')->insert(array(
            'nombre' => 'Cartera Azalea',
            'descripcion' => 'Para damas en los mejores colores y tamaños',
            'created_at' => $now
        ));
        DB::table('superproducto')->insert(array(
            'nombre' => 'Correa ZOMBRA',
            'descripcion' => 'Para todo hombre, joven y niño',
            'created_at' => $now
        ));
        DB::table('superproducto')->insert(array(
            'nombre' => 'Maleta OFICE',
            'created_at' => $now
        ));
        DB::table('superproducto')->insert(array(
            'nombre' => 'MiniCartera OH',
            'created_at' => $now
        ));
        DB::table('superproducto')->insert(array(
            'nombre' => 'Cartuchera Fabercastell',
            'created_at' => $now
        ));
        DB::table('superproducto')->insert(array(
            'nombre' => 'Mochila DELUXE',
            'created_at' => $now
        ));
    }
}
