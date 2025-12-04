<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RefundRequest; // Jangan lupa import Model ini
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        
        // Ambil data refund, urutkan dari yang terbaru
        $refunds = RefundRequest::with(['user', 'game'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admin.dashboard', compact('users', 'refunds'));
    }

    // --- LOGIKA UNTUK PUBLISHER (YANG SUDAH ADA) ---
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

    // --- LOGIKA BARU UNTUK REFUND ---
    
    public function approveRefund($id)
    {
        $refund = RefundRequest::findOrFail($id);
        
        if ($refund->status !== 'pending') {
            return back()->with('error', 'Request sudah diproses sebelumnya.');
        }

        // 1. Ubah status refund jadi approved
        $refund->update(['status' => 'approved']);

        // 2. Hapus game dari library user
        // Kita gunakan detach() untuk menghapus relasi di tabel pivot
        $user = $refund->user;
        if($user->games) {
            $user->games()->detach($refund->game_id);
        }

        return back()->with('success', 'Refund disetujui. Game telah dihapus dari library user.');
    }

    public function rejectRefund($id)
    {
        $refund = RefundRequest::findOrFail($id);
        $refund->update(['status' => 'rejected']);

        return back()->with('success', 'Refund ditolak.');
    }
}