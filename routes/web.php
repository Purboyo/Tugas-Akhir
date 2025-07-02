<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\PCController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\LaboratoryReportController;
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
        // Route::resource('pc', PCController::class);
        Route::resource('form', FormController::class); //Default Form
        // Route::post('/admin/report/check/{id}', [ReportController::class, 'check']);
        // Route::resource('report', ReportController::class);
    });

    // Routes khusus teknisi
    Route::middleware(['auth','role:teknisi'])->prefix('teknisi')->name('teknisi.')->group(function () {
        Route::get('/dashboard', fn() => view('teknisi.dashboard'))->name('teknisi.dashboard');

        Route::resource('lab', LaboratoryController::class);
        Route::resource('pc', PCController::class);
        Route::resource('form', FormController::class);
        Route::resource('report', ReportController::class);
        Route::patch('/report/{id}/check', [ReportController::class, 'check'])->name('report.check');
        Route::post('/report/check-all', [ReportController::class, 'checkAll'])->name('report.checkAll');
        Route::get('report-bad', [ReportController::class, 'reportBadForm'])->name('report.reportBadForm');
        Route::post('report-bad/submit', [ReportController::class, 'submitBadReport'])->name('report.submitBadReport');
        Route::get('/history-report', [HistoryController::class, 'historyReportPC'])->name('report.history');
        // Route::get('/history-reports', [ReportController::class, 'historyReports'])->name('historyReports');
        // Route::get('/lab-reports', [ReportController::class, 'labReports'])->name('labReports'); //tambahkan di kepala_lab nanti
        Route::patch('/report/{report}/status', [ReportController::class, 'updateStatus'])->name('report.updateStatus'); 
        Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
        Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
        Route::get('/maintenance/history', [HistoryController::class, 'historymaintenancepc'])->name('maintenance.history');
        Route::get('/maintenance/create/{reminder}', [MaintenanceController::class, 'create'])->name('maintenance.create');
    });

    // // Routes yang bisa diakses oleh admin & teknisi sekaligus
    // Route::middleware(['auth','role:admin,teknisi'])->group(function () {
    //     Route::get('/api/report/{id}/answers', [ReportController::class, 'getAnswers'])->name('api.report.answers');
    //     Route::delete('/reports/{id}', [ReportController::class, 'destroy'])->name('reports.destroy');
    // });

    // Routes khusus jurusan
    Route::middleware(['auth','role:jurusan'])->prefix('jurusan')->name('jurusan.')->group(function () {
        Route::get('/dashboard', fn() => view('jurusan.dashboard'))->name('jurusan.dashboard');
    });

    // Routes khusus kepala_lab
    Route::middleware(['auth','role:kepala_lab'])->prefix('kepala_lab')->name('kepala_lab.')->group(function () {
        Route::get('/dashboard', fn() => view('kepala_lab.dashboard'))->name('kepala_lab.dashboard');
        Route::get('/lab-reports', [LaboratoryReportController::class, 'labReports'])->name('labReports'); //tambahkan di kepala_lab nanti
        Route::put('/lab-report/{id}', [LaboratoryReportController::class, 'update'])->name('labreport.update');
        Route::get('/maintenance/history', [HistoryController::class, 'historymaintenancepc'])->name('maintenance.history');
        Route::get('/history-report', [HistoryController::class, 'historyReportPC'])->name('report.history');

    });

});
