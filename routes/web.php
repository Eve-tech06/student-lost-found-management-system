<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\LostItemController;
use App\Http\Controllers\FoundItemController;
use App\Http\Controllers\SearchController;

// Homepage
Route::get('/', function () {
    return view('welcome');
});

// Lost Items Routes
Route::middleware('auth')
    ->prefix('lost-items')
    ->name('lost-items.')
    ->group(function () {
        Route::get('/', [LostItemController::class, 'index'])->name('index');
        Route::get('/create', [LostItemController::class, 'create'])->name('create');
        Route::post('/', [LostItemController::class, 'store'])->name('store');
        Route::get('/{item}', [LostItemController::class, 'show'])->name('show');
        Route::get('/{item}/edit', [LostItemController::class, 'edit'])->name('edit');
        Route::put('/{item}', [LostItemController::class, 'update'])->name('update');
    });

// Found Items Routes
Route::middleware('auth')
    ->prefix('found-items')
    ->name('found-items.')
    ->group(function () {
        Route::get('/', [FoundItemController::class, 'index'])->name('index');
        Route::get('/create', [FoundItemController::class, 'create'])->name('create');
        Route::post('/', [FoundItemController::class, 'store'])->name('store');
        Route::get('/{item}', [FoundItemController::class, 'show'])->name('show');
        Route::get('/{item}/edit', [FoundItemController::class, 'edit'])->name('edit');
        Route::put('/{item}', [FoundItemController::class, 'update'])->name('update');
    });

// Search and Browse Items Routes
Route::get('/items', [SearchController::class, 'index'])->name('items.index');
Route::get('/items/search', [SearchController::class, 'search'])->name('items.search');
Route::get('/items/filter', [SearchController::class, 'filter'])->name('items.filter');
Route::get('/items/{id}', [SearchController::class, 'show'])->name('items.show');

// Claims Management Routes
Route::middleware('auth')->group(function () {
    Route::get('/items/{itemId}/claim', [ClaimController::class, 'create'])
        ->name('claims.create');

    Route::post('/items/{itemId}/claim', [ClaimController::class, 'store'])
        ->name('claims.store');

    Route::get('/my-claims', [ClaimController::class, 'index'])
        ->name('claims.index');

    Route::get('/my-claims/{claimId}', [ClaimController::class, 'show'])
        ->name('claims.show');
});

// Administration Routes
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        // User management
        Route::get('/users', [AdminController::class, 'users'])
            ->name('users');

        Route::get('/users/{id}', [AdminController::class, 'showUser'])
            ->name('users.show');

        Route::patch('/users/{id}/role', [AdminController::class, 'updateUserRole'])
            ->name('users.updateRole');

        // Item management
        Route::get('/items', [AdminController::class, 'items'])
            ->name('items');

        Route::get('/items/{id}', [AdminController::class, 'showItem'])
            ->name('items.show');

        Route::patch('/items/{id}/status', [AdminController::class, 'updateItemStatus'])
            ->name('items.updateStatus');

        // Claim management
        Route::get('/claims', [AdminController::class, 'claims'])
            ->name('claims');

        Route::get('/claims/{id}', [AdminController::class, 'claimDetails'])
            ->name('claims.show');

        Route::patch('/claims/{id}/approve', [AdminController::class, 'approveClaim'])
            ->name('claims.approve');

        Route::patch('/claims/{id}/reject', [AdminController::class, 'rejectClaim'])
            ->name('claims.reject');

        // Reports
        Route::get('/reports', [AdminController::class, 'reports'])
            ->name('reports');
    });