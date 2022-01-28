<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\Medition;
use Faker\Generator as Faker;

$factory->define(Medition::class, function (Faker $faker) {

    $sensor = App\models\Sensor::pluck('id')->toArray();

    return [
        'time' => now(),
        'value' => $faker->randomFloat(2, 0, 10),
        'uid' => $faker->uuid,
        'id_sensor' => $faker->randomElement($sensor),
    ];
});
