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
        // UPDATE: Filter hanya game yang is_approved = true
        $featuredGames = Game::where('is_featured', true)
                             ->where('is_approved', true) // Filter Approved
                             ->inRandomOrder()
                             ->take(3)->get();
                             
        $allGames = Game::where('is_approved', true) // Filter Approved
                        ->take(12)->get();

        return view('store', compact('featuredGames', 'allGames'));
    }

    /**
     * Halaman Pencarian & Filter
     */
    public function search(Request $request)
    {
        // UPDATE: Mulai query dengan filter approved
        $query = Game::where('is_approved', true);

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->input('q') . '%');
        }

        if ($request->filled('genre')) {
            if (is_array($request->input('genre'))) {
                $query->whereIn('genre', $request->input('genre'));
            } else {
                $query->where('genre', $request->input('genre'));
            }
        }

        if ($request->filled('price')) {
            if ($request->input('price') == 'free') {
                $query->where('price', 0);
            } elseif ($request->input('price') == 'paid') {
                $query->where('price', '>', 0);
            }
        }

        $games = $query->paginate(15)->withQueryString();

        return view('search', compact('games'));
    }

    public function show(Game $game)
    {
        // Logic: Jika game belum diapprove, hanya Admin atau pemilik (nanti) yang bisa lihat
        if (!$game->is_approved) {
             if (!Auth::check() || !Auth::user()->isAdmin()) {
                 abort(404);
             }
        }
        return view('game_detail', compact('game'));
    }

    // ... (Fungsi addToCart, libraryIndex, storeShelf BIARKAN SEPERTI SEBELUMNYA) ...
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
        // Hanya ambil game yang approved untuk library umum (kecuali logic library user spesifik)
        $ownedGames = \App\Models\Game::where('is_approved', true)->get();
        $userShelves = Shelf::where('user_id', Auth::id())->with('games')->get();
        return view('library', compact('ownedGames', 'userShelves'));
    }

   public function storeShelf(Request $request)
    {
        $shelf = Shelf::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'type' => $request->mode,
            'criteria' => $request->genre,
        ]);
    
        if ($request->mode === 'manual') {
            if ($request->has('selected_games')) {
                $shelf->games()->attach($request->selected_games);
            }
        } 
        elseif ($request->mode === 'dynamic') {
            $gameIds = \App\Models\Game::where('genre', $request->genre)->pluck('id');
            $shelf->games()->attach($gameIds);
        }
    
        return redirect()->back()->with('success', 'Shelf berhasil dibuat!');
    }

    // --- TAMBAHAN BARU UNTUK PUBLISHER ---

    // 1. Request jadi Publisher
    public function requestPublisher()
    {
        $user = Auth::user();
        if ($user->role === 'admin' || $user->role === 'publisher') {
            return back()->with('info', 'You are already a publisher.');
        }

        $user->update(['publisher_request_status' => 'pending']);
        return back()->with('success', 'Request sent to Admin. Please wait for approval.');
    }

    // 2. Halaman Edit Game
    public function edit(Game $game)
    {
        // Cek permission: Hanya Publisher/Admin
        if (!Auth::user()->isPublisher() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        return view('games.edit', compact('game'));
    }

    // 3. Simpan Perubahan Game
    public function update(Request $request, Game $game)
    {
        if (!Auth::user()->isPublisher() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',a
            'description' => 'required',
            'price' => 'required|numeric',
            'genre' => 'required|string',
            'cover_image' => 'required|url',
        ]);

        $game->update($validated);

        return redirect()->route('game.show', $game)->with('success', 'Game updated successfully!');
    }
}