<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Employee::create([
            'nama_karyawan' => 'John Doe',
            'email' => 'john@company.com',
            'no_telepon' => '081234567890',
            'alamat' => 'Jl. Sudirman No. 123, Jakarta',
            'posisi' => 'Manager',
            'departemen' => 'IT',
            'gaji_pokok' => 8000000,
            'tunjangan' => 1000000,
            'status' => 'aktif'
        ]);

        \App\Models\Employee::create([
            'nama_karyawan' => 'Jane Smith',
            'email' => 'jane@company.com',
            'no_telepon' => '081234567891',
            'alamat' => 'Jl. Thamrin No. 456, Jakarta',
            'posisi' => 'Developer',
            'departemen' => 'IT',
            'gaji_pokok' => 6000000,
            'tunjangan' => 500000,
            'status' => 'aktif'
        ]);

        \App\Models\Employee::create([
            'nama_karyawan' => 'Bob Wilson',
            'email' => 'bob@company.com',
            'no_telepon' => '081234567892',
            'alamat' => 'Jl. Gatot Subroto No. 789, Jakarta',
            'posisi' => 'HR Specialist',
            'departemen' => 'HR',
            'gaji_pokok' => 5500000,
            'tunjangan' => 750000,
            'status' => 'aktif'
        ]);

        echo "✓ Sample employees created\n";
    }
}
