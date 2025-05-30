<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

// ... comments ...

Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ... dashboard and ui routes ...

    // إدارة المستخدمين
    Route::get('/users', [DashboardController::class, 'users'])->name('users.index');
    Route::get('/users/{id}', [DashboardController::class, 'viewUser'])->name('users.show');
    Route::get('/users/{id}/edit', [DashboardController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [DashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [DashboardController::class, 'deleteUser'])->name('users.destroy');
    Route::patch('/users/{id}/toggle-status', [DashboardController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::post('/users', [DashboardController::class, 'storeUser'])->name('users.store');

    // إدارة الطلبات - Move these inside the admin group
    Route::get('/requests', [DashboardController::class, 'requests'])->name('requests.index');
    Route::post('/requests', [DashboardController::class, 'storeRequest'])->name('requests.store');
    // Add missing request routes
    Route::get('/requests/{request}', [DashboardController::class, 'showRequest'])->name('requests.show'); // Assuming showRequest method exists or will be created
    Route::get('/requests/{request}/edit', [DashboardController::class, 'editRequest'])->name('requests.edit'); // Assuming editRequest method exists or will be created
    Route::put('/requests/{request}', [DashboardController::class, 'updateRequest'])->name('requests.update'); // Assuming updateRequest method exists or will be created
    Route::delete('/requests/{request}', [DashboardController::class, 'destroyRequest'])->name('requests.destroy'); // Assuming destroyRequest method exists or will be created

    // Settings Routes
    Route::get('/settings', [DashboardController::class, 'settings'])->name('admin.settings'); // Renamed from settings.index for consistency
    Route::post('/settings', [DashboardController::class, 'updateSettings'])->name('admin.settings.update'); // General settings update
    Route::post('/settings/advanced', [DashboardController::class, 'updateAdvancedSettings'])->name('admin.settings.updateAdvancedSettings');
    Route::post('/settings/footer-features', [DashboardController::class, 'updateFooterFeatures'])->name('admin.settings.updateFooterFeatures'); // Add this route definition

    // ... system logs and settings routes ...
});
