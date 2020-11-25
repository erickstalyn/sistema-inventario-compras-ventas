<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Abasto;
use App\Persona;
use App\Centro;
use Faker\Generator as Faker;

$factory->define(Abasto::class, function (Faker $faker) {
    $proveedor = Persona::where('tipo', 'E')->get()->random();
    $centro = Centro::all()->random();
    $admin = Persona::where('nombres', 'like', '%jose%')->first();
    return [
        'administrador_id' => $admin->id,
        'proveedor_id' => $proveedor->id,
        'proveedor_nombre' => $proveedor->razon_social,

        'centro_id' => $centro->id,

        'total' => $faker->randomFloat(2, 500, 3000),
        'tipo' => 0,
        'created_at' => $faker->dateTimeThisYear('2020-11-23 10:00:00', 'America/Lima')
    ];
});
