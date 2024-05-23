<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Address;
use Faker\Generator as Faker;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'prefecture' => $faker->state,
        'city' => $faker->city,
        'town' => $faker->streetName,
    ];
});