<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Voucher;
use App\Models\User;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::all();
        $user = Auth::user();
        return view('community.voucher-shop', compact('vouchers', 'user'));
    }

    public function buy(Request $request, Voucher $voucher)
    {
        $user = Auth::user();

        // 1. Check balance
        if ($user->kukus_coins < $voucher->cost_in_coins) {
            return redirect()->back()->with('error', 'Not enough Kukus Coins!');
        }

        // 2. Check if user already owns this voucher (optional, but good for unique codes)
        // For now, let's allow multiples or check logic. 
        // If code is unique per user usage, we might want to allow multiples.
        // But simpler: One active voucher of a type? Let's allow multiples.
        
        // 3. Deduct coins
        $user->kukus_coins -= $voucher->cost_in_coins;
        $user->save();

        // 4. Add to inventory
        $user->vouchers()->attach($voucher->id, ['is_used' => false]);

        return redirect()->back()->with('success', 'Voucher purchased successfully!');
    }
    // ADMIN ONLY: Grant Voucher for Free
    public function grant(Request $request, Voucher $voucher)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $user = Auth::user();
        $user->vouchers()->attach($voucher->id, ['is_used' => false]);

        return redirect()->back()->with('success', 'Voucher granted for free (Admin Test)!');
    }
}
