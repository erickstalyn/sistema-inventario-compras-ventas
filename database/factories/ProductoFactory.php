<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Producto;
use Faker\Generator as Faker;

$factory->define(Producto::class, function (Faker $faker) {
    return [
        // 'codigo' => $faker->unique()->regexify('/[1-9][0-9]{11}/'),
        'codigo' => $faker->unique()->ean13(),
        'precio_mayor' => $precio_menor = $faker->randomFloat(2, 50, 150),
        'precio_menor' => $faker->randomFloat(2, $precio_menor*1.1, $precio_menor*1.3)
        

    ];
});
