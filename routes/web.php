<?php

use App\Http\Controllers\ParentDashboardWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ─── Protected Web Routes (session auth) ─────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ParentDashboardWebController::class, 'index'])->name('dashboard');
});
