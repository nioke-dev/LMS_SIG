<?php

use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.landing.index')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // ----------------------------------------------------
    // LEARNER PORTAL (Public & Employee)
    // ----------------------------------------------------
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::middleware(['role:employee'])->group(function () {
        Route::view('employee/dashboard', 'pages.employee.index')->name('employee.dashboard');
    });

    // ----------------------------------------------------
    // MANAGEMENT PORTAL (Back-Office)
    // ----------------------------------------------------
    Route::middleware(['role:learning_administrator'])->group(function () {
        Route::view('learning-admin/dashboard', 'pages.admin.index')->name('learning-admin.dashboard');
        Route::resource('learning-admin/users', UserManagementController::class)->except(['show']);
    });
    Route::middleware(['role:learning_coordinator'])->group(function () {
        Route::get('learning-coordinator/dashboard', [\App\Http\Controllers\TnaSubmissionController::class, 'dashboard'])->name('learning-coordinator.dashboard');

        // TNA Routes
        Route::get('learning-coordinator/daftar-usulan', [\App\Http\Controllers\TnaSubmissionController::class, 'index'])->name('learning-coordinator.daftar-usulan');
        Route::get('learning-coordinator/buat-usulan', [\App\Http\Controllers\TnaSubmissionController::class, 'create'])->name('learning-coordinator.buat-usulan');
        Route::get('learning-coordinator/tna/{id}', [\App\Http\Controllers\TnaSubmissionController::class, 'show'])->name('learning-coordinator.tna.show');
        Route::post('learning-coordinator/tna', [\App\Http\Controllers\TnaSubmissionController::class, 'store'])->name('learning-coordinator.tna.store');
        Route::get('learning-coordinator/tna/{id}/edit', [\App\Http\Controllers\TnaSubmissionController::class, 'edit'])->name('learning-coordinator.tna.edit');
        Route::put('learning-coordinator/tna/{id}', [\App\Http\Controllers\TnaSubmissionController::class, 'update'])->name('learning-coordinator.tna.update');
        Route::delete('learning-coordinator/tna/{id}', [\App\Http\Controllers\TnaSubmissionController::class, 'destroy'])->name('learning-coordinator.tna.destroy');

        Route::get('learning-coordinator/export-tna', [\App\Http\Controllers\TnaExportController::class, 'export'])->name('learning-coordinator.export-tna');
        
        // Profile Routes
        Route::get('learning-coordinator/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('learning-coordinator.profile');
        Route::get('learning-coordinator/profile/change-password', [\App\Http\Controllers\ProfileController::class, 'changePassword'])->name('learning-coordinator.profile.change-password');
    });
    Route::middleware(['role:admin_coordinator'])->group(function () {
        Route::view('admin-coordinator/dashboard', 'pages.admin-coordinator.index')->name('admin-coordinator.dashboard');
    });
    Route::middleware(['role:sme'])->group(function () {
        Route::view('sme/dashboard', 'pages.sme.index')->name('sme.dashboard');
    });
    Route::middleware(['role:helpdesk_admin'])->group(function () {
        Route::view('helpdesk/dashboard', 'pages.helpdesk.index')->name('helpdesk.dashboard');
    });
});

// Google SSO Routes
Route::get('auth/google', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'handleGoogleCallback']);

// Management Login Route (Back-Office Entry)
Route::middleware('guest')->group(function () {
    Route::get('backoffice', [\App\Http\Controllers\Auth\ManagementAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('backoffice', [\App\Http\Controllers\Auth\ManagementAuthController::class, 'login'])->name('admin.login.submit');
});

require __DIR__ . '/settings.php';
