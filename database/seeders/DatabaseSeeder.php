<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun Admin
        User::create([
            'name' => 'Administrator SMK',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Akun Guru
        User::create([
            'name' => 'Budi Santoso, S.Kom',
            'email' => 'guru@test.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        // Akun Siswa
        User::create([
            'name' => 'Ahmad Fathanah',
            'email' => 'siswa@test.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);
    }
}
