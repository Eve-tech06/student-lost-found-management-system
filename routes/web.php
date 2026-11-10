<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/items', [SearchController::class, 'index'])->name('items.index');
Route::get('/items/search', [SearchController::class, 'search'])->name('items.search');
Route::get('/items/filter', [SearchController::class, 'filter'])->name('items.filter');
Route::get('/items/{id}', [SearchController::class, 'show'])->name('items.show');