   <?php

   use App\Http\Controllers\Api\FoundItemApiController;
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Route;

   Route::get('/user', function (Request $request) {
       return $request->user();
   })->middleware('auth:sanctum');

   Route::middleware('auth:sanctum')->prefix('found-items')->group(function () {
       Route::get('/', [FoundItemApiController::class, 'index']);
       Route::post('/', [FoundItemApiController::class, 'store']);
       Route::get('/{item}', [FoundItemApiController::class, 'show']);
       Route::match(['put', 'patch'], '/{item}', [FoundItemApiController::class, 'update']);
   });