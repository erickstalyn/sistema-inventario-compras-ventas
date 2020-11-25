<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Detalle_abasto;
use App\Producto;

$factory->define(Detalle_abasto::class, function (Faker $faker) {
    $producto = Producto::all()->random();

    return [
        'producto_id' => $producto->id,
        'nombre_producto' => $producto->nombre,
        'cantidad' => $cantidad = $faker->numberBetween(50, 150),
        'costo_abasto' => $precio = $faker->randomFloat(2, $producto->precio_mayor*0.7, $producto->precio_mayor*0.9),
        'subtotal' => $cantidad * $precio
    ];
});
