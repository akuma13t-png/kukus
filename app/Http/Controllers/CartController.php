<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth; // Import Auth
use Illuminate\Support\Str; // Import Str untuk transaction ID
use Illuminate\Support\Facades\DB; // Import DB facade
// use Barryvdh\DomPDF\Facade\Pdf; // Tidak digunakan, tetap simulasi

class CartController extends Controller
{
    // Menampilkan isi keranjang (TIDAK ADA PERUBAHAN)
    public function index()
    {
        $cart = Session::get('cart', []);
        
        $total = 0;
        foreach($cart as $item) {
            $price = $item['price'];
            if(isset($item['discount_percent']) && $item['discount_percent'] > 0) {
                $price = $price * (1 - $item['discount_percent'] / 100);
            }
            $total += $price;
        }

        return view('cart.index', compact('cart', 'total'));
    }

    // Menambahkan game ke keranjang (TIDAK ADA PERUBAHAN)
    public function addToCart($id)
    {
        $game = Game::findOrFail($id);
        $cart = Session::get('cart', []);

        if(!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk membeli game.');
        }

        // Cek apakah game sudah dimiliki
        $user = Auth::user();
        if ($user->games->contains($id)) {
            return redirect()->back()->with('error', 'Anda sudah memiliki game ini!');
        }

        // Cek jika game sudah ada di cart
        if(isset($cart[$id])) {
            return redirect()->back()->with('error', 'Game ini sudah ada di keranjang Anda!');
        }

        // Simpan data game ke session cart
        $cart[$id] = [
            'id' => $game->id,
            'title' => $game->title,
            'price' => $game->price,
            'cover_image' => $game->cover_image,
            'discount_percent' => $game->discount_percent
        ];

        Session::put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Game berhasil ditambahkan ke keranjang!');
    }

