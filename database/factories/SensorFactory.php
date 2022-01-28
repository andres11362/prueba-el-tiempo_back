<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\Sensor;
use Faker\Generator as Faker;

$factory->define(Sensor::class, function (Faker $faker) {
    
    $tm = App\models\TypeMedition::pluck('id')->toArray();

    return [
        'name' => $faker->userName,
        'description' => $faker->text,
        'ubication' => $faker->address,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'id_type_medition' => $faker->randomElement($tm)
    ];
});
