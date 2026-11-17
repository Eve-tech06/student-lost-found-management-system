<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('users.show');
    Route::patch('/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('users.updateRole');

    // Item management
    Route::get('/items', [AdminController::class, 'items'])->name('items');
    Route::get('/items/{id}', [AdminController::class, 'showItem'])->name('items.show');
    Route::patch('/items/{id}/status', [AdminController::class, 'updateItemStatus'])->name('items.updateStatus');

    // Claim management
    Route::get('/claims', [AdminController::class, 'claims'])->name('claims');
    Route::get('/claims/{id}', [AdminController::class, 'claimDetails'])->name('claims.show');
    Route::patch('/claims/{id}/approve', [AdminController::class, 'approveClaim'])->name('claims.approve');
    Route::patch('/claims/{id}/reject', [AdminController::class, 'rejectClaim'])->name('claims.reject');

    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');

});