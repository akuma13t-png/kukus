<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Menampilkan Dashboard Admin
    public function index()
    {
        // Security Check: Hanya Admin yang boleh masuk
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Ambil data untuk ditampilkan
        $pendingPublishers = User::where('publisher_request_status', 'pending')->get();
        $pendingGames = Game::where('is_approved', false)->get();
        $allUsers = User::where('role', '!=', 'admin')->get(); // Semua user selain admin
        $allGames = Game::all();

        return view('admin.dashboard', compact('pendingPublishers', 'pendingGames', 'allUsers', 'allGames'));
    }

    // --- FITUR MANAJEMEN USER ---

    public function approvePublisher(User $user)
    {
        $user->update([
            'role' => 'publisher',
            'publisher_request_status' => 'approved'
        ]);
        return back()->with('success', 'User approved as Publisher.');
    }

    public function rejectPublisher(User $user)
    {
        $user->update(['publisher_request_status' => 'rejected']);
        return back()->with('success', 'Publisher request rejected.');
    }

    public function banUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'User has been banned.');
    }

    // --- FITUR MANAJEMEN GAME ---

    public function approveGame(Game $game)
    {
        $game->update(['is_approved' => true]);
        return back()->with('success', 'Game approved and published.');
    }

    public function rejectGame(Game $game)
    {
        $game->delete(); // Hapus game jika tidak layak
        return back()->with('success', 'Game rejected and deleted.');
    }
}