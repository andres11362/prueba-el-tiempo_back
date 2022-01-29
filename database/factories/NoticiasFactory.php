<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\Noticia;
use App\models\Seccion;
use App\User;
use Faker\Generator as Faker;

$factory->define(Noticia::class, function (Faker $faker) {

    $users = User::pluck('id')->toArray();
    $secciones = Seccion::pluck('id')->toArray();

    return [
        'titulo' => $faker->title(), 
        'contenido' => $faker->text(300), 
        'imagen'  => 'https://i.blogs.es/a19bfc/testing/450_1000.jpg',
        'endpoint' => 'abc123', 
        'id_usuario' => $faker->randomElement($users), 
        'id_seccion' => $faker->randomElement($secciones)
    ];
});
