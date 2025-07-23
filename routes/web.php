<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
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
// Route untuk login dan logout
Route::get('/', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('forgot-password', [AuthController::class, 'forgotPasswordForm'])->name('forgot-password.form');
Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password.post');
Route::get('reset-password', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Route publik tanpa auth untuk form via QR dll
Route::get('/welcome/{id}', [PublicFormController::class, 'welcome'])->name('welcome');
Route::get('/redirect-form/{pcId}', [PublicFormController::class, 'redirectToForm'])->name('public.form.redirect');
Route::get('/form/{form}/fill/{pc}', [PublicFormController::class, 'fill'])->name('form.fill');
Route::post('/form/{form}/submit', [PublicFormController::class, 'submit'])->name('form.submit');
Route::get('/form/success/{pc}', [PublicFormController::class, 'success'])->name('form.success');

//test send reminder-emails
Route::get('/send-reminder-emails', [ReminderController::class, 'sendReminderEmails']);


// Route group dengan middleware auth
Route::middleware(['auth'])->group(function () {

    // Routes khusus admin
    Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::resource('reminder', ReminderController::class);
        Route::get('/get-laboratories/{userId}', [ReminderController::class, 'getLaboratories']);
        Route::resource('user', UserController::class);
        Route::resource('lab', LaboratoryController::class);
        Route::resource('form', FormController::class); //Default Form
    });

    // Routes khusus teknisi
    Route::middleware(['auth','role:teknisi'])->prefix('teknisi')->name('teknisi.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'teknisi'])->name('teknisi.dashboard');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::resource('lab', LaboratoryController::class);
        Route::resource('pc', PCController::class);
        Route::resource('form', FormController::class);
        Route::resource('report', ReportController::class);
        Route::patch('/report/{id}/check', [ReportController::class, 'check'])->name('report.check');
        Route::post('/report/check-all', [ReportController::class, 'checkAll'])->name('report.checkAll');
        Route::get('report-bad', [ReportController::class, 'reportBadForm'])->name('report.reportBadForm');
        Route::post('report-bad/submit', [ReportController::class, 'submitBadReport'])->name('report.submitBadReport');
        Route::get('/history-report', [HistoryController::class, 'historyReportPC'])->name('report.history');
        Route::get('report/export', [HistoryController::class, 'exportpc'])->name('report.export');
        Route::get('/lab-reports', [LaboratoryReportController::class, 'labReportsTeknisi'])->name('labReports');
        Route::patch('/report/{report}/status', [ReportController::class, 'updateStatus'])->name('report.updateStatus'); 
        Route::put('/lab-report/{id}', [LaboratoryReportController::class, 'updateteknisi'])->name('labreport.updateteknisi');
        Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
        Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
        Route::get('/maintenance/export/pdf', [HistoryController::class, 'exportPdf'])->name('maintenance.export.pdf');
        Route::get('/maintenance/history', [HistoryController::class, 'historymaintenancepc'])->name('maintenance.history');
        Route::get('/maintenance/export/pdf', [HistoryController::class, 'exportPdf'])->name('maintenance.export.pdf');
        Route::get('/maintenance/create/{reminder}', [MaintenanceController::class, 'create'])->name('maintenance.create');
    });

    // Routes khusus jurusan
    Route::middleware(['auth','role:jurusan'])->prefix('jurusan')->name('jurusan.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'jurusan'])->name('jurusan.dashboard');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/lab-reports', [LaboratoryReportController::class, 'labReportsJurusan'])->name('labReports');
        Route::get('/maintenance/history', [HistoryController::class, 'historymaintenancepc'])->name('maintenance.history');
        Route::get('/maintenance/export/pdf', [HistoryController::class, 'exportPdf'])->name('maintenance.export.pdf');
        Route::get('/maintenance/export/pdf', [HistoryController::class, 'exportPdf'])->name('maintenance.export.pdf');
        Route::get('/history-report', [HistoryController::class, 'historyReportPC'])->name('report.history');
        Route::get('report/export', [HistoryController::class, 'exportpc'])->name('report.export');
    });

    // Routes khusus kepala_lab
    Route::middleware(['auth','role:kepala_lab'])->prefix('kepala_lab')->name('kepala_lab.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'kepalaLab'])->name('kepala_lab.dashboard');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/lab-reports', [LaboratoryReportController::class, 'labReports'])->name('labReports');
        Route::put('/lab-report/{id}', [LaboratoryReportController::class, 'update'])->name('labreport.update');
        Route::get('/maintenance/history', [HistoryController::class, 'historymaintenancepc'])->name('maintenance.history');
        Route::get('/maintenance/export/pdf', [HistoryController::class, 'exportPdf'])->name('maintenance.export.pdf');
        Route::get('/history-report', [HistoryController::class, 'historyReportPC'])->name('report.history');
        Route::get('report/export', [HistoryController::class, 'exportpc'])->name('report.export');
        Route::resource('reminder', ReminderController::class);
    });
});
