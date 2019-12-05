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
        // DB::table('producto')->insert(array(//1
        //     'superproducto_id' => 1,
        //     'codigo' => '1234567890123',
        //     'nombre' => 'Campera SILMAR R3D Grande Rojo',
        //     'descripcion' => 'Ideal para viajeros extranjeros',
        //     'size' => 'Grande',
        //     'color' => 'Rojo',
        //     'costo_produccion' => 31.50,
        //     'precio_menor' => 65,
        //     'precio_mayor' => 55,
        //     'created_at' => $now
        // ));
        // DB::table('producto')->insert(array(//2
        //     'superproducto_id' => 1,
        //     'codigo' => '1614678965456',
        //     'nombre' => 'Campera SILMAR R3D Grande Amarillo',
        //     'descripcion' => 'Ideal para viajeros extranjeros',
        //     'size' => 'Grande',
        //     'color' => 'Amarillo',
        //     'costo_produccion' => 31.50,
        //     'precio_menor' => 65,
        //     'precio_mayor' => 55,
        //     'created_at' => $now
        // ));
        // DB::table('producto')->insert(array(//3
        //     'superproducto_id' => 1,
        //     'codigo' => '8769874534563',
        //     'nombre' => 'Campera SILMAR R3D Grande Azul',
        //     'descripcion' => 'Ideal para viajeros extranjeros',
        //     'size' => 'Grande',
        //     'color' => 'Azul',
        //     'costo_produccion' => 31.50,
        //     'precio_menor' => 65,
        //     'precio_mayor' => 55,
        //     'created_at' => $now
        // ));
        // DB::table('producto')->insert(array(//4
        //     'superproducto_id' => 1,
        //     'codigo' => '7978560982345',
        //     'nombre' => 'Campera SILMAR R3D Mediano Negro',
        //     'descripcion' => 'Ideal para viajeros extranjeros',
        //     'size' => 'Mediano',
        //     'color' => 'Negro',
        //     'costo_produccion' => 31.50,
        //     'precio_menor' => 65,
        //     'precio_mayor' => 55,
        //     'created_at' => $now
        // ));
        // DB::table('producto')->insert(array(//5
        //     'superproducto_id' => 1,
        //     'codigo' => '7978560982345',
        //     'nombre' => 'Campera SILMAR R3D Mediano Marron',
        //     'descripcion' => 'Ideal para viajeros extranjeros',
        //     'size' => 'Mediano',
        //     'color' => 'Marron',
        //     'costo_produccion' => 31.50,
        //     'precio_menor' => 65,
        //     'precio_mayor' => 55,
        //     'created_at' => $now
        // ));



        // DB::table('producto')->insert(array(//6
        //     'superproducto_id' => 1,
        //     'codigo' => '7978560982345',
        //     'nombre' => 'Campera SILMAR R3D Mediana Celeste',
        //     'descripcion' => 'Ideal para viajeros extranjeros',
        //     'size' => 'Mediano',
        //     'color' => 'Celeste',
        //     'costo_produccion' => 31.50,
        //     'precio_menor' => 65,
        //     'precio_mayor' => 55,
        //     'created_at' => $now
        // ));

        // //Mochila Porta XT
        // DB::table('producto')->insert(array(//7
        //     'superproducto_id' => 2,
        //     'codigo' => '7978560982345',
        //     'nombre' => 'Mochila Porta XT Pequeña Negro',
        //     'descripcion' => 'Perfecto para escaloneros',
        //     'size' => 'Pequeño',
        //     'color' => 'Negro',
        //     'costo_produccion' => 25.40,
        //     'precio_menor' => 40.00,
        //     'precio_mayor' => 35.60,
        //     'created_at' => $now
        // ));
        // DB::table('producto')->insert(array(//8
        //     'superproducto_id' => 2,
        //     'codigo' => '7978560982345',
        //     'nombre' => 'Mochila Porta XT Pequeña Azul',
        //     'descripcion' => 'Perfecto para escaloneros',
        //     'size' => 'Pequeño',
        //     'color' => 'Azul',
        //     'costo_produccion' => 25.40,
        //     'precio_menor' => 40.00,
        //     'precio_mayor' => 35.60,
        //     'created_at' => $now
        // ));
        // DB::table('producto')->insert(array(//9
        //     'superproducto_id' => 2,
        //     'codigo' => '7978560982345',
        //     'nombre' => 'Mochila Porta XT Grande Roja',
        //     'descripcion' => 'Perfecto para escaloneros',
        //     'size' => 'Grande',
        //     'color' => 'Rojo',
        //     'costo_produccion' => 25.40,
        //     'precio_menor' => 40.00,
        //     'precio_mayor' => 35.60,
        //     'created_at' => $now
        // ));

        // //Cartera Azalea YUR
        // DB::table('producto')->insert(array(//10
        //     'superproducto_id' => 3,
        //     'codigo' => '7978560982345',
        //     'nombre' => 'Cartera Azalea YUR Grande Rojo',
        //     'descripcion' => 'Mochila con mucho espacio',
        //     'size' => 'Grande',
        //     'color' => 'Rojo',
        //     'costo_produccion' => 47.60,
        //     'precio_menor' => 58.90,
        //     'precio_mayor' => 50.30,
        //     'created_at' => $now
        // ));

    }
}
