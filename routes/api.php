<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NewsController;

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


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);
Route::middleware('auth:sanctum')->post('/news', [NewsController::class, 'store']);
Route::middleware('auth:sanctum')->get('/news', [NewsController::class, 'index']);
        
Route::middleware('auth:sanctum')->post('/upload-image', [NewsController::class, 'uploadImage']);
Route::middleware('auth:sanctum')->post('/news/{news}/like', [NewsController::class, 'toggleLike']);
// api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/news/{id}', [NewsController::class, 'show']); // API untuk edit
    Route::put('/news/{id}', [NewsController::class, 'update']);
});