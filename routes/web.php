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
Route::get('/register', [WebAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [WebAuthController::class, 'register']);
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

// ─── Protected Web Routes (session auth) ─────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ParentDashboardWebController::class, 'index'])->name('dashboard');
});

// ─── Play Routes (Token/Cookie Auth) ─────────────────────────────────
Route::get('/play', function () {
    return view('play.index');
})->name('play.index');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/play/tunanetra', function () {
        return view('play.tunanetra');
    })->name('play.tunanetra');

    Route::get('/play/tunarungu', function () {
        return view('play.tunarungu');
    })->name('play.tunarungu');
});

