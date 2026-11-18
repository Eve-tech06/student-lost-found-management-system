<?php

use Illuminate\Support\Facades\Route;
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

// Search and Browse Items Routes
Route::get('/items', [SearchController::class, 'index'])->name('items.index');
Route::get('/items/search', [SearchController::class, 'search'])->name('items.search');
Route::get('/items/filter', [SearchController::class, 'filter'])->name('items.filter');
Route::get('/items/{id}', [SearchController::class, 'show'])->name('items.show');

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