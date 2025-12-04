<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Shelf; 
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    /**
     * Halaman Depan Toko (Landing Page)
     */
    public function index()
    {
        // Hanya ambil data untuk display depan
        $featuredGames = Game::where('is_featured', true)->inRandomOrder()->take(3)->get();
        $allGames = Game::take(12)->get(); // Ambil 12 game sembarang untuk list bawah

        return view('store', compact('featuredGames', 'allGames'));
    }

    /**
     * Halaman Pencarian & Filter (Search Page)
     */
    public function search(Request $request)
    {
        $query = Game::query();

        // 1. Filter Judul (Search Bar)
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->input('q') . '%');
        }

        // 2. Filter Genre (Sidebar - Checkbox/Link)
        if ($request->filled('genre')) {
            // Jika genre berupa array (checkbox), gunakan whereIn
            if (is_array($request->input('genre'))) {
                $query->whereIn('genre', $request->input('genre'));
            } else {
                $query->where('genre', $request->input('genre'));
            }
        }

        // 3. Filter Harga
        if ($request->filled('price')) {
            if ($request->input('price') == 'free') {
                $query->where('price', 0);
            } elseif ($request->input('price') == 'paid') {
                $query->where('price', '>', 0);
            }
        }

        // Ambil hasil
        $games = $query->paginate(15)->withQueryString(); // Gunakan pagination biar rapi

        return view('search', compact('games'));
    }

    // ... Fungsi show(), addToCart(), libraryIndex() biarkan seperti sebelumnya ...
    public function show(Game $game)
    {
        return view('game_detail', compact('game'));
    }

    public function addToCart(Game $game)
    {
        $cart = Session::get('cart', []);
        if (!isset($cart[$game->id])) {
            $cart[$game->id] = ["title" => $game->title, "price" => $game->price, "cover_image" => $game->cover_image];
            Session::put('cart', $cart);
        }
        return redirect()->back();
    }

    public function libraryIndex()
{
    // 1. Ambil semua game (untuk list utama)
    $ownedGames = \App\Models\Game::all();

    // 2. AMBIL SHELF DARI DATABASE (Agar tidak hilang saat refresh)
    // Kita ambil shelf milik user yang login, beserta isi gamenya
    $userShelves = Shelf::where('user_id', Auth::id())
                        ->with('games') // Load relasi games
                        ->get();

    return view('library', compact('ownedGames', 'userShelves'));
}

   public function storeShelf(Request $request)
{
    // 1. Buat Shelf (Wadah Rak)
    $shelf = Shelf::create([
        'user_id' => Auth::id(),
        'name' => $request->name,
        'type' => $request->mode, // 'manual' atau 'dynamic'
        'criteria' => $request->genre, // Simpan genre (misal: RPG)
    ]);

    // 2. LOGIKA FILTER (Ini yang memperbaiki masalah game masuk semua)
    
    // KASUS A: Jika User memilih MANUAL
    if ($request->mode === 'manual') {
        // Cek apakah ada game yang dicentang?
        if ($request->has('selected_games')) {
            // HANYA masukkan game yang ID-nya dikirim dari form
            $shelf->games()->attach($request->selected_games);
        }
    } 
    // KASUS B: Jika User memilih DYNAMIC (Otomatis)
    elseif ($request->mode === 'dynamic') {
        // Cari game yang genrenya SAMA dengan yang dipilih
        $gameIds = \App\Models\Game::where('genre', $request->genre)->pluck('id');
        
        // Masukkan game hasil filter tersebut ke shelf
        $shelf->games()->attach($gameIds);
    }

    return redirect()->back()->with('success', 'Shelf berhasil dibuat!');
}

}