<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UrlController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Public API routes

Route::get('/urls/{shortCode}', [UrlController::class, 'get']);

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/urls', [UrlController::class, 'create']);
    Route::get('/urls', [UrlController::class, 'list']);
    Route::put('/urls/{url}', [UrlController::class, 'update']);
    Route::delete('/urls/{url}', [UrlController::class, 'delete']);
    Route::get('/urls/{url}/view', [UrlController::class, 'view']);
});

// User authentication endpoints
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
