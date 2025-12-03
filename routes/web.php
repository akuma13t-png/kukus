<?php

use App\Http\Controllers\GameController; // Pastikan ini ada
use Illuminate\Support\Facades\Route;

// 1. GANTI ROUTE UTAMA (/) ke GameController Anda
Route::get('/', [GameController::class, 'index'])->name('store.index');

// Tambahkan atau pastikan route ini ada
Route::get('/game/{game}', [GameController::class, 'show'])->name('game.show');

// Keranjang Belanja
Route::post('/cart/add/{game}', [GameController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [GameController::class, 'viewCart'])->name('cart.view');

// 2. UBAH ROUTE DASHBOARD agar mengarah ke toko (setelah login)
Route::get('/dashboard', [GameController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Panggil GameController, tapi pastikan hanya diakses jika pengguna sudah login (middleware('auth'))
Route::get('/library', [GameController::class, 'libraryIndex'])
    ->middleware('auth')
    ->name('library.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route Utama (Store Front - Landing Page)
Route::get('/', [GameController::class, 'index'])->name('store.index');

// Route Baru (Halaman Pencarian Khusus)
Route::get('/search', [GameController::class, 'search'])->name('games.search');

// Route Lainnya (Biarkan tetap ada)
// ...

// --- SOLUSI DARURAT UNTUK MENGISI DATA GAME ---
Route::get('/fix-data', function () {
    // 1. Hapus data lama (Reset)
    \App\Models\Game::truncate();

    // 2. Buat Data Manual (Tanpa Factory agar tidak error)
    $titles = ['Galactic Wars', 'Ancient Legends', 'Cyber Punk 2099', 'Farm Simulator', 'Zombie Attack', 'Speed Racer', 'Magic World'];
    $genres = ['Action', 'RPG', 'Sci-Fi', 'Simulation', 'Horror', 'Racing', 'Fantasy'];

    for ($i = 0; $i < 7; $i++) {
        \App\Models\Game::create([
            'title' => $titles[$i],
            'description' => 'Deskripsi seru untuk game ' . $titles[$i],
            'price' => rand(50000, 500000),
            'genre' => $genres[$i],
            'publisher' => 'SteamClone Studio',
            'release_date' => now(),
            'cover_image' => 'https://picsum.photos/400/200?random=' . ($i + 1),
            'is_featured' => rand(0, 1),
            'discount_percent' => rand(0, 1) ? rand(10, 50) : 0,
        ]);
    }

    return "BERHASIL! 7 Game telah ditambahkan manual. <a href='/'>Kembali ke Toko</a>";
});

require __DIR__ . '/auth.php';