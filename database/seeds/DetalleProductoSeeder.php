<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Centro;
use App\Producto;
use App\Detalle_producto;

class DetalleProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $centros = Centro::all();
        $productos = Producto::all();
        
        foreach ($centros as $centro) {
            foreach ($productos as $producto) {
                Detalle_producto::create([
                    'centro_id' => $centro->id,
                    'producto_id' => $producto->id,
                    'precio_menor' => $producto->precio_menor,
                    'precio_mayor' => $producto->precio_mayor,
                    'tiempo_espera' => $faker->numberBetween(2, 30)
                ]);
            }
        }
    }
}