    // Menghapus game dari keranjang (TIDAK ADA PERUBAHAN)
    public function remove($id)
    {
        $cart = Session::get('cart', []);

        if(isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Game dihapus dari keranjang.');
    }

    // Halaman Checkout (UPDATE: Include Vouchers)
    public function checkout(Request $request)
    {
        $cart = Session::get('cart', []);
        
        if(empty($cart)) {
            return redirect()->route('store.index')->with('error', 'Keranjang Anda kosong.');
        }

        $total = 0;
        foreach($cart as $item) {
            $price = $item['price'];
            if(isset($item['discount_percent']) && $item['discount_percent'] > 0) {
                $price = $price * (1 - $item['discount_percent'] / 100);
            }
            $total += $price;
        }

        // Get User Vouchers
        $user = Auth::user();
        $vouchers = $user->vouchers()->wherePivot('is_used', false)->get();

        // Calculate Discount if Voucher Selected
        $discountAmount = 0;
        $selectedVoucher = null;

        if ($request->has('voucher_id')) {
            $voucher = $vouchers->find($request->voucher_id);
            if ($voucher) {
                $selectedVoucher = $voucher;
                if ($voucher->type === 'percent') {
                    $discountAmount = $total * ($voucher->discount_percent / 100);
                } else {
                    $discountAmount = $voucher->discount_amount;
                }
            }
        }
        
        // Ensure discount doesn't exceed total
        $discountAmount = min($discountAmount, $total);
        $finalTotal = $total - $discountAmount;

        return view('cart.checkout', compact('cart', 'total', 'vouchers', 'selectedVoucher', 'discountAmount', 'finalTotal'));
    }

    // Process Pembayaran (UPDATE: Handle Vouchers)
    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required',
            'voucher_id' => 'nullable', // Removed exists check to avoid issues with empty strings/ownership
        ]);

        $cart = Session::get('cart', []);
        if(empty($cart)) {
            return redirect()->route('store.index')->with('error', 'Keranjang Anda kosong.');
        }

        $user = Auth::user();
        $paymentMethod = $request->payment_method;
        $transactionId = 'TXN-' . strtoupper(Str::random(10));
        $voucherId = $request->input('voucher_id') ?: null; // Handle empty string

        try {
            DB::transaction(function () use ($user, $cart, $paymentMethod, $transactionId, $voucherId) {
                // Lock user record for update to prevent race conditions
                $user = \App\Models\User::where('id', $user->id)->lockForUpdate()->first();

                $totalTransaction = 0;
                $purchasedGames = [];

                // 1. Hitung Total Awal
                foreach ($cart as $item) {
                    $price = $item['price'];
                    if(isset($item['discount_percent']) && $item['discount_percent'] > 0) {
                        $price = $price * (1 - $item['discount_percent'] / 100);
                    }
                    $totalTransaction += $price;
                    $purchasedGames[] = array_merge($item, ['final_price' => $price]);
                }

                // 2. Apply Voucher Logic
                $discountAmount = 0;
                if ($voucherId) {
                    // Check if user owns voucher and is unused
                    $userVoucher = $user->vouchers()->where('voucher_id', $voucherId)->wherePivot('is_used', false)->first();
                    
                    if ($userVoucher) {
                        if ($userVoucher->type === 'percent') {
                            $discountAmount = $totalTransaction * ($userVoucher->discount_percent / 100);
                        } else {
                            $discountAmount = $userVoucher->discount_amount;
                        }
                        
                        // Mark voucher as used
                        $user->vouchers()->updateExistingPivot($voucherId, ['is_used' => true]);
                    }
                }

                // Ensure discount doesn't exceed total
                $discountAmount = min($discountAmount, $totalTransaction);
                $finalTotal = $totalTransaction - $discountAmount;

                // 3. Logika Saldo Kukus Money
                if ($paymentMethod === 'kukus_money') {
                    if ($user->kukus_money_balance < $finalTotal) {
                        throw new \Exception('Saldo Kukus Money Anda (Rp' . number_format($user->kukus_money_balance, 0, ',', '.') . 
                                         ') tidak cukup untuk total pembelian Rp' . number_format($finalTotal, 0, ',', '.') . '.');
                    }
                    
                    // Berhasil: Kurangi saldo Kukus Money
                    $user->kukus_money_balance -= $finalTotal;
                    $user->save();
                } 

                // 4. Tambahkan ke Library (berlaku untuk semua metode pembayaran)
                foreach ($purchasedGames as $item) {
                    try {
                        $user->games()->attach($item['id'], [
                            'purchase_price' => $item['final_price'], // Note: Storing original price per item, not discounted. 
                            // Ideally we distribute discount, but for simplicity we store base price.
                            'transaction_id' => $transactionId,
                            'created_at' => now(),
                            'updated_at' => now(),
                            'purchase_method' => $paymentMethod, 
                        ]);
                    } catch (\Exception $e) {
                        // Abaikan jika game sudah dimiliki
                        continue; 
                    }
                }
                
                // 5. Siapkan data transaksi untuk halaman sukses
                $transactionData = [
                    'id' => $transactionId,
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'total' => $finalTotal,
                    'discount' => $discountAmount,
                    'method' => $paymentMethod,
                    'date' => now()->format('d F Y H:i:s'),
                    'items' => $purchasedGames,
                ];

                Session::put('last_transaction', $transactionData);
                Session::forget('cart');
            });
        } catch (\Exception $e) {
            return redirect()->route('cart.checkout')->with('error', $e->getMessage());
        }

        return redirect()->route('cart.success');
    }

    // Halaman Sukses (TIDAK ADA PERUBAHAN)
    public function success()
    {
        $transaction = Session::get('last_transaction');

        if (!$transaction) {
            return redirect()->route('store.index')->with('error', 'Tidak ada riwayat transaksi ditemukan.');
        }

        return view('cart.success', compact('transaction'));
    }

    // FITUR BARU: Download Receipt/Nota (TAMBAHKAN DETAIL METHOD)
    public function downloadReceipt()
    {
        $transaction = Session::get('last_transaction');

        if (!$transaction) {
            return redirect()->route('store.index');
        }

        $receiptContent = "========================================\n";
        $receiptContent .= "         STEAMCLONE RECEIPT\n";
        $receiptContent .= "========================================\n";
        $receiptContent .= "Transaction ID: " . $transaction['id'] . "\n";
        $receiptContent .= "Date: " . $transaction['date'] . "\n";
        $receiptContent .= "Customer: " . $transaction['user_name'] . "\n";
        // Tampilkan Metode Pembayaran
        $method = $transaction['method'] === 'kukus_money' ? 'Kukus Money' : ucfirst($transaction['method']);
        $receiptContent .= "Payment Method: " . $method . "\n"; 
        $receiptContent .= "----------------------------------------\n";
        $receiptContent .= "ITEMS:\n";
        
        foreach ($transaction['items'] as $item) {
            $receiptContent .= "- " . $item['title'] . ": Rp " . number_format($item['final_price'], 0, ',', '.') . "\n";
        }

        $receiptContent .= "----------------------------------------\n";
        $receiptContent .= "TOTAL: Rp " . number_format($transaction['total'], 0, ',', '.') . "\n";
        $receiptContent .= "========================================\n";
        $receiptContent .= "Thank you for your purchase!\n";

        return response($receiptContent, 200)
                ->header('Content-Type', 'text/plain')
                ->header('Content-Disposition', 'attachment; filename="receipt-' . $transaction['id'] . '.txt"');
    }
}