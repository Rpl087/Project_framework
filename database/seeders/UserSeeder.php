<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Ahmad Mahasiswa',
            'email' => 'mahasiswa@lab.test',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Budi Laboran',
            'email' => 'laboran@lab.test',
            'password' => Hash::make('password'),
            'role' => 'laboran',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Dr. Citra Kepala Lab',
            'email' => 'kepalalab@lab.test',
            'password' => Hash::make('password'),
            'role' => 'kepala_lab',
            'email_verified_at' => now(),
        ]);
    }
}
