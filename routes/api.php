```php
<?php

use App\Http\Controllers\Api\FoundItemApiController;
use App\Http\Controllers\Api\SearchApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Search API Routes
Route::get('/items', [SearchApiController::class, 'index']);
Route::get('/items/search', [SearchApiController::class, 'search']);
Route::get('/items/filter', [SearchApiController::class, 'filter']);
Route::get('/items/{id}', [SearchApiController::class, 'show']);
```