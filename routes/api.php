<?php

use App\Http\Controllers\Api\LostItemApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('lost-items')->name('api.lost-items.')->group(function () {
    Route::get('/', [LostItemApiController::class, 'index'])->name('index');
    Route::post('/', [LostItemApiController::class, 'store'])->name('store');
    Route::get('/{item}', [LostItemApiController::class, 'show'])->name('show');
    Route::put('/{item}', [LostItemApiController::class, 'update'])->name('update');
    Route::delete('/{item}', [LostItemApiController::class, 'destroy'])->name('destroy');
});
