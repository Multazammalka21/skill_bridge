<?php

use App\Http\Controllers\ParentDashboardWebController;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\QuizManagementController;
use App\Http\Controllers\Admin\BadgeManagementController;
use App\Http\Controllers\PlayController;
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
    
    // Quiz Monitoring Dashboard
    Route::get('/quiz/monitoring', [QuizManagementController::class, 'monitoring'])->name('quiz.monitoring');
    
    // Quiz Management CRUD
    Route::resource('quiz', QuizManagementController::class)->except(['show']);

    // Badge Management CRUD
    Route::resource('badges', BadgeManagementController::class)->except(['show']);
});

// ─── Protected Parent & Child Routes ─────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ParentDashboardWebController::class, 'index'])->name('dashboard');

    // Dynamic Play Views (With active child profile context)
    Route::get('/play/tunanetra/{child}', [PlayController::class, 'tunanetra'])->name('play.tunanetra');
    Route::get('/play/tunarungu/{child}', [PlayController::class, 'tunarungu'])->name('play.tunarungu');
    Route::post('/play/quiz/submit', [PlayController::class, 'submitResult'])->name('play.quiz.submit');
});

// Fallback old routes for safety
Route::get('/play', function () {
    return view('play.index');
})->name('play.index');
