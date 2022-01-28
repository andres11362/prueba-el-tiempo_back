<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\TypeMedition;
use Faker\Generator as Faker;

$factory->define(TypeMedition::class, function (Faker $faker) {
    return [
        'name' => $faker->userName,
        'val_min' => $faker->randomFloat(2, 0, 10),
        'val_max' => $faker->randomFloat(2, 0, 10),
    ];
});
