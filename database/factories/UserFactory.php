<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'phone' => $faker->phoneNumber,
        'mobile' => $faker->phoneNumber,
        'password' => '$2y$12$5B4Pntph7MN7Xi6s2ZjakOIEr0qMjDz3xBVjEm/uHYqLf5QYw94zC', // password
        'remember_token' => Str::random(10),
    ];
});
