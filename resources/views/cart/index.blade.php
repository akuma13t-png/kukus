@extends('layouts.guest')

@section('content')
<div class="bg-[#1b2838] min-h-screen">
    <div class="max-w-7xl mx-auto py-8">
        
        <h1 class="text-3xl font-light text-white uppercase tracking-wider mb-8">
            Your <span class="font-bold text-[#66c0f4]">Shopping Cart</span>
        </h1>

        <div class="flex flex-col md:flex-row gap-8">
            {{-- KOLOM KIRI: DAFTAR ITEM --}}
            <div class="w-full md:w-3/4 space-y-4">
                
                @if(session('success'))
                    <div class="bg-green-600/20 border border-green-500 text-green-400 p-4 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-600/20 border border-red-500 text-red-400 p-4 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @if(count($cart) > 0)
                    @foreach($cart as $id => $item)
                        <div class="flex bg-[#16202d] p-4 rounded-sm border border-transparent hover:bg-[#1f2c3d] hover:border-gray-600 transition group relative">
                            {{-- Gambar --}}
                            <div class="w-32 h-16 bg-black mr-4 flex-shrink-0">
                                <img src="{{ $item['cover_image'] }}" class="w-full h-full object-cover">
                            </div>

                            {{-- Info --}}
                            <div class="flex-grow flex flex-col justify-between">
                                <h3 class="text-white font-bold text-lg">{{ $item['title'] }}</h3>
                                <div class="text-xs text-gray-500 flex gap-2">
                                    <span class="bg-[#2a2a2a] px-1 rounded">Windows</span>
                                </div>
                            </div>

                            {{-- Harga & Hapus --}}
                            <div class="flex flex-col items-end justify-between">
                                <div class="text-right">
                                    @php
                                        $finalPrice = $item['price'];
                                        if(isset($item['discount_percent']) && $item['discount_percent'] > 0) {
                                            $finalPrice = $item['price'] * (1 - $item['discount_percent'] / 100);
                                        }
                                    @endphp

                                    @if(isset($item['discount_percent']) && $item['discount_percent'] > 0)
                                        <div class="flex items-center gap-2">
                                            <span class="bg-[#4c6b22] text-[#a4d007] text-xs px-1 rounded">-{{ $item['discount_percent'] }}%</span>
                                            <span class="text-xs text-gray-500 line-through">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                        </div>
                                        <div class="text-white font-bold">Rp {{ number_format($finalPrice, 0, ',', '.') }}</div>
                                    @else
                                        <div class="text-white font-bold">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                                    @endif
                                </div>

                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-gray-500 underline hover:text-white mt-2">Remove</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="bg-[#16202d] p-12 text-center rounded">
                        <p class="text-white text-lg mb-4">Keranjang belanja Anda kosong.</p>
                        <a href="{{ route('store.index') }}" class="bg-[#66c0f4] hover:bg-[#419ec0] text-white px-6 py-3 rounded font-bold uppercase text-sm inline-block">
                            Kembali ke Toko
                        </a>
                    </div>
                @endif
            </div>

            {{-- KOLOM KANAN: TOTAL --}}
            @if(count($cart) > 0)
            <div class="w-full md:w-1/4">
                <div class="bg-[#16202d] p-6 rounded-sm sticky top-24">
                    <h3 class="text-gray-400 text-sm font-bold uppercase mb-4">Estimated Total</h3>
                    
                    <div class="text-3xl font-black text-white mb-2">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-500 mb-6">Sales tax will be calculated during checkout where applicable.</div>

                    <a href="{{ route('cart.checkout') }}" class="block w-full bg-gradient-to-r from-[#5c7e10] to-[#76a113] hover:brightness-110 text-white text-center py-4 rounded-sm font-bold text-lg shadow-lg mb-3">
                        Purchase for myself
                    </a>
                    
                    <button disabled class="block w-full bg-gray-600 text-gray-400 text-center py-3 rounded-sm font-bold text-sm cursor-not-allowed">
                        Purchase as a gift
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection