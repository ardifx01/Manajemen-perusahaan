<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'perle@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        echo "✓ Test users created:\n";
        echo "Email: perle@gmail.com | Password: password123\n";
        echo "Email: admin@gmail.com | Password: admin123\n";
    }
}
