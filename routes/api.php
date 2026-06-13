<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChildController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\ModuleController;
use App\Http\Controllers\Api\ParentDashboardController;
use App\Http\Controllers\Api\QuizController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Pinteria (SDG #4 Quality Education)
|--------------------------------------------------------------------------
| Metode autentikasi:
|
|  JWT Bearer Token  → POST /login  → Authorization: Bearer <token>
|
*/

// ─── 1. Public — tanpa autentikasi ────────────────────────────────────────
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// ─── 2. JWT Bearer — Protected routes ─────────────────────────────────────
// Kirim: Authorization: Bearer <token dari /login>
Route::middleware('auth:api')->group(function () {

    // Auth
    Route::post('/logout',  [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me',       [AuthController::class, 'me']);

    // Children CRUD
    Route::apiResource('children', ChildController::class);

    // Parent Dashboard
    Route::get('/dashboard',                         [ParentDashboardController::class, 'index']);
    Route::get('/dashboard/child/{child}',           [ParentDashboardController::class, 'childProgress']);

    // Modules (Repository Pattern)
    Route::get('/modules', [ModuleController::class, 'index']);

    // Lessons
    Route::get('/lessons',                           [LessonController::class, 'index']);
    Route::get('/lessons/{lesson}',                  [LessonController::class, 'show']);
    Route::post('/lessons/{lesson}/complete',         [LessonController::class, 'complete']);
    Route::post('/lessons/{lesson}/start-session',    [LessonController::class, 'startSession']);
    Route::post('/sessions/{session}/end',            [LessonController::class, 'endSession']);

    // Quiz
    Route::post('/quiz/answer',  [QuizController::class, 'submitAnswer']);
    Route::get('/quiz/results',  [QuizController::class, 'results']);
});
