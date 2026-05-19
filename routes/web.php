<?php

use App\Http\Controllers\ParentDashboardWebController;
use App\Http\Controllers\WebAuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/login', [WebAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [WebAuthController::class, 'login']);
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

// ─── Protected Web Routes (session auth) ─────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ParentDashboardWebController::class, 'index'])->name('dashboard');
});

