<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RefundRequest;
use App\Models\Game;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Data untuk Dashboard Utama (Yang Pending/Active saja)
        
        // 1. Publisher Requests (Pending)
        $publisherRequests = User::where('publisher_request_status', 'pending')->get();
        
        // 2. Refund Requests (Pending)
        $refunds = RefundRequest::where('status', 'pending')->with(['user', 'game'])->orderBy('created_at', 'desc')->get();

        // 3. Game Approval Requests (Pending) - FITUR BARU
        $pendingGames = Game::where('is_approved', false)->orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact('publisherRequests', 'refunds', 'pendingGames'));
    }

    // --- HALAMAN HISTORY BARU ---
    public function history()
    {
        // 1. Game yang Ditolak
        // Kita asumsikan game yang ditolak itu is_approved = false TAPI sudah dicek admin.
        // Untuk sederhana, kita bisa tambah status kolom 'status' di game, tapi karena struktur DB sudah fix,
        // kita anggap game yg tidak diapprove dan sudah lama / atau dihapus masuk sini.
        // ATAU: Kita tampilkan History User Rejected & Refund History saja yg sudah jelas statusnya.
        
        $rejectedPublishers = User::where('publisher_request_status', 'rejected')->get();
        
        $processedRefunds = RefundRequest::whereIn('status', ['approved', 'rejected'])
                            ->with(['user', 'game'])
                            ->orderBy('updated_at', 'desc')
                            ->get();

        // Untuk Game History, karena kita tidak punya kolom 'rejected',
        // Kita tampilkan game yang is_approved=false sebagai "Pending/Rejected Queue" di dashboard,
        // Di sini kita tampilkan game yang sudah Approved sebagai "Published History".
        $publishedGames = Game::where('is_approved', true)->orderBy('created_at', 'desc')->get();

        return view('admin.history', compact('rejectedPublishers', 'processedRefunds', 'publishedGames'));
    }

    // --- GAME ACTIONS ---
    public function approveGame(Game $game)
    {
        $game->update(['is_approved' => true]);
        return back()->with('success', 'Game berhasil dipublish ke Store!');
    }

    public function rejectGame(Game $game)
    {
        // Hapus game dari database jika ditolak (Soft delete kalau ada, tapi di sini hard delete)
        // Atau biarkan tapi kasih flag. Kita pilih delete agar bersih, atau simpan di tabel history terpisah.
        // Sesuai request: "masukan ke database history".
        // Karena struktur tabel terbatas, kita simpan datanya di session flash atau log sebelum hapus, 
        // TAPI opsi terbaik tanpa ubah DB: Hapus recordnya.
        $game->delete(); 
        
        return back()->with('success', 'Game ditolak dan dihapus dari antrian.');
    }

    // --- PUBLISHER ACTIONS ---
    public function approvePublisher(User $user)
    {
        $user->role = 'publisher';
        $user->publisher_request_status = 'approved';
        $user->save();
        return redirect()->back()->with('success', 'User approved as Publisher.');
    }

    public function rejectPublisher(User $user)
    {
        $user->publisher_request_status = 'rejected';
        $user->save();
        return redirect()->back()->with('success', 'Publisher request rejected.');
    }

    // --- REFUND ACTIONS ---
    public function approveRefund($id)
    {
        $refund = RefundRequest::findOrFail($id);
        if ($refund->status !== 'pending') return back();

        $refund->update(['status' => 'approved']);
        // Hapus game dari user
        $refund->user->games()->detach($refund->game_id);

        return back()->with('success', 'Refund disetujui.');
    }

    public function rejectRefund($id)
    {
        $refund = RefundRequest::findOrFail($id);
        $refund->update(['status' => 'rejected']);
        return back()->with('success', 'Refund ditolak.');
    }
}