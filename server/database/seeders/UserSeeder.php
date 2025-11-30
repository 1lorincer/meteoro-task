<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@shop.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        //users
        User::create([
            'name' => 'Бека беков',
            'email' => 'beka@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
        User::create([
            'name' => 'Ayka Aykova',
            'email' => 'ayka@email.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
