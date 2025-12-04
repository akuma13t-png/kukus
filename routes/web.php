<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LibraryController; 
use App\Http\Controllers\PageController; // Import Controller Baru
use App\Http\Controllers\RefundController; // Import Controller Baru
use Illuminate\Support\Facades\Route;

// --- EXISTING ROUTES ---
Route::get('/', [GameController::class, 'index'])->name('store.index');
Route::get('/game/{id}', [GameController::class, 'show'])->name('game.show');
Route::get('/search', [GameController::class, 'search'])->name('games.search');

// --- NEW STATIC PAGE ROUTES (FOOTER) ---
Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/privacy', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/stats', [PageController::class, 'stats'])->name('pages.stats');

// --- AUTH ROUTES ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/library', [LibraryController::class, 'index'])->name('library.index');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Publisher Request
    Route::post('/request-publisher', function (Illuminate\Http\Request $request) {
        $user = $request->user();
        $user->publisher_request_status = 'pending';
        $user->save();
        return back()->with('status', 'request-sent');
    })->name('user.request_publisher');

    // --- NEW REFUND ROUTES ---
    Route::get('/support/refunds', [RefundController::class, 'create'])->name('refunds.create');
    Route::post('/support/refunds', [RefundController::class, 'store'])->name('refunds.store');
});

// --- ADMIN ROUTES (UPDATED) ---
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Publisher Actions
    Route::post('/admin/approve/{user}', [AdminController::class, 'approvePublisher'])->name('admin.approvePublisher');
    Route::post('/admin/reject/{user}', [AdminController::class, 'rejectPublisher'])->name('admin.rejectPublisher');
    
    // --- NEW REFUND ACTIONS ---
    Route::post('/admin/refund/{id}/approve', [AdminController::class, 'approveRefund'])->name('admin.refund.approve');
    Route::post('/admin/refund/{id}/reject', [AdminController::class, 'rejectRefund'])->name('admin.refund.reject');
    
    // Game Management (Existing)
    Route::get('/games/create', [GameController::class, 'create'])->name('games.create');
    Route::post('/games', [GameController::class, 'store'])->name('games.store');
    Route::get('/games/{game}/edit', [GameController::class, 'edit'])->name('games.edit');
    Route::put('/games/{game}', [GameController::class, 'update'])->name('games.update');
    Route::delete('/games/{game}', [GameController::class, 'destroy'])->name('games.destroy');
});

require __DIR__.'/auth.php';