<?php

use App\Http\Controllers\LostItemController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware('auth')->prefix('lost-items')->name('lost-items.')->group(function () {
    Route::get('/', [LostItemController::class, 'index'])->name('index');
    Route::get('/create', [LostItemController::class, 'create'])->name('create');
    Route::post('/', [LostItemController::class, 'store'])->name('store');
    Route::get('/{item}', [LostItemController::class, 'show'])->name('show');
    Route::get('/{item}/edit', [LostItemController::class, 'edit'])->name('edit');
    Route::put('/{item}', [LostItemController::class, 'update'])->name('update');
});
