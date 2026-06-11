<?php

use App\Http\Controllers\ParentDashboardWebController;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\LearningPathController;
use App\Http\Controllers\Admin\MediaAssetController;
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
    Route::get('/dashboard/statistik', [AdminDashboardController::class, 'statistik'])->name('dashboard.statistik');

    // ── Kategori Pembelajaran ─────────────────────────────────────────
    Route::resource('categories', CategoryController::class)->except(['show']);

    // ── Materi Pembelajaran (Lessons) ─────────────────────────────────
    Route::post('lessons/reorder', [LessonController::class, 'reorder'])->name('lessons.reorder');
    Route::resource('lessons', LessonController::class)->except(['show']);

    // ── Library Media Asset ───────────────────────────────────────────
    Route::get('media', [MediaAssetController::class, 'index'])->name('media.index');
    Route::post('media', [MediaAssetController::class, 'store'])->name('media.store');
    Route::delete('media/{asset}', [MediaAssetController::class, 'destroy'])->name('media.destroy');

    // ── Learning Path (Urutan Belajar) ────────────────────────────────
    Route::get('learning-path', [LearningPathController::class, 'index'])->name('learning-path.index');
    Route::put('learning-path', [LearningPathController::class, 'update'])->name('learning-path.update');

    // ── Quiz Management ───────────────────────────────────────────────
    Route::get('/quiz/monitoring', [QuizManagementController::class, 'monitoring'])->name('quiz.monitoring');
    Route::resource('quiz', QuizManagementController::class)->except(['show']);

    // ── Badge Management ──────────────────────────────────────────────
    Route::resource('badges', BadgeManagementController::class)->except(['show']);
});

// ─── Protected Parent & Child Routes ─────────────────────────────────
Route::middleware(['auth', 'role:parent'])->group(function () {
    Route::get('/dashboard', [ParentDashboardWebController::class, 'index'])->name('dashboard');
    Route::post('/children', [ParentDashboardWebController::class, 'storeChild'])->name('parent.children.store');

    // Dynamic Play Views (With active child profile context)
    Route::get('/play/start/{child}', [PlayController::class, 'autoPlay'])->name('play.auto');
    Route::get('/play/choose-mode/{child}', [PlayController::class, 'chooseMode'])->name('play.choose-mode');
    Route::get('/play/tunanetra/{child}', [PlayController::class, 'tunanetra'])->name('play.tunanetra');
    Route::get('/play/tunarungu/{child}', [PlayController::class, 'tunarungu'])->name('play.tunarungu');
    Route::post('/play/quiz/submit', [PlayController::class, 'submitResult'])->name('play.quiz.submit');
    Route::post('/play/verify-password', [PlayController::class, 'verifyPassword'])->name('play.verify-password');
});

// Fallback old routes for safety
Route::get('/play', function () {
    return view('play.index');
})->name('play.index');
