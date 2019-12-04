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
        //Campera Silmar R3D
        DB::table('producto')->insert(array(//1
            'superproducto_id' => 1,
            'codigo' => '1234567890123',
            'nombre' => 'Campera SILMAR R3D Grande Roja',
            'descripcion' => 'Ideal para viajeros extranjeros',
            'size' => 'Grande',
            'color' => 'Roja',
            'costo_produccion' => 31.50,
            'precio_menor' => 65,
            'precio_mayor' => 55,
            'created_at' => $now
        ));
        DB::table('producto')->insert(array(//2
            'superproducto_id' => 1,
            'codigo' => '1614678965456',
            'nombre' => 'Campera SILMAR R3D Grande Amarilla',
            'descripcion' => 'Ideal para viajeros extranjeros',
            'size' => 'Grande',
            'color' => 'Amarilla',
            'costo_produccion' => 31.50,
            'precio_menor' => 65,
            'precio_mayor' => 55,
            'created_at' => $now
        ));
        DB::table('producto')->insert(array(//3
            'superproducto_id' => 1,
            'codigo' => '8769874534563',
            'nombre' => 'Campera SILMAR R3D Grande Azul',
            'descripcion' => 'Ideal para viajeros extranjeros',
            'size' => 'Grande',
            'color' => 'Azul',
            'costo_produccion' => 31.50,
            'precio_menor' => 65,
            'precio_mayor' => 55,
            'created_at' => $now
        ));
        DB::table('producto')->insert(array(//4
            'superproducto_id' => 1,
            'codigo' => '7978560982345',
            'nombre' => 'Campera SILMAR R3D Mediana Negra',
            'descripcion' => 'Ideal para viajeros extranjeros',
            'size' => 'Mediana',
            'color' => 'Negra',
            'costo_produccion' => 31.50,
            'precio_menor' => 65,
            'precio_mayor' => 55,
            'created_at' => $now
        ));
        DB::table('producto')->insert(array(//5
            'superproducto_id' => 1,
            'codigo' => '7978560982345',
            'nombre' => 'Campera SILMAR R3D Mediana Marron',
            'descripcion' => 'Ideal para viajeros extranjeros',
            'size' => 'Mediana',
            'color' => 'Marron',
            'costo_produccion' => 31.50,
            'precio_menor' => 65,
            'precio_mayor' => 55,
            'created_at' => $now
        ));
        DB::table('producto')->insert(array(//6
            'superproducto_id' => 1,
            'codigo' => '7978560982345',
            'nombre' => 'Campera SILMAR R3D Mediana Celeste',
            'descripcion' => 'Ideal para viajeros extranjeros',
            'size' => 'Mediana',
            'color' => 'Celeste',
            'costo_produccion' => 31.50,
            'precio_menor' => 65,
            'precio_mayor' => 55,
            'created_at' => $now
        ));

        //Mochila Porta XT
        DB::table('producto')->insert(array(//7
            'superproducto_id' => 2,
            'codigo' => '7978560982345',
            'nombre' => 'Mochila Porta XT Pequeña Negra',
            'descripcion' => 'Perfecto para escaloneros',
            'size' => 'Pequeña',
            'color' => 'Negra',
            'costo_produccion' => 25.40,
            'precio_menor' => 40.00,
            'precio_mayor' => 35.60,
            'created_at' => $now
        ));
        DB::table('producto')->insert(array(//8
            'superproducto_id' => 2,
            'codigo' => '7978560982345',
            'nombre' => 'Mochila Porta XT Pequeña Azul',
            'descripcion' => 'Perfecto para escaloneros',
            'size' => 'Pequeña',
            'color' => 'Azul',
            'costo_produccion' => 25.40,
            'precio_menor' => 40.00,
            'precio_mayor' => 35.60,
            'created_at' => $now
        ));
        DB::table('producto')->insert(array(//9
            'superproducto_id' => 2,
            'codigo' => '7978560982345',
            'nombre' => 'Mochila Porta XT Grande Roja',
            'descripcion' => 'Perfecto para escaloneros',
            'size' => 'Grande',
            'color' => 'Roja',
            'costo_produccion' => 25.40,
            'precio_menor' => 40.00,
            'precio_mayor' => 35.60,
            'created_at' => $now
        ));

        //Cartera Azalea YUR
        DB::table('producto')->insert(array(//10
            'superproducto_id' => 3,
            'codigo' => '7978560982345',
            'nombre' => 'Cartera Azalea YUR Grande Roja',
            'descripcion' => 'Mochila con mucho espacio',
            'size' => 'Grande',
            'color' => 'Roja',
            'costo_produccion' => 47.60,
            'precio_menor' => 58.90,
            'precio_mayor' => 50.30,
            'created_at' => $now
        ));

    }
}
