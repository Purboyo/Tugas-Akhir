<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\PCController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PublicFormController;
use App\Http\Controllers\ReminderController;
use App\Models\Maintenance;

Route::get('/', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route publik tanpa auth untuk form via QR dll
Route::get('/welcome/{id}', [PublicFormController::class, 'welcome'])->name('welcome');
Route::get('/redirect-form/{pcId}', [PublicFormController::class, 'redirectToForm'])->name('public.form.redirect');
Route::get('/form/{form}/fill/{pc}', [PublicFormController::class, 'fill'])->name('form.fill');
Route::post('/form/{form}/submit', [PublicFormController::class, 'submit'])->name('form.submit');
Route::get('/form/success/{pc}', [PublicFormController::class, 'success'])->name('form.success');

// Route group dengan middleware auth
Route::middleware(['auth'])->group(function () {

    // Routes khusus admin
    Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
        Route::resource('reminder', ReminderController::class);
        Route::get('/get-laboratories/{userId}', [ReminderController::class, 'getLaboratories']);
        Route::resource('user', UserController::class);
        Route::resource('lab', LaboratoryController::class);
        Route::resource('pc', PCController::class);
        Route::resource('form', FormController::class);
        Route::post('/admin/report/check/{id}', [ReportController::class, 'check']);
        Route::resource('report', ReportController::class);
    });

    // Routes khusus teknisi
    Route::middleware(['auth','role:teknisi'])->prefix('teknisi')->name('teknisi.')->group(function () {
        Route::get('/dashboard', fn() => view('teknisi.dashboard'))->name('teknisi.dashboard');

        Route::resource('lab', LaboratoryController::class);
        Route::resource('pc', PCController::class);
        Route::resource('form', FormController::class);
        Route::resource('report', ReportController::class);
        Route::resource('maintenance', MaintenanceController::class);
        Route::post('/teknisi/report/check/{id}', [ReportController::class, 'check']);
        Route::post('/teknisi/report/done', [ReportController::class, 'done']);
        Route::post('/report/status/{id}', [ReportController::class, 'updateStatus'])->name('report.updateStatus');

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
