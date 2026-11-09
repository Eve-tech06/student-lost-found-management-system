<?php

use App\Http\Controllers\FoundItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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