<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Alimentacion de la base de datos
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(SeccionesSeeder::class);
        $this->call(NoticiasSeeder::class);
    }
}
