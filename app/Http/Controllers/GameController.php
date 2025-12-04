<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Shelf; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    // ... method index, search, show, addToCart, libraryIndex, storeShelf TETAP SAMA ...
    
    // (Copy paste method lama yang tidak diubah di sini jika mau full file, 
    // tapi untuk hemat token saya hanya tulis method baru/update sesuai instruksi "Diff" atau "Full File"
    // Saya akan tulis FULL FILE agar aman dan tidak error)

    public function index()
    {
        $featuredGames = Game::where('is_featured', true)
                             ->where('is_approved', true)
                             ->inRandomOrder()
                             ->take(3)->get();
                             
        $allGames = Game::where('is_approved', true)
                        ->take(12)->get();

        return view('store', compact('featuredGames', 'allGames'));
    }

    public function search(Request $request)
    {
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
        // Admin dan Publisher pemilik game boleh lihat meski belum diapprove
        if (!$game->is_approved) {
             $canView = Auth::check() && (Auth::user()->isAdmin() || Auth::user()->name === $game->publisher);
             if (!$canView) {
                 abort(404);
             }
        }
        return view('game_detail', compact('game'));
    }

    public function addToCart(Game $game)
    {
        $cart = Session::get('cart', []);
        if (!isset($cart[$game->id])) {
            $cart[$game->id] = ["id" => $game->id, "title" => $game->title, "price" => $game->price, "cover_image" => $game->cover_image, "discount_percent" => $game->discount_percent];
            Session::put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Added to cart');
    }

    public function libraryIndex()
    {
        $ownedGames = Game::where('is_approved', true)->get();
        $userShelves = Shelf::where('user_id', Auth::id())->with('games')->get();
        return view('library', compact('ownedGames', 'userShelves'));
    }

    public function storeShelf(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mode' => 'required|in:manual,dynamic',
        ]);

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
            $gameIds = Game::where('genre', $request->genre)->pluck('id');
            $shelf->games()->attach($gameIds);
        }
    
        return redirect()->back()->with('success', 'Shelf berhasil dibuat!');
    }

    // --- BARU: Method Create Game (Form Upload) ---
    public function create()
    {
        return view('games.create');
    }

    // --- BARU: Method Store Game (Proses Upload) ---
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'genre' => 'required|string',
            'cover_image' => 'required|image|max:2048', // Max 2MB
            'screenshots.*' => 'image|max:2048', // Multiple images
            'trailer_video' => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg|max:20000', // Max 20MB
        ]);

        // 1. Upload Cover Image
        $coverPath = $request->file('cover_image')->store('covers', 'public');
        $coverUrl = Storage::url($coverPath);

        // 2. Upload Screenshots (Multiple)
        $screenshotUrls = [];
        if($request->hasFile('screenshots')) {
            foreach($request->file('screenshots') as $file) {
                $path = $file->store('screenshots', 'public');
                $screenshotUrls[] = Storage::url($path);
            }
        }

        // 3. Upload Trailer (Video)
        $trailerUrl = null;
        if($request->hasFile('trailer_video')) {
            $path = $request->file('trailer_video')->store('trailers', 'public');
            $trailerUrl = Storage::url($path);
        }

        // 4. Simpan ke Database
        Game::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'genre' => $request->genre,
            'publisher' => Auth::user()->name, // Publisher adalah user yg login
            'release_date' => now(),
            'cover_image' => $coverUrl,
            'screenshots' => $screenshotUrls, // Disimpan sebagai JSON otomatis karena casting di model
            'trailer_url' => $trailerUrl,
            'is_approved' => false, // Default pending
            'is_featured' => false,
            'discount_percent' => 0
        ]);

        return redirect()->route('dashboard')->with('success', 'Game berhasil diupload! Menunggu persetujuan Admin.');
    }

    public function edit(Game $game)
    {
        return view('games.edit', compact('game'));
    }

    public function update(Request $request, Game $game)
    {
        // Logic update sederhana (bisa dikembangkan nanti)
        $game->update($request->only(['title', 'description', 'price', 'genre', 'cover_image']));
        return redirect()->route('game.show', $game)->with('success', 'Game updated');
    }

    public function destroy(Game $game)
    {
        $game->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Game dihapus');
    }
}