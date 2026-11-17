<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClaimController;

// Homepage
Route::get('/', function () {
    return view('welcome');
});

// Claims Management Routes
Route::middleware(['auth'])->group(function () {

    // Display the claim submission form
    Route::get('/items/{itemId}/claim', [ClaimController::class, 'create'])
        ->name('claims.create');

    // Process and save a submitted claim
    Route::post('/items/{itemId}/claim', [ClaimController::class, 'store'])
        ->name('claims.store');

    // Display all claims belonging to the logged-in user
    Route::get('/my-claims', [ClaimController::class, 'index'])
        ->name('claims.index');

    // Display details of an individual claim
    Route::get('/my-claims/{claimId}', [ClaimController::class, 'show'])
        ->name('claims.show');

});
