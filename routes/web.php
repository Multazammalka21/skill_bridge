<?php

use App\Http\Controllers\ParentDashboardWebController;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\QuizManagementController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Onboarding route
Route::get('/onboarding', function () {
    return view('onboarding');
})->name('onboarding');

Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'admin' 
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('dashboard');
    }
    return redirect()->route('onboarding');
});

Route::get('/login', [WebAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [WebAuthController::class, 'login']);
Route::get('/register', [WebAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [WebAuthController::class, 'register']);
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

// ─── Protected Admin Routes ──────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Quiz Management CRUD
    Route::resource('quiz', QuizManagementController::class)->except(['show']);
});

// ─── Protected Parent Routes ─────────────────────────────────────────
Route::middleware(['auth', 'role:parent'])->group(function () {
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
