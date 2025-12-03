@extends('layouts.guest')

@section('content')

    {{-- PESAN ERROR JIKA DATABASE KOSONG --}}
    @if($allGames->isEmpty())
        <div
            class="bg-red-600 border-4 border-black text-white p-4 mb-8 mx-auto max-w-7xl shadow-[4px_4px_0px_0px_rgba(255,255,255,1)]">
            <div class="flex items-center gap-4">
                <span class="text-3xl">⚠️</span>
                <div>
                    <p class="font-black text-lg uppercase">Database Kosong!</p>
                    <p class="font-bold">Tidak ada game ditemukan. <a href="/fix-data" class="underline hover:text-black">KLIK
                            DI SINI</a> untuk mengisi data.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-16">

        {{-- 1. FEATURED & RECOMMENDED (Carousel Style Steam - Versi Hitam Putih) --}}
        <section>
            <h2 class="text-xl font-black text-white mb-4 uppercase tracking-widest border-l-8 border-white pl-3">
                Featured & Recommended
            </h2>

            @if($featuredGames->count() > 0)
                @php $feat = $featuredGames->first(); @endphp

                {{-- Container Utama: Putih dengan Border Hitam Tebal --}}
                <div
                    class="flex flex-col md:flex-row bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(255,255,255,0.2)] overflow-hidden h-auto md:h-[400px]">

                    {{-- KIRI: Gambar Besar --}}
                    <div class="w-full md:w-2/3 relative group border-r-4 border-black bg-black">
                        <img src="{{ $feat->cover_image }}"
                            class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition duration-500">

                        {{-- Badge Live Now --}}
                        <div
                            class="absolute top-4 left-4 bg-red-600 text-white font-black px-4 py-1 text-sm border-2 border-black shadow-md transform -rotate-2">
                            LIVE NOW
                        </div>
                    </div>

                    {{-- KANAN: Info Panel --}}
                    <div class="w-full md:w-1/3 p-6 flex flex-col justify-between bg-white text-black relative">

                        {{-- Judul Game --}}
                        <div>
                            <h3
                                class="text-4xl font-black text-black mb-2 leading-none uppercase tracking-tighter line-clamp-2">
                                {{ $feat->title }}
                            </h3>
                            <div class="text-xs font-bold text-gray-500 mb-4 uppercase">
                                Released: {{ \Carbon\Carbon::parse($feat->release_date)->format('d M, Y') }}
                            </div>

                            {{-- Screenshot Kecil (Simulasi) --}}
                            <div class="grid grid-cols-2 gap-2 mb-4">
                                <div class="h-20 bg-gray-200 border-2 border-black hover:bg-gray-300 transition"></div>
                                <div class="h-20 bg-gray-200 border-2 border-black hover:bg-gray-300 transition"></div>
                                <div class="h-20 bg-gray-200 border-2 border-black hover:bg-gray-300 transition"></div>
                                <div class="h-20 bg-gray-200 border-2 border-black hover:bg-gray-300 transition"></div>
                            </div>

                            <div class="flex gap-2">
                                <span class="bg-black text-white text-xs font-bold px-2 py-1 rounded-sm">Top Seller</span>
                                <span
                                    class="bg-gray-200 text-black border border-black text-xs font-bold px-2 py-1 rounded-sm">{{ $feat->genre }}</span>
                            </div>
                        </div>

                        {{-- Harga & Tombol --}}
                        <div class="mt-4">
                            <div class="flex items-center gap-3 mb-3 justify-end">
                                @if($feat->discount_percent > 0)
                                    <span
                                        class="bg-green-500 text-black font-black text-3xl px-2 border-2 border-black transform -rotate-3 shadow-sm">
                                        -{{ $feat->discount_percent }}%
                                    </span>
                                    <div class="flex flex-col items-end leading-none">
                                        <span
                                            class="text-gray-500 text-xs line-through font-bold decoration-2">Rp{{ number_format($feat->price, 0, ',', '.') }}</span>
                                        <span
                                            class="text-green-600 text-xl font-black">Rp{{ number_format($feat->price * (1 - $feat->discount_percent / 100), 0, ',', '.') }}</span>
                                    </div>
                                @else
                                    <span
                                        class="text-2xl font-black text-black">Rp{{ number_format($feat->price, 0, ',', '.') }}</span>
                                @endif
                            </div>

                            <a href="{{ route('game.show', $feat) }}"
                                class="block w-full text-center bg-black hover:bg-gray-800 text-white font-black py-4 text-lg border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[-2px] hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] transition-all">
                                LIHAT DETAIL
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </section>

        {{-- 2. SPECIAL OFFERS (Grid Diskon - Style Kartu Putih) --}}
        <section>
            <h2 class="text-xl font-black text-white mb-4 uppercase tracking-widest border-l-8 border-green-500 pl-3">
                Special Offers
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($allGames->where('discount_percent', '>', 0)->take(3) as $game)
                    <div
                        class="group bg-white border-4 border-black hover:translate-y-[-6px] hover:shadow-[8px_8px_0px_0px_rgba(74,222,128,1)] transition-all duration-200 cursor-pointer">
                        <a href="{{ route('game.show', $game) }}" class="block h-full flex flex-col relative">

                            {{-- Badge Deal --}}
                            <div
                                class="absolute top-0 right-0 bg-blue-600 text-white text-xs font-black px-3 py-1 border-l-4 border-b-4 border-black z-10">
                                DEAL
                            </div>

                            {{-- Gambar --}}
                            <div class="h-48 overflow-hidden border-b-4 border-black bg-gray-100">
                                <img src="{{ $game->cover_image }}" alt="{{ $game->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>

                            {{-- Info --}}
                            <div class="p-5 flex-grow flex flex-col justify-between">
                                <div>
                                    <h4
                                        class="text-xl font-black text-black uppercase leading-tight mb-2 line-clamp-1 group-hover:text-blue-700 transition">
                                        {{ $game->title }}</h4>
                                    <span
                                        class="inline-block bg-gray-200 text-black text-[10px] font-bold px-2 py-0.5 border border-black mb-3">{{ $game->genre }}</span>
                                </div>

                                <div class="flex items-center justify-between mt-4 bg-gray-100 p-2 border-2 border-black">
                                    <span
                                        class="bg-green-500 text-black font-black text-xl px-2 border-2 border-black">-{{ $game->discount_percent }}%</span>
                                    <div class="text-right leading-none">
                                        <div class="text-[10px] text-gray-500 line-through font-bold">
                                            Rp{{ number_format($game->price, 0, ',', '.') }}</div>
                                        <div class="text-lg font-black text-black">
                                            Rp{{ number_format($game->price * (1 - $game->discount_percent / 100), 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-2 text-xs text-gray-500 font-bold">Offer ends soon!</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- 3. NEW & TRENDING (List View Horizontal - Style Putih) --}}
        <section>
            <div class="flex justify-between items-end mb-4 border-b-4 border-white pb-2">
                <h2 class="text-xl font-black text-white uppercase tracking-widest">New & Trending</h2>
                <a href="{{ route('games.search') }}"
                    class="text-xs bg-white text-black font-black px-4 py-2 border-2 border-white hover:bg-gray-300 transition">SEE
                    ALL</a>
            </div>

            <div class="space-y-3">
                @foreach ($allGames->take(6) as $game)
                    <div
                        class="group flex bg-white border-4 border-black hover:border-blue-500 hover:translate-x-2 transition-all duration-200 overflow-hidden relative h-24 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">

                        <a href="{{ route('game.show', $game) }}" class="flex w-full items-center">
                            {{-- Gambar Kecil --}}
                            <img src="{{ $game->cover_image }}" class="h-24 w-48 object-cover border-r-4 border-black" alt="">

                            {{-- Info --}}
                            <div class="flex-grow px-6">
                                <h4 class="font-black text-black text-xl truncate group-hover:text-blue-600 transition">
                                    {{ $game->title }}</h4>
                                <div class="flex gap-2 mt-1">
                                    <span
                                        class="text-xs font-bold text-gray-500 bg-gray-200 px-2 py-0.5 rounded border border-gray-300">{{ $game->genre }}</span>
                                    @if(rand(0, 1)) <span
                                    class="text-xs font-bold text-white bg-black px-2 py-0.5 rounded">WIN</span> @endif
                                </div>
                            </div>

                            {{-- Harga --}}
                            <div class="px-8 text-right min-w-[150px]">
                                @if($game->discount_percent > 0)
                                    <div class="text-xs text-gray-400 line-through font-bold">
                                        Rp{{ number_format($game->price, 0, ',', '.') }}</div>
                                    <div class="text-green-600 font-black text-xl">
                                        Rp{{ number_format($game->price * (1 - $game->discount_percent / 100), 0, ',', '.') }}</div>
                                @else
                                    <div class="text-black font-black text-xl">Rp{{ number_format($game->price, 0, ',', '.') }}
                                    </div>
                                @endif
                            </div>
                        </a>

                        {{-- Tombol Cart (Overlay Slide) --}}
                        <div
                            class="absolute right-0 top-0 h-full w-16 bg-black flex items-center justify-center translate-x-full group-hover:translate-x-0 transition-transform duration-200 border-l-4 border-black">
                            <form action="{{ route('cart.add', $game) }}" method="POST" class="w-full h-full">
                                @csrf
                                <button type="submit"
                                    class="w-full h-full text-white font-black text-3xl hover:bg-green-600 transition flex items-center justify-center">
                                    +
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

    </div>
@endsection