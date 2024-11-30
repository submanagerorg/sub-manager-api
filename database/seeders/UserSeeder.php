<?php

namespace Database\Seeders;

use App\Models\Timezone;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'uid' => fake()->uuid(),
            'username' => 'Idris',
            'email' => 'idriseun222@gmail.com',
            'password' => Hash::make('password'),
            'timezone_id' => Timezone::first()->id
        ]);
    }
}
