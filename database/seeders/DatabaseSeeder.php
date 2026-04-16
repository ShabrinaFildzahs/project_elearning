<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Akun Admin Utama
        Admin::create([
            'username' => 'admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);

        // 2. Buat Contoh Data Dasar (Opsional tetapi membantu untuk tes)
        $kelasX = Kelas::create(['nama' => 'X RPL 1']);
        $kelasXI = Kelas::create(['nama' => 'XI RPL 1']);
        
        $mapel1 = MataPelajaran::create(['nama' => 'Pemrograman Web']);
        $mapel2 = MataPelajaran::create(['nama' => 'Basis Data']);

        // Log info untuk user
        $this->command->info('Seed Berhasil!');
        $this->command->info('Admin Username: admin');
        $this->command->info('Admin Password: password');
    }
}
