<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Guru;
use App\Http\Controllers\Siswa;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware(['auth:admin,guru,siswa'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ===== ADMIN =====
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', Admin\UserController::class);
        Route::resource('classes', Admin\ClassController::class);
        Route::post('classes/store-map', [Admin\ClassController::class, 'storeMap'])->name('classes.storeMap');
        Route::delete('classes/destroy-map/{id}', [Admin\ClassController::class, 'destroyMap'])->name('classes.destroyMap');
        Route::get('schedules/bulk', [Admin\ScheduleController::class, 'bulk'])->name('schedules.bulk.form');
        Route::post('schedules/bulk', [Admin\ScheduleController::class, 'storeBulk'])->name('schedules.bulk');
        Route::resource('schedules', Admin\ScheduleController::class);
    });

    // ===== GURU =====
    Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('schedules', [Guru\ScheduleController::class, 'index'])->name('schedules');
        Route::resource('materials', Guru\MaterialController::class);
        Route::resource('assignments', Guru\AssignmentController::class);
        Route::get('assignments/{assignment}/submissions', [Guru\AssignmentController::class, 'submissions'])->name('assignments.submissions');
        Route::post('submissions/{submission}/grade', [Guru\AssignmentController::class, 'grade'])->name('submissions.grade');
        Route::resource('forums', Guru\ForumController::class);
        Route::post('forums/{forum}/comments', [Guru\ForumController::class, 'storeComment'])->name('forums.comments.store');
        Route::get('profile', [Guru\ProfileController::class, 'index'])->name('profile');
    });

    // ===== SISWA =====
    Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('schedules', [Siswa\ScheduleController::class, 'index'])->name('schedules');
        Route::get('materials', [Siswa\MaterialController::class, 'index'])->name('materials');
        Route::get('materials/{material}/download', [Siswa\MaterialController::class, 'download'])->name('materials.download');
        Route::get('assignments', [Siswa\AssignmentController::class, 'index'])->name('assignments');
        Route::get('assignments/{assignment}', [Siswa\AssignmentController::class, 'show'])->name('assignments.show');
        Route::post('assignments/{assignment}/submit', [Siswa\AssignmentController::class, 'submit'])->name('assignments.submit');
        Route::resource('forums', Siswa\ForumController::class);
        Route::post('forums/{forum}/comments', [Siswa\ForumController::class, 'storeComment'])->name('forums.comments.store');
        Route::get('profile', [Siswa\ProfileController::class, 'index'])->name('profile');
    });
});

require __DIR__.'/auth.php';
