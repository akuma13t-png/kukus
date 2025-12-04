<?php

namespace App\Http\Controllers;

use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefundController extends Controller
{
    public function create()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();
        
        // Kita perlu menampilkan daftar game yang dimiliki user di form refund
        // Asumsi: User memiliki relasi 'games' (many-to-many via pivot table)
        // Jika belum ada relasi di model User, tambahkan: public function games() { return $this->belongsToMany(Game::class, 'game_user'); }
        // Atau sesuaikan dengan nama tabel pivot Anda (misal: 'library', 'user_games', dll).
        // Di sini kita asumsikan user bisa akses list gamenya via $user->games
        $games = $user->games ?? collect([]); 

        return view('pages.refunds', compact('games'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'reason' => 'required|string|min:10',
        ]);

        $user = Auth::user();

        // Cek apakah user benar-benar memiliki game tersebut (validasi kepemilikan)
        if ($user->games && !$user->games->contains($request->game_id)) {
            return back()->with('error', 'Anda tidak memiliki game ini.');
        }

        // Cek apakah sudah ada request pending untuk game yang sama
        $existing = RefundRequest::where('user_id', $user->id)
            ->where('game_id', $request->game_id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', 'Request refund untuk game ini sedang diproses.');
        }

        RefundRequest::create([
            'user_id' => $user->id,
            'game_id' => $request->game_id,
            'reason' => $request->reason,
        ]);

        return back()->with('success', 'Permintaan refund berhasil dikirim dan menunggu persetujuan Admin.');
    }
}