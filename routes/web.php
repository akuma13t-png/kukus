<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AdminController; // Import AdminController
use Illuminate\Support\Facades\Route;

// --- PUBLIC ROUTES ---
Route::get('/', [GameController::class, 'index'])->name('store.index');
Route::get('/game/{game}', [GameController::class, 'show'])->name('game.show');
Route::get('/search', [GameController::class, 'search'])->name('games.search');

// --- AUTHENTICATED USERS ROUTES ---
Route::middleware('auth')->group(function () {
    // Dashboard User (Arahkan ke store atau custom dashboard)
    Route::get('/dashboard', [GameController::class, 'index'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Library & Cart
    Route::get('/library', [GameController::class, 'libraryIndex'])->name('library.index');
    Route::post('/library/shelf', [GameController::class, 'storeShelf'])->name('shelf.store');
    
    // Cart Routes
    Route::post('/cart/add/{game}', [GameController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [GameController::class, 'viewCart'])->name('cart.view'); // Pastikan method viewCart ada di Controller jika dipakai

    // Request Publisher
    Route::post('/request-publisher', [GameController::class, 'requestPublisher'])->name('user.request_publisher');
});

// --- PUBLISHER ROUTES ---
Route::middleware(['auth'])->group(function () {
    // Edit Game
    Route::get('/game/{game}/edit', [GameController::class, 'edit'])->name('game.edit');
    Route::put('/game/{game}', [GameController::class, 'update'])->name('game.update');
});

// --- ADMIN ROUTES ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    
    // Manage Users
    Route::post('/approve-publisher/{user}', [AdminController::class, 'approvePublisher'])->name('approve_publisher');
    Route::post('/reject-publisher/{user}', [AdminController::class, 'rejectPublisher'])->name('reject_publisher');
    Route::delete('/ban-user/{user}', [AdminController::class, 'banUser'])->name('ban_user');

    // Manage Games
    Route::post('/approve-game/{game}', [AdminController::class, 'approveGame'])->name('approve_game');
    Route::delete('/reject-game/{game}', [AdminController::class, 'rejectGame'])->name('reject_game');
});

require __DIR__ . '/auth.php';