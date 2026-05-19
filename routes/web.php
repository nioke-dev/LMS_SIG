<?php

use App\Http\Controllers\TnaSubmissionController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.landing.index')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // ----------------------------------------------------
    // LEARNER PORTAL (Public & Employee)
    // ----------------------------------------------------
    Route::middleware(['role:employee,public'])->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');
    });

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
        Route::get('admin-coordinator/merging-hub', [TnaSubmissionController::class, 'mergingHub'])->name('admin-coordinator.merging-hub');
        Route::get('admin-coordinator/blueprint-directory', [TnaSubmissionController::class, 'blueprintDirectory'])->name('admin-coordinator.blueprint-directory');
        Route::get('admin-coordinator/api/hierarchy', [TnaSubmissionController::class, 'getHierarchy'])->name('admin-coordinator.api.hierarchy');
        Route::post('admin-coordinator/blueprint/initiate', [TnaSubmissionController::class, 'initiateBlueprint'])->name('admin-coordinator.blueprint.initiate');
        Route::post('admin-coordinator/blueprint/store', [TnaSubmissionController::class, 'storeBlueprint'])->name('admin-coordinator.blueprint.store');
        Route::post('admin-coordinator/blueprint/{id}/remind', [TnaSubmissionController::class, 'remindSme'])->name('admin-coordinator.blueprint.remind');
        Route::get('admin-coordinator/blueprint/{id}/edit', [TnaSubmissionController::class, 'editBlueprint'])->name('admin-coordinator.blueprint.edit');
        Route::delete('admin-coordinator/blueprint/{id}', [TnaSubmissionController::class, 'destroyBlueprint'])->name('admin-coordinator.blueprint.destroy');
        Route::get('admin-coordinator/category-approval', [TnaSubmissionController::class, 'categoryApproval'])->name('admin-coordinator.category-approval');
        Route::post('admin-coordinator/category-approval/{id}/approve', [TnaSubmissionController::class, 'approveCategory'])->name('admin-coordinator.category.approve');
        Route::post('admin-coordinator/category-approval/{id}/reject', [TnaSubmissionController::class, 'rejectCategory'])->name('admin-coordinator.category.reject');
        Route::post('admin-coordinator/category-approval/store', [TnaSubmissionController::class, 'storeCategory'])->name('admin-coordinator.category.store');
        Route::post('admin-coordinator/category-approval/{id}/toggle', [TnaSubmissionController::class, 'toggleCategoryStatus'])->name('admin-coordinator.category.toggle');
        Route::get('admin-coordinator/sme-directory', [TnaSubmissionController::class, 'smeDirectory'])->name('admin-coordinator.sme-directory');
    });
    Route::middleware(['role:sme,admin_coordinator,learning_coordinator,learning_administrator'])->group(function () {
        Route::get('sme/dashboard', [\App\Http\Controllers\SmeController::class, 'dashboard'])->name('sme.dashboard');
        Route::get('sme/blueprint', [\App\Http\Controllers\SmeController::class, 'index'])->name('sme.blueprint.index');
        Route::get('sme/blueprint/export', [\App\Http\Controllers\SmeController::class, 'exportExcel'])->name('sme.blueprint.export');
        Route::get('sme/revisions', [\App\Http\Controllers\SmeController::class, 'revisionList'])->name('sme.revision.index');
        Route::get('sme/masterclasses', [\App\Http\Controllers\SmeController::class, 'masterclassIndex'])->name('sme.masterclass.index');
        Route::get('sme/validated', [\App\Http\Controllers\SmeController::class, 'validatedIndex'])->name('sme.validated.index');
        Route::get('sme/blueprint/{id}', [\App\Http\Controllers\SmeController::class, 'showBlueprint'])->name('sme.blueprint.show');
        Route::post('sme/blueprint/{id}/submit', [\App\Http\Controllers\SmeController::class, 'submitMaterial'])->name('sme.blueprint.submit');
        Route::get('sme/masterclass-curriculum/{id}', [\App\Http\Controllers\SmeController::class, 'masterclassCurriculum'])->name('sme.masterclass.curriculum');
        Route::post('sme/masterclass-curriculum/{id}/draft', [\App\Http\Controllers\SmeController::class, 'saveCurriculumDraft'])->name('sme.masterclass.draft');
        Route::post('sme/masterclass-curriculum/{id}/quiz-draft', [\App\Http\Controllers\SmeController::class, 'saveQuizDraft'])->name('sme.quiz.draft.save');
        Route::get('sme/masterclass-curriculum/{id}/quiz-draft', [\App\Http\Controllers\SmeController::class, 'getQuizDraft'])->name('sme.quiz.draft.get');
        Route::post('sme/masterclass-curriculum/{id}/video-draft', [\App\Http\Controllers\SmeController::class, 'saveVideoDraft'])->name('sme.video.draft.save');
        Route::get('sme/masterclass-curriculum/{id}/video-draft', [\App\Http\Controllers\SmeController::class, 'getVideoDraft'])->name('sme.video.draft.get');
        Route::post('sme/masterclass-curriculum/{id}/submit', [\App\Http\Controllers\SmeController::class, 'submitFinalCurriculum'])->name('sme.masterclass.submit');
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

// Role Selection for Multi-Role Users
Route::middleware(['auth'])->group(function () {
    Route::get('auth/select-role', [\App\Http\Controllers\Auth\ManagementAuthController::class, 'showRoleSelection'])->name('auth.select-role');
    Route::post('auth/select-role', [\App\Http\Controllers\Auth\ManagementAuthController::class, 'selectRole'])->name('auth.select-role.submit');
});

require __DIR__ . '/settings.php';
