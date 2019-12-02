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
    public function run()
    {
        $now = Carbon::now('America/Lima')->toDateString();

        DB::table('producto')->insert(array(
            'superproducto_id' => 1,
            'nombre' => 'Campera SILMAR R3D Grande Roja',
            'descripcion' => 'Ideal para viajeros extranjeros',
            'size' => 'Grande',
            'color' => 'Roja',
            'precio_menor' => 65,
            'precio_mayor' => 55,
            'stock' => 0,
            'created_at' => $now
        ));
        DB::table('producto')->insert(array(
            'superproducto_id' => 1,
            'nombre' => 'Campera SILMAR R3D Grande Amarilla',
            'descripcion' => 'Ideal para viajeros extranjeros',
            'size' => 'Grande',
            'color' => 'Amarilla',
            'precio_menor' => 65,
            'precio_mayor' => 55,
            'stock' => 0,
            'created_at' => $now
        ));
        DB::table('producto')->insert(array(
            'superproducto_id' => 1,
            'nombre' => 'Campera SILMAR R3D Grande Azul',
            'descripcion' => 'Ideal para viajeros extranjeros',
            'size' => 'Grande',
            'color' => 'Azul',
            'precio_menor' => 65,
            'precio_mayor' => 55,
            'stock' => 0,
            'created_at' => $now
        ));
        DB::table('producto')->insert(array(
            'superproducto_id' => 1,
            'nombre' => 'Campera SILMAR R3D Mediana Negra',
            'descripcion' => 'Ideal para viajeros extranjeros',
            'size' => 'Mediana',
            'color' => 'Negra',
            'precio_menor' => 70,
            'precio_mayor' => 65,
            'stock' => 0,
            'created_at' => $now
        ));
        
    }
}
