<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExamController;
use Illuminate\Support\Facades\Route;


Route::get('/', fn() => view('login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return auth()->user()->role === 'admin' ? redirect('/admin') : redirect('/siswa');
    });

    // ADMIN
    Route::middleware('role:admin')
        ->prefix('admin')
        ->group(function () {
            Route::get('/', [AdminController::class, 'index']);
            Route::get('/soal', [AdminController::class, 'soal']);
            Route::post('/soal', [AdminController::class, 'storeSoal']);
            Route::get('/soal/{id}/edit', [AdminController::class, 'editSoal']);
            Route::put('/soal/{id}', [AdminController::class, 'updateSoal']);
            Route::delete('/soal/{id}', [AdminController::class, 'deleteSoal']);
            Route::get('/exam', [AdminController::class, 'exam']);
            Route::post('/exam', [AdminController::class, 'storeExam']);
            Route::get('/exam/{id}/edit', [AdminController::class, 'editExam']);
            Route::put('/exam/{id}', [AdminController::class, 'updateExam']);
            Route::delete('/exam/{id}', [AdminController::class, 'deleteExam']);
            Route::get('/results', [AdminController::class, 'results']);
            Route::get('/users', [AdminController::class, 'users']);
            Route::post('/users', [AdminController::class, 'storeUser']);
            Route::get('/users/{id}/edit', [AdminController::class, 'editUser']);
            Route::put('/users/{id}', [AdminController::class, 'updateUser']);
            Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
            Route::get('/analytics', [AdminController::class, 'analytics']);
            Route::get('/schedule', [AdminController::class, 'schedule']);
        });

    // SISWA
    Route::middleware('role:student')
        ->prefix('siswa')
        ->group(function () {
            Route::get('/', [ExamController::class, 'index']);
            Route::get('/ujian/{id}', [ExamController::class, 'start']);
            Route::post('/ujian/{id}', [ExamController::class, 'submit']);
            Route::post('/ujian/{id}/save-progress', [ExamController::class, 'saveProgress']);
            Route::get('/result/{examId}', [ExamController::class, 'result']);
        });
});
