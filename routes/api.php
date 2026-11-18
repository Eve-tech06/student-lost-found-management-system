<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

    // Submit a claim
    Route::post(
        '/items/{itemId}/claim',
        [ClaimApiController::class, 'store']
    )->name('api.claims.store');

    // View the logged-in user's claims
    Route::get(
        '/my-claims',
        [ClaimApiController::class, 'index']
    )->name('api.claims.index');

    // View one of the logged-in user's claims
    Route::get(
        '/my-claims/{claimId}',
        [ClaimApiController::class, 'show']
    )->name('api.claims.show');

});
