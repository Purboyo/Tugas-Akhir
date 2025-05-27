<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\PCController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PublicFormController;

Route::get('/', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route publik tanpa auth untuk form via QR dll
Route::get('/form/fill/{pc}', [PublicFormController::class, 'redirectToForm'])->name('form.qr.redirect');
Route::get('/form/{form}/fill', [FormController::class, 'fill'])->name('form.fill');
Route::post('/form/{form}/submit', [FormController::class, 'submit'])->name('form.submit');

// Route group dengan middleware auth
Route::middleware(['auth'])->group(function () {

    // Routes khusus admin
    Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');

        Route::resource('user', UserController::class);
        Route::resource('lab', LaboratoryController::class);
        Route::resource('pc', PCController::class);
        Route::resource('form', FormController::class);
        Route::resource('report', ReportController::class);
    });

    // Routes khusus teknisi
    Route::middleware(['auth','role:teknisi'])->prefix('teknisi')->name('teknisi.')->group(function () {
        Route::get('/dashboard', fn() => view('teknisi.dashboard'))->name('teknisi.dashboard');

        Route::resource('lab', LaboratoryController::class);
        Route::resource('pc', PCController::class);
        Route::resource('form', FormController::class);
        Route::resource('report', ReportController::class);
    });

    // Routes yang bisa diakses oleh admin & teknisi sekaligus
    Route::middleware(['auth','role:admin,teknisi'])->group(function () {
        Route::get('/api/report/{id}/answers', [ReportController::class, 'getAnswers'])->name('api.report.answers');
        Route::delete('/reports/{id}', [ReportController::class, 'destroy'])->name('reports.destroy');
    });

    // Routes khusus jurusan
    Route::middleware(['auth','role:jurusan'])->prefix('jurusan')->name('jurusan.')->group(function () {
        Route::get('/dashboard', fn() => view('jurusan.dashboard'))->name('jurusan.dashboard');
    });

    // Routes khusus kepala_lab
    Route::middleware(['auth','role:kepala_lab'])->prefix('kepala_lab')->name('kepala_lab.')->group(function () {
        Route::get('/dashboard', fn() => view('kepala_lab.dashboard'))->name('kepala_lab.dashboard');
    });

});
