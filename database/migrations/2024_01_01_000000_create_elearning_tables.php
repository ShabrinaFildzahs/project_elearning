<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Administrator
        Schema::create('administrator', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });

        // 2. Guru
        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('no_hp');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('pendidikan_terakhir');
            $table->timestamps();
        });

        // 3. Kelas
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        // 4. Siswa
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kelas')->constrained('kelas')->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('no_hp');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('nisn')->unique();
            $table->year('tahun_masuk');
            $table->timestamps();
        });

        // 5. Mata Pelajaran
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        // 6. Pemetaan Akademik (Plotting Guru ke Kelas & Mapel)
        Schema::create('pemetaan_akademik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kelas')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('id_mata_pelajaran')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignId('id_guru')->constrained('guru')->onDelete('cascade');
            $table->timestamps();
        });

        // 7. Jadwal Pelajaran
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pemetaan_akademik')->constrained('pemetaan_akademik')->onDelete('cascade');
            $table->string('hari'); // Senin, Selasa, dst.
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();
        });

        // 8. Materi
        Schema::create('materi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pemetaan_akademik')->constrained('pemetaan_akademik')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('path_file');
            $table->timestamps();
        });

        // 9. Tugas
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pemetaan_akademik')->constrained('pemetaan_akademik')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi');
            $table->dateTime('tenggat_waktu');
            $table->enum('tipe', ['tugas', 'kuis'])->default('tugas');
            $table->timestamps();
        });

        // 10. Pengumpulan Tugas
        Schema::create('pengumpulan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tugas')->constrained('tugas')->onDelete('cascade');
            $table->foreignId('id_siswa')->constrained('siswa')->onDelete('cascade');
            $table->string('path_file');
            $table->enum('status', ['tertunda', 'dinilai'])->default('tertunda');
            $table->integer('nilai')->nullable();
            $table->timestamps();
        });

        // 11. Forum Diskusi
        Schema::create('forum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pemetaan_akademik')->constrained('pemetaan_akademik')->onDelete('cascade');
            // Polymorphic relation untuk pencipta forum (Induk: Guru atau Siswa)
            $table->unsignedBigInteger('id_pembuat');
            $table->string('tipe_pembuat'); // App\Models\Guru atau App\Models\Siswa
            $table->string('judul');
            $table->text('konten');
            $table->timestamps();
        });

        // 12. Komentar Forum
        Schema::create('komentar_forum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_forum')->constrained('forum')->onDelete('cascade');
            $table->unsignedBigInteger('id_pembuat');
            $table->string('tipe_pembuat');
            $table->text('konten');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komentar_forum');
        Schema::dropIfExists('forum');
        Schema::dropIfExists('pengumpulan');
        Schema::dropIfExists('tugas');
        Schema::dropIfExists('materi');
        Schema::dropIfExists('jadwal');
        Schema::dropIfExists('pemetaan_akademik');
        Schema::dropIfExists('mata_pelajaran');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('guru');
        Schema::dropIfExists('administrator');
    }
};
