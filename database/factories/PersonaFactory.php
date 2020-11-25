<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Persona;
use Faker\Generator as Faker;

$factory->define(Persona::class, function (Faker $faker) {
    return [
        'tipo' => $tipo = $faker->randomElement(['E', 'P']),
        
        'dni' => $tipo==='P' ? $faker->unique()->regexify('/[0-9]{8}/') : NULL,
        'nombres' => $tipo==='P' ? $faker->unique()->firstName() : NULL, 
        'apellidos' => $tipo==='P' ? $faker->unique()->lastName() : NULL,

        'ruc' => $tipo==='E' ? $faker->unique()->regexify('/[0-9]{11}/') : NULL,
        'razon_social' => $tipo==='E' ? $faker->unique()->company() : NULL
    ];
});
