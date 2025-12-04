@extends('layouts.guest')

@section('content')
<div class="bg-[#1b2838] min-h-screen py-8">
    <div class="max-w-4xl mx-auto">
        
        {{-- Breadcrumb --}}
        <div class="text-xs text-blue-400 font-bold mb-6 uppercase">
            <a href="{{ route('cart.index') }}" class="hover:text-white">Cart</a> > <span class="text-gray-400">Payment</span>
        </div>

        <h1 class="text-3xl font-light text-white uppercase tracking-wider mb-8">
            Payment <span class="font-bold text-[#66c0f4]">Method</span>
        </h1>

        <div class="flex flex-col md:flex-row gap-8 bg-[#16202d] p-8 border border-black shadow-2xl">
            
            {{-- KIRI: Form Pembayaran --}}
            <div class="w-full md:w-2/3 border-r border-black pr-8">
                <form action="{{ route('cart.process') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-[#66c0f4] text-xs font-bold uppercase mb-2">Pilih Metode Pembayaran</label>
                        <div class="space-y-2">
                            <label class="flex items-center bg-[#2a3f5a] p-3 rounded cursor-pointer border border-transparent hover:border-white group">
                                <input type="radio" name="payment_method" value="dana" class="form-radio text-[#66c0f4] focus:ring-0" checked>
                                <span class="ml-3 text-white font-bold group-hover:text-[#66c0f4]">DANA</span>
                                <span class="ml-auto text-xs text-gray-400">E-Wallet</span>
                            </label>

                            <label class="flex items-center bg-[#2a3f5a] p-3 rounded cursor-pointer border border-transparent hover:border-white group">
                                <input type="radio" name="payment_method" value="qris" class="form-radio text-[#66c0f4] focus:ring-0">
                                <span class="ml-3 text-white font-bold group-hover:text-[#66c0f4]">QRIS</span>
                                <span class="ml-auto text-xs text-gray-400">Scan QR</span>
                            </label>

                            <label class="flex items-center bg-[#2a3f5a] p-3 rounded cursor-pointer border border-transparent hover:border-white group">
                                <input type="radio" name="payment_method" value="bca" class="form-radio text-[#66c0f4] focus:ring-0">
                                <span class="ml-3 text-white font-bold group-hover:text-[#66c0f4]">Bank Transfer (BCA)</span>
                                <span class="ml-auto text-xs text-gray-400">Virtual Account</span>
                            </label>

                            <label class="flex items-center bg-[#2a3f5a] p-3 rounded cursor-pointer border border-transparent hover:border-white group">
                                <input type="radio" name="payment_method" value="visa" class="form-radio text-[#66c0f4] focus:ring-0">
                                <span class="ml-3 text-white font-bold group-hover:text-[#66c0f4]">Visa / MasterCard</span>
                                <span class="ml-auto text-xs text-gray-400">Credit Card</span>
                            </label>
                        </div>
                        @error('payment_method')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-8 border-t border-gray-700 pt-6">
                        <p class="text-xs text-gray-400 mb-4">
                            Dengan mengklik "Lanjutkan Pembelian", Anda menyetujui <a href="#" class="text-white hover:underline">Perjanjian Pelanggan SteamClone</a>.
                        </p>
                        <button type="submit" class="bg-gradient-to-r from-[#5c7e10] to-[#76a113] hover:brightness-110 text-white font-bold py-3 px-8 rounded-sm shadow-lg uppercase tracking-wider text-sm w-full md:w-auto">
                            Lanjutkan Pembelian
                        </button>
                    </div>
                </form>
            </div>

            {{-- KANAN: Ringkasan --}}
            <div class="w-full md:w-1/3">
                <h3 class="text-gray-400 text-xs font-bold uppercase mb-4">Order Summary</h3>
                
                <div class="space-y-2 mb-4 max-h-60 overflow-y-auto custom-scrollbar">
                    @foreach($cart as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-300 truncate w-2/3">{{ $item['title'] }}</span>
                            <span class="text-gray-400">
                                @if(isset($item['discount_percent']) && $item['discount_percent'] > 0)
                                    Rp {{ number_format($item['price'] * (1 - $item['discount_percent'] / 100), 0, ',', '.') }}
                                @else
                                    Rp {{ number_format($item['price'], 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-600 pt-4 flex justify-between items-center">
                    <span class="text-white font-bold">Total:</span>
                    <span class="text-[#66c0f4] font-black text-xl">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection