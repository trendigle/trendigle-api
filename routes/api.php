<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CelebrityController;
use App\Http\Controllers\Api\ActivityController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/celebrities', [CelebrityController::class, 'store']);
Route::get('/celebrities/{id}', [CelebrityController::class, 'show']);
Route::put('/celebrities/{id}', [CelebrityController::class, 'update']);
Route::delete('/celebrities/{id}', [CelebrityController::class, 'destroy']);

Route::post('/activities', [ActivityController::class, 'store']);
Route::get('/activities/{id}', [ActivityController::class, 'show']);
Route::put('/activities/{id}', [ActivityController::class, 'update']);
Route::delete('/activities/{id}', [ActivityController::class, 'destroy']);
