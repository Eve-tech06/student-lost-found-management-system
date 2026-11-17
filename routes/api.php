<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClaimApiController;

// Claims Management API Routes
Route::middleware(['auth'])->group(function () {

    // Submit a claim
    Route::post(
        '/items/{itemId}/claim',
        [ClaimApiController::class, 'store']
    )->name('api.claims.store');

    // View all claims belonging to the logged-in user
    Route::get(
        '/my-claims',
        [ClaimApiController::class, 'index']
    )->name('api.claims.index');

    // View details of one claim belonging to the logged-in user
    Route::get(
        '/my-claims/{claimId}',
        [ClaimApiController::class, 'show']
    )->name('api.claims.show');

});
