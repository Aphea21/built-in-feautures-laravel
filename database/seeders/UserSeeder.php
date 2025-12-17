<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verified users
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('1qwertyu'),
            'role' => 'admin',
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'name' => 'Agent',
            'email' => 'cashier@gmail.com',
            'password' => Hash::make('1qwertyu'),
            'role' => 'agent',
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('1qwertyu'),
            'role' => 'user',
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'name' => 'Driver',
            'email' => 'driver@gmail.com',
            'password' => Hash::make('1qwertyu'),
            'role' => 'agent',
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'name' => 'Johnny Brader',
            'email' => 'johnnybrader08@gmail.com',
            'password' => Hash::make('1qwertyu'),
            'role' => 'agent',
            'email_verified_at' => Carbon::now(),
        ]);

        // Unverified users (4 examples)
        User::create([
            'name' => 'Unverified 1',
            'email' => 'unverified1@gmail.com',
            'password' => Hash::make('1qwertyu'),
            'role' => 'user',
            'email_verified_at' => null,
        ]);

        User::create([
            'name' => 'Unverified 2',
            'email' => 'unverified2@gmail.com',
            'password' => Hash::make('1qwertyu'),
            'role' => 'user',
            'email_verified_at' => null,
        ]);

        User::create([
            'name' => 'Unverified 3',
            'email' => 'unverified3@gmail.com',
            'password' => Hash::make('1qwertyu'),
            'role' => 'agent',
            'email_verified_at' => null,
        ]);

        User::create([
            'name' => 'Unverified 4',
            'email' => 'unverified4@gmail.com',
            'password' => Hash::make('1qwertyu'),
            'role' => 'agent',
            'email_verified_at' => null,
        ]);
    }
}
