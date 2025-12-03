<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
        $ownedGames = Game::all();
        return view('library', compact('ownedGames'));
    }
}