<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\HabitLogController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('habits/{id}/stats', [HabitLogController::class, 'stats']);
    Route::apiResource('habits', HabitController::class);
    
    Route::post('habits/{id}/logs', [HabitLogController::class, 'store']);
    Route::get('habits/{id}/logs', [HabitLogController::class, 'index']);
});