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
            'nombre' => 'Campera SILMAR R3D Grande Rojo',
            'descripcion' => 'Ideal para viajeros extranjeros',
            'size' => 'Grande',
            'color' => 'Rojo',
            'precio_menor' => 65,
            'precio_mayor' => 55,
            'created_at' => $now
        ));
        DB::table('producto')->insert(array(
            'superproducto_id' => 1,
            'nombre' => 'Campera SILMAR R3D Grande Amarillo',
            'descripcion' => 'Ideal para viajeros extranjeros',
            'size' => 'Grande',
            'color' => 'Amarillo',
            'precio_menor' => 65,
            'precio_mayor' => 55,
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
            'created_at' => $now
        ));
        DB::table('producto')->insert(array(
            'superproducto_id' => 1,
            'nombre' => 'Campera SILMAR R3D Mediano Negro',
            'descripcion' => 'Ideal para viajeros extranjeros',
            'size' => 'Mediano',
            'color' => 'Negro',
            'precio_menor' => 70,
            'precio_mayor' => 65,
            'created_at' => $now
        ));
        
    }
}
