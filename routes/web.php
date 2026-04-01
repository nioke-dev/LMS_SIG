<?php

use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Public user dashboard (default)
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Learning Administrator dashboard
    Route::middleware(['role:learning_administrator'])->group(function () {
        Route::view('learning-admin/dashboard', 'dashboards.learning-admin')->name('learning-admin.dashboard');
        Route::get('learning-admin/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('learning-admin/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('learning-admin/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('learning-admin/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('learning-admin/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('learning-admin/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });

    // Learning Coordinator dashboard
    Route::middleware(['role:learning_coordinator'])->group(function () {
        Route::view('learning-coordinator/dashboard', 'dashboards.learning-coordinator')->name('learning-coordinator.dashboard');
    });

    // Admin Coordinator dashboard
    Route::middleware(['role:admin_coordinator'])->group(function () {
        Route::view('admin-coordinator/dashboard', 'dashboards.admin-coordinator')->name('admin-coordinator.dashboard');
    });

    // SME dashboard
    Route::middleware(['role:sme'])->group(function () {
        Route::view('sme/dashboard', 'dashboards.sme')->name('sme.dashboard');
    });

    // Employee dashboard
    Route::middleware(['role:employee'])->group(function () {
        Route::view('employee/dashboard', 'dashboards.employee')->name('employee.dashboard');
    });

    // Helpdesk Admin dashboard
    Route::middleware(['role:helpdesk_admin'])->group(function () {
        Route::view('helpdesk/dashboard', 'dashboards.helpdesk')->name('helpdesk.dashboard');
    });
});

require __DIR__.'/settings.php';
