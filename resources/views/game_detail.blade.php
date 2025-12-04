@extends('layouts.guest')

@section('content')
    <div class="bg-gray-900 text-gray-200 min-h-screen">

        {{-- HEADER NAVIGASI TIRUAN STEAM --}}
        <header class="bg-gray-800 shadow-lg border-b border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex justify-between items-center">

                <div class="flex items-center space-x-6">
                    <a href="{{ route('store.index') }}" class="text-2xl font-black text-white tracking-widest">
                        STEAM<span class="text-blue-500">CLONE</span>
                    </a>
                    <nav class="hidden md:flex space-x-4 text-sm font-semibold">
                        <a href="{{ route('store.index') }}" class="hover:text-white text-gray-400">STORE</a>
                        <a href="{{ route('library.index') }}" class="hover:text-white text-gray-400">LIBRARY</a>
                        <a href="#" class="hover:text-white text-gray-400">COMMUNITY</a>
                    </nav>
                </div>

                <div class="space-x-2 flex items-center bg-gray-700 p-1 rounded-sm">
                    @if (Route::has('login'))
                        @auth
                            <span class="text-sm text-gray-400">Halo, {{ Auth::user()->name }}</span>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="text-xs bg-red-600 hover:bg-red-700 text-white px-2 py-0.5 rounded-sm font-bold">LOGOUT</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-xs text-gray-300 hover:text-white px-2 py-0.5 font-bold border-r border-gray-600 pr-3">LOGIN</a>
                            <a href="{{ route('register') }}"
                                class="text-xs text-gray-300 hover:text-white px-2 py-0.5 font-bold">REGISTER</a>
                        @endauth
                    @endif
                </div>
            </div>
        </header>
        {{-- END HEADER --}}

        @if(isset($game))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

                {{-- JUDUL GAME --}}
                <h1 class="text-4xl font-bold text-white mb-2">{{ $game->title }}</h1>
                <p class="text-gray-400 mb-6 text-sm">Genre: {{ $game->genre }} | Publisher: {{ $game->publisher }}</p>

                <div class="flex space-x-8">

                    {{-- Kolom Kiri: Gambar Besar dan Tabs --}}
                    <div class="w-3/4">
                        {{-- Gambar Cover/Screenshots Besar --}}
                        <img src="{{ $game->cover_image }}" alt="{{ $game->title }} cover"
                            class="w-full h-auto mb-6 rounded-lg shadow-xl">

                        {{-- TAB BUTTONS --}}
                        <div class="mb-6">
                            <div class="flex border-b border-gray-700 mb-4">
                                <button id="tab-desc-btn"
                                    class="tab-btn p-3 px-6 text-sm font-semibold border-b-2 border-blue-500 text-white transition duration-150">
                                    DESKRIPSI
                                </button>
                                <button id="tab-specs-btn"
                                    class="tab-btn p-3 px-6 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-white transition duration-150">
                                    SPESIFIKASI
                                </button>
                                <button id="tab-reviews-btn"
                                    class="tab-btn p-3 px-6 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-white transition duration-150">
                                    ULASAN (Tiruan)
                                </button>
                            </div>

                            {{-- TAB CONTENT CONTAINERS --}}
                            <div id="tab-content">

                                {{-- KONTEN 1: DESKRIPSI (Default Active) --}}
                                <div id="content-desc" class="tab-page">
                                    <h2 class="text-2xl font-bold mb-3 text-white">Tentang Game Ini</h2>
                                    <p class="text-gray-400 leading-relaxed">{{ $game->description }}</p>
                                </div>

                                {{-- KONTEN 2: SPESIFIKASI (Hidden Awalnya) --}}
                                <div id="content-specs" class="tab-page hidden">
                                    <h2 class="text-2xl font-bold mb-3 text-white">Persyaratan Sistem (Tiruan)</h2>
                                    <ul class="text-gray-400 space-y-2">
                                        <li><span class="font-bold text-white">OS:</span> Windows 10</li>
                                        <li><span class="font-bold text-white">Prosesor:</span> Intel Core i5 / AMD Ryzen 5</li>
                                        <li><span class="font-bold text-white">Memori:</span> 8 GB RAM</li>
                                        <li><span class="font-bold text-white">Penyimpanan:</span> 50 GB Tersedia</li>
                                    </ul>
                                </div>

                                {{-- KONTEN 3: ULASAN (Hidden Awalnya) --}}
                                <div id="content-reviews" class="tab-page hidden">
                                    <h2 class="text-2xl font-bold mb-3 text-white">Ulasan Pengguna (Tiruan)</h2>
                                    <div class="p-4 bg-gray-800 rounded-md mb-3">
                                        <p class="font-semibold text-green-400">Sangat Positif</p>
                                        <p class="text-sm text-gray-400">"Game ini wajib dibeli! Ceritanya luar biasa." -
                                            GamerPro123</p>
                                    </div>
                                    <div class="p-4 bg-gray-800 rounded-md">
                                        <p class="font-semibold text-yellow-400">Campuran</p>
                                        <p class="text-sm text-gray-400">"Ada beberapa bug, tapi intinya seru." - CoderBebas</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Detail Tambahan --}}
                        <div class="grid grid-cols-2 gap-4 text-sm mt-8 border-t border-gray-700 pt-6">
                            <div>
                                <h3 class="font-bold text-white">Tanggal Rilis:</h3>
                                <p class="text-gray-400">{{ \Carbon\Carbon::parse($game->release_date)->format('d F Y') }}</p>
                            </div>
                            <div>
                                <h3 class="font-bold text-white">Publisher:</h3>
                                <p class="text-gray-400">{{ $game->publisher }}</p>
                            </div>
                        </div>

                    </div>

                    {{-- Kolom Kanan: Detail Beli --}}
                    <div class="w-1/4">
                        <div class="bg-gray-800 p-6 rounded-lg h-fit shadow-xl border border-gray-700">
                            <h3 class="text-xl font-bold mb-4 text-white">Beli {{ $game->title }}</h3>

                            {{-- Kalkulasi dan Kotak Harga --}}
                            @php
                                $finalPrice = $game->price;
                                $hasDiscount = $game->discount_percent ?? 0 > 0;

                                if ($hasDiscount) {
                                    $finalPrice = $game->price * (1 - ($game->discount_percent / 100));
                                    $originalPriceFormatted = 'Rp' . number_format($game->price, 0, ',', '.');
                                    $discountBadge = '<span class="bg-green-700 text-white font-bold p-1 text-sm rounded-sm mr-2">-' . $game->discount_percent . '%</span>';
                                } else {
                                    $originalPriceFormatted = '';
                                    $discountBadge = '';
                                }
                            @endphp

                            <div class="flex justify-between items-center bg-gray-700 p-3 rounded-md mb-4">
                                <div class="flex flex-col items-start">
                                    @if ($hasDiscount)
                                        <div class="flex items-center space-x-1">
                                            {!! $discountBadge !!}
                                            <span class="text-xs text-gray-500 line-through">{{ $originalPriceFormatted }}</span>
                                        </div>
                                        <span class="text-2xl font-black text-green-400">
                                            Rp{{ number_format($finalPrice, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-lg text-gray-400">Harga:</span>
                                        <span class="text-2xl font-black text-white">
                                            Rp{{ number_format($finalPrice, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- FORM UNTUK TOMBOL KERANJANG --}}
                            <form action="{{ route('cart.add', $game) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full text-center bg-green-600 hover:bg-green-700 text-white py-3 rounded-sm font-semibold text-lg">
                                    TAMBAH KE KERANJANG
                                </button>
                            </form>
                            {{-- END FORM KERANJANG --}}
                        </div>
                    </div>
                </div>

            </div>
        @else
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <h1 class="text-4xl font-bold text-red-500">Game Tidak Ditemukan (404)</h1>
                <a href="{{ route('store.index') }}" class="text-blue-400 hover:underline mt-4 block">Kembali ke Toko</a>
            </div>
        @endif
    </div>

    {{-- JAVASCRIPT UNTUK TAB INTERAKTIF --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tabButtons = document.querySelectorAll('.tab-btn');
                const tabPages = document.querySelectorAll('.tab-page');

                tabButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const targetId = button.id.replace('-btn', '');

                        // Nonaktifkan semua tombol dan konten
                        tabButtons.forEach(btn => {
                            btn.classList.remove('border-blue-500', 'text-white', 'font-semibold');
                            btn.classList.add('border-transparent', 'text-gray-500');
                        });

                        tabPages.forEach(page => {
                            page.classList.add('hidden');
                        });

                        // Aktifkan tombol yang diklik
                        button.classList.remove('border-transparent', 'text-gray-500');
                        button.classList.add('border-blue-500', 'text-white', 'font-semibold');

                        // Tampilkan konten yang sesuai
                        const targetContent = document.getElementById('content-' + targetId.substring(4)); // Ambil ID yang benar: content-desc
                        if (targetContent) {
                            targetContent.classList.remove('hidden');
                        }
                    });
                });
            });
        </script>
    @endpush

@endsection