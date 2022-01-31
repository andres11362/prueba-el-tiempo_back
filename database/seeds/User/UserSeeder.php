<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        User::create([
            'name' => 'Pruebas Laravel',
            'email' => 'correo@pruebas.com',
            'email_verified_at' => now(),
            'password' => '12345678', // password
            'is_super_user' => 1,
            'remember_token' => Str::random(10),
        ]);

        factory(User::class)->times(25)->create();
    }
}
