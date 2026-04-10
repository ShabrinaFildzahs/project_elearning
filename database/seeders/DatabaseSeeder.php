<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Subject;
use App\Models\AcademicMap;
use App\Models\Schedule;
use App\Models\Material;
use App\Models\Assignment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== USERS =====
        $admin = User::create([
            'name'     => 'Administrator SMK',
            'email'    => 'admin@smkbinaa.sch.id',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $guru1 = User::create([
            'name'     => 'Budi Santoso, S.Kom',
            'email'    => 'budi@smkbinaa.sch.id',
            'password' => Hash::make('password'),
            'role'     => 'guru',
        ]);

        $guru2 = User::create([
            'name'     => 'Siti Aminah, S.Pd',
            'email'    => 'siti@smkbinaa.sch.id',
            'password' => Hash::make('password'),
            'role'     => 'guru',
        ]);

        $siswa1 = User::create([
            'name'     => 'Ahmad Fathanah',
            'email'    => 'ahmad@smkbinaa.sch.id',
            'password' => Hash::make('password'),
            'role'     => 'siswa',
        ]);

        $siswa2 = User::create([
            'name'     => 'Dewi Kusuma',
            'email'    => 'dewi@smkbinaa.sch.id',
            'password' => Hash::make('password'),
            'role'     => 'siswa',
        ]);

        // Akun test mudah
        User::firstOrCreate(['email' => 'admin@test.com'], [
            'name'     => 'Admin Test',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);
        User::firstOrCreate(['email' => 'guru@test.com'], [
            'name'     => 'Guru Test',
            'password' => Hash::make('password'),
            'role'     => 'guru',
        ]);
        $siswaTest = User::firstOrCreate(['email' => 'siswa@test.com'], [
            'name'     => 'Siswa Test',
            'password' => Hash::make('password'),
            'role'     => 'siswa',
        ]);

        // ===== KELAS =====
        $kelas1 = Kelas::create(['name' => 'X-RPL-1']);
        $kelas2 = Kelas::create(['name' => 'X-RPL-2']);
        $kelas3 = Kelas::create(['name' => 'XI-RPL-1']);

        // ===== SUBJECTS =====
        $web    = Subject::create(['name' => 'Pemrograman Web']);
        $db     = Subject::create(['name' => 'Basis Data']);
        $algo   = Subject::create(['name' => 'Algoritma & Pemrograman']);
        $net    = Subject::create(['name' => 'Jaringan Komputer']);

        // ===== ACADEMIC MAPS (Guru -> Kelas -> Mapel) =====
        $map1 = AcademicMap::create(['class_id' => $kelas1->id, 'subject_id' => $web->id,  'teacher_id' => $guru1->id]);
        $map2 = AcademicMap::create(['class_id' => $kelas1->id, 'subject_id' => $db->id,   'teacher_id' => $guru2->id]);
        $map3 = AcademicMap::create(['class_id' => $kelas2->id, 'subject_id' => $algo->id, 'teacher_id' => $guru1->id]);
        $map4 = AcademicMap::create(['class_id' => $kelas3->id, 'subject_id' => $net->id,  'teacher_id' => $guru2->id]);

        // ===== SCHEDULES =====
        Schedule::create(['academic_map_id' => $map1->id, 'day' => 'Senin',  'start_time' => '07:30', 'end_time' => '09:00']);
        Schedule::create(['academic_map_id' => $map2->id, 'day' => 'Senin',  'start_time' => '09:15', 'end_time' => '10:45']);
        Schedule::create(['academic_map_id' => $map3->id, 'day' => 'Selasa', 'start_time' => '07:30', 'end_time' => '09:00']);
        Schedule::create(['academic_map_id' => $map4->id, 'day' => 'Selasa', 'start_time' => '10:00', 'end_time' => '11:30']);
        Schedule::create(['academic_map_id' => $map1->id, 'day' => 'Kamis',  'start_time' => '07:30', 'end_time' => '09:00']);
        Schedule::create(['academic_map_id' => $map2->id, 'day' => 'Jumat',  'start_time' => '08:00', 'end_time' => '09:30']);

        // ===== MATERIALS =====
        Material::create([
            'academic_map_id' => $map1->id,
            'title'           => 'Pengantar HTML & CSS',
            'description'     => 'Modul dasar pembuatan halaman web menggunakan HTML5 dan CSS3 modern.',
            'file_path'       => 'materials/sample_html_css.pdf',
        ]);
        Material::create([
            'academic_map_id' => $map1->id,
            'title'           => 'JavaScript ES6 Fundamentals',
            'description'     => 'Memahami fitur JavaScript modern: arrow functions, destructuring, promises, dan async/await.',
            'file_path'       => 'materials/sample_js.pdf',
        ]);
        Material::create([
            'academic_map_id' => $map2->id,
            'title'           => 'Konsep Database Relasional',
            'description'     => 'Mempelajari konsep tabel, kunci primer, kunci asing, dan relasi antar tabel.',
            'file_path'       => 'materials/sample_db.pdf',
        ]);
        Material::create([
            'academic_map_id' => $map2->id,
            'title'           => 'Query SQL Lanjutan',
            'description'     => 'Materi JOIN, subquery, stored procedure, dan optimisasi query.',
            'file_path'       => 'materials/sample_sql.pdf',
        ]);

        // ===== ASSIGNMENTS =====
        Assignment::create([
            'academic_map_id' => $map1->id,
            'title'           => 'Buat Halaman Portfolio Pribadi',
            'description'     => 'Buat halaman portfolio menggunakan HTML & CSS yang responsif. Sertakan: header, about, skill, portfolio, dan footer.',
            'deadline'        => now()->addDays(7),
            'type'            => 'tugas',
        ]);
        Assignment::create([
            'academic_map_id' => $map1->id,
            'title'           => 'Quiz Pemrograman Web - Bab 1',
            'description'     => 'Kerjakan soal-soal pilihan ganda dan esai seputar HTML, CSS, dan JavaScript dasar.',
            'deadline'        => now()->addDays(3),
            'type'            => 'kuis',
        ]);
        Assignment::create([
            'academic_map_id' => $map2->id,
            'title'           => 'Perancangan ERD Sistem Perpustakaan',
            'description'     => 'Rancang ERD lengkap untuk sistem manajemen perpustakaan digital. Sertakan minimal 5 entitas.',
            'deadline'        => now()->addDays(5),
            'type'            => 'tugas',
        ]);
    }
}
