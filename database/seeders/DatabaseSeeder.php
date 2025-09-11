<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Buat user admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password', [
                'rounds' => 12, // Gunakan Bcrypt dengan 12 rounds
            ]),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Buat user test biasa
        User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password', [
                'rounds' => 12, // Gunakan Bcrypt dengan 12 rounds
            ]),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);
    }
}
