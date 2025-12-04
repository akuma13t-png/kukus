<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LibraryController; 
use App\Http\Controllers\PageController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

// --- EXISTING ROUTES ---
Route::get('/', [GameController::class, 'index'])->name('store.index');
Route::get('/game/{id}', [GameController::class, 'show'])->name('game.show');
Route::get('/search', [GameController::class, 'search'])->name('games.search');

// --- STATIC PAGE ROUTES ---
Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/privacy', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/stats', [PageController::class, 'stats'])->name('pages.stats');

// --- CART ROUTES ---
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/checkout/process', [CartController::class, 'processPayment'])->name('cart.process');
Route::get('/checkout/success', [CartController::class, 'success'])->name('cart.success');

// --- AUTH ROUTES ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
    Route::post('/shelf', [LibraryController::class, 'storeShelf'])->name('shelf.store');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Publisher Request & Game Upload (Role: Publisher Only)
    Route::post('/request-publisher', function (Illuminate\Http\Request $request) {
        $user = $request->user();
        $user->publisher_request_status = 'pending';
        $user->save();
        return back()->with('status', 'request-sent');
    })->name('user.request_publisher');

    // Route khusus Publisher untuk Upload Game
    Route::middleware('role:publisher')->group(function() {
        Route::get('/publish/game', [GameController::class, 'create'])->name('games.create');
        Route::post('/publish/game', [GameController::class, 'store'])->name('games.store');
    });

    // --- REFUND ROUTES ---
    Route::get('/support/refunds', [RefundController::class, 'create'])->name('refunds.create');
    Route::post('/support/refunds', [RefundController::class, 'store'])->name('refunds.store');
});

// --- ADMIN ROUTES ---
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/history', [AdminController::class, 'history'])->name('admin.history'); // Route History Baru
    
    // Publisher Actions
    Route::post('/admin/approve/{user}', [AdminController::class, 'approvePublisher'])->name('admin.approvePublisher');
    Route::post('/admin/reject/{user}', [AdminController::class, 'rejectPublisher'])->name('admin.rejectPublisher');
    
    // Game Approval Actions
    Route::post('/admin/game/{game}/approve', [AdminController::class, 'approveGame'])->name('admin.game.approve');
    Route::post('/admin/game/{game}/reject', [AdminController::class, 'rejectGame'])->name('admin.game.reject');

    // Refund Actions
    Route::post('/admin/refund/{id}/approve', [AdminController::class, 'approveRefund'])->name('admin.refund.approve');
    Route::post('/admin/refund/{id}/reject', [AdminController::class, 'rejectRefund'])->name('admin.refund.reject');
    
    // Game Management (Edit/Delete existing)
    Route::get('/games/{game}/edit', [GameController::class, 'edit'])->name('games.edit');
    Route::put('/games/{game}', [GameController::class, 'update'])->name('games.update');
    Route::delete('/games/{game}', [GameController::class, 'destroy'])->name('games.destroy');
});

require __DIR__.'/auth.php';