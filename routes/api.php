<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Admin API routes
Route::middleware(['auth:sanctum', 'admin'])
    ->prefix('admin')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminApiController::class, 'dashboard']);

        // User management
        Route::get('/users', [AdminApiController::class, 'users']);
        Route::patch('/users/{id}/role', [AdminApiController::class, 'updateUserRole']);

        // Item management
        Route::get('/items', [AdminApiController::class, 'items']);
        Route::get('/items/{id}', [AdminApiController::class, 'showItem']);
        Route::patch('/items/{id}/status', [AdminApiController::class, 'updateItemStatus']);

        // Claim management
        Route::get('/claims', [AdminApiController::class, 'claims']);
        Route::get('/claims/{id}', [AdminApiController::class, 'claimDetails']);
        Route::patch('/claims/{id}/approve', [AdminApiController::class, 'approveClaim']);
        Route::patch('/claims/{id}/reject', [AdminApiController::class, 'rejectClaim']);

        // Reports
        Route::get('/reports', [AdminApiController::class, 'reports']);

    });