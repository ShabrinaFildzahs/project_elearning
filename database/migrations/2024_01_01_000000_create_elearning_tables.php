<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'guru', 'siswa'])->default('siswa');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., X RPL 1
            $table->timestamps();
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Pemrograman Web
            $table->timestamps();
        });

        Schema::create('academic_maps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_map_id')->constrained('academic_maps')->onDelete('cascade');
            $table->string('day'); // Senin, Selasa, etc.
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_map_id')->constrained('academic_maps')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->timestamps();
        });

        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_map_id')->constrained('academic_maps')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->dateTime('deadline');
            $table->enum('type', ['tugas', 'kuis'])->default('tugas');
            $table->timestamps();
        });

        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('file_path');
            $table->enum('status', ['pending', 'graded'])->default('pending');
            $table->integer('grade')->nullable();
            $table->timestamps();
        });

        Schema::create('forums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_map_id')->constrained('academic_maps')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('forum_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_id')->constrained('forums')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_comments');
        Schema::dropIfExists('forums');
        Schema::dropIfExists('submissions');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('academic_maps');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('users');
    }
};
