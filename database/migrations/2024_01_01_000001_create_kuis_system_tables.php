<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kuis Header
        Schema::create('kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pemetaan_akademik')->constrained('pemetaan_akademik')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->dateTime('tenggat_waktu');
            $table->integer('durasi_menit')->default(60);
            $table->timestamps();
        });

        // 2. Pertanyaan Kuis (Pilihan Ganda)
        Schema::create('pertanyaan_kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kuis')->constrained('kuis')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->text('opsi_a');
            $table->text('opsi_b');
            $table->text('opsi_c');
            $table->text('opsi_d');
            $table->text('opsi_e');
            $table->char('jawaban_benar', 1); // A, B, C, D, or E
            $table->integer('poin')->default(10);
            $table->timestamps();
        });

        // 3. Hasil Kuis (Nilai Siswa)
        Schema::create('hasil_kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kuis')->constrained('kuis')->onDelete('cascade');
            $table->foreignId('id_siswa')->constrained('siswa')->onDelete('cascade');
            $table->integer('skor');
            $table->integer('jumlah_benar');
            $table->integer('jumlah_salah');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_kuis');
        Schema::dropIfExists('pertanyaan_kuis');
        Schema::dropIfExists('kuis');
    }
};
