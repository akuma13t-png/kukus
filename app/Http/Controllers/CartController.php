<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // Menampilkan isi keranjang
    public function index()
    {
        $cart = Session::get('cart', []);
        
        // Hitung total harga
        $total = 0;
        foreach($cart as $item) {
            // Hitung harga setelah diskon jika ada
            $price = $item['price'];
            if(isset($item['discount_percent']) && $item['discount_percent'] > 0) {
                $price = $price * (1 - $item['discount_percent'] / 100);
            }
            $total += $price;
        }

        return view('cart.index', compact('cart', 'total'));
    }

    // Menambahkan game ke keranjang
    public function addToCart($id)
    {
        $game = Game::findOrFail($id);
        $cart = Session::get('cart', []);

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

    // Menghapus game dari keranjang
    public function remove($id)
    {
        $cart = Session::get('cart', []);

        if(isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Game dihapus dari keranjang.');
    }

    // Halaman Checkout (Pilih Pembayaran)
    public function checkout()
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

        return view('cart.checkout', compact('cart', 'total'));
    }

    // Proses Pembayaran (Simulasi)
    public function processPayment(Request $request)
    {
        // Validasi input
        $request->validate([
            'payment_method' => 'required',
        ]);

        // Di sini harusnya ada logika untuk menyimpan transaksi ke database
        // Dan logika menambahkan game ke Library User jika user login.
        
        // Untuk simulasi ini, kita anggap sukses dan kosongkan keranjang
        Session::forget('cart');

        return redirect()->route('cart.success');
    }

    // Halaman Sukses
    public function success()
    {
        return view('cart.success');
    }
}