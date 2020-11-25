<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Envio;
use App\Centro;

$factory->define(Envio::class, function (Faker $faker) {
    return [
        'tipo' => 1,
        'centro_to_id' => Centro::all()->random()['id']
    ];
});
