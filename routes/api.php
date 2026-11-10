<?php
use App\Http\Controllers\Api\SearchApiController;

Route::get('/items', [SearchApiController::class, 'index']);
Route::get('/items/search', [SearchApiController::class, 'search']);
Route::get('/items/filter', [SearchApiController::class, 'filter']);
Route::get('/items/{id}', [SearchApiController::class, 'show']);