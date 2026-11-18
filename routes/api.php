<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\FoundItemApiController;
use App\Http\Controllers\Api\LostItemApiController;
use App\Http\Controllers\Api\SearchApiController;
use App\Http\Controllers\Api\ClaimApiController;

// Authenticated user information
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Found Items API Routes
Route::middleware('auth:sanctum')->prefix('found-items')->group(function () {
    Route::get('/', [FoundItemApiController::class, 'index']);
    Route::post('/', [FoundItemApiController::class, 'store']);
    Route::get('/{item}', [FoundItemApiController::class, 'show']);
    Route::match(['put', 'patch'], '/{item}', [FoundItemApiController::class, 'update']);
});

// Lost Items API Routes
Route::middleware('auth:sanctum')
    ->prefix('lost-items')
    ->name('api.lost-items.')
    ->group(function () {
        Route::get('/', [LostItemApiController::class, 'index'])->name('index');
        Route::post('/', [LostItemApiController::class, 'store'])->name('store');
        Route::get('/{item}', [LostItemApiController::class, 'show'])->name('show');
        Route::put('/{item}', [LostItemApiController::class, 'update'])->name('update');
        Route::delete('/{item}', [LostItemApiController::class, 'destroy'])->name('destroy');
    });

// Search API Routes
Route::get('/items', [SearchApiController::class, 'index']);
Route::get('/items/search', [SearchApiController::class, 'search']);
Route::get('/items/filter', [SearchApiController::class, 'filter']);
Route::get('/items/{id}', [SearchApiController::class, 'show']);

// Claims Management API Routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/items/{itemId}/claim', [ClaimApiController::class, 'store'])
        ->name('api.claims.store');

    Route::get('/my-claims', [ClaimApiController::class, 'index'])
        ->name('api.claims.index');

    Route::get('/my-claims/{claimId}', [ClaimApiController::class, 'show'])
        ->name('api.claims.show');

});

// Administration API Routes
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