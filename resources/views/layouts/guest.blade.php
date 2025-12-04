<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SteamClone') }}</title>

    {{-- Fonts & Scripts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-[#1b2838] text-gray-200 flex flex-col min-h-screen">

    {{-- 1. HEADER --}}
    <header class="bg-[#171a21] border-b-4 border-black shadow-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex justify-between items-center gap-4">

            {{-- KIRI: LOGO & NAVIGASI (TETAP SAMA) --}}
            <div class="flex items-center space-x-6 flex-shrink-0">
                <a href="{{ route('store.index') }}" class="flex items-center gap-2 group">
                    <img src="{{ asset('logo.png') }}" alt="Logo"
                        class="h-10 w-auto object-contain drop-shadow-md hover:scale-105 transition">
                    <h1 class="text-2xl font-black tracking-widest uppercase hidden md:block">
                        <span class="text-white">STEAM</span>
                        <span class="text-gray-500 group-hover:text-white transition"
                            style="-webkit-text-stroke: 0px;">CLONE</span>
                    </h1>
                </a>

                <nav class="hidden lg:flex space-x-1 ml-4">
                    <a href="{{ route('store.index') }}"
                        class="px-3 py-2 font-bold text-blue-400 hover:text-white transition uppercase text-sm">Store</a>
                    <a href="{{ route('library.index') }}"
                        class="px-3 py-2 font-bold text-gray-400 hover:text-white transition uppercase text-sm">Library</a>
                    <a href="{{ route('dashboard') }}"
                        class="px-3 py-2 font-bold text-gray-400 hover:text-white transition uppercase text-sm">Community</a>
                </nav>
            </div>

            {{-- TENGAH: SEARCH BAR (TETAP SAMA) --}}
            <div class="flex-grow max-w-md mx-4 hidden md:block">
                <form action="{{ route('games.search') }}" method="GET" class="relative group">
                    <input type="text" name="q" placeholder="search" value="{{ request('q') }}"
                        class="w-full bg-[#316282] text-black placeholder-gray-900 font-bold border-2 border-transparent focus:border-white rounded-sm py-1 px-3 shadow-inner focus:bg-white transition-all outline-none italic hover:bg-[#4c84a5]">
                    <button type="submit"
                        class="absolute right-1 top-1/2 transform -translate-y-1/2 p-1 text-gray-900 hover:text-blue-600">
                        🔍
                    </button>
                </form>
            </div>

            {{-- KANAN: USER / AUTH (DI UPDATE DENGAN CART & KUKUS MONEY) --}}
            <div class="flex items-center space-x-3 flex-shrink-0">
                
                @auth
                    {{-- TOMBOL TOPUP KUKUS MONEY (BARU) --}}
                    <a href="{{ route('kukus.topup.create') }}" class="bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded-[2px] text-xs font-bold uppercase tracking-wider shadow-lg flex items-center gap-1 transition">
                        <span class="text-xs font-black">Rp</span> Top Up
                    </a>
                    
                    {{-- INFO KUKUS MONEY & USER --}}
                    <div class="text-right hidden sm:block leading-tight mr-2">
                        <div class="text-[#66c0f4] font-bold text-sm truncate max-w-[150px]">{{ Auth::user()->name }}</div>
                        {{-- Tampilkan Kukus Money --}}
                        <div class="text-[10px] text-gray-400 font-mono">
                            <span class="text-red-400 font-black">Kukus Money:</span> 
                            Rp {{ number_format(Auth::user()->kukus_money_balance ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                @endauth
                
                {{-- === TOMBOL CART BARU DI SINI (SEBELAH KIRI PROFILE) === --}}
                <a href="{{ route('cart.index') }}" class="relative group mr-2 p-1 rounded hover:bg-[#212c3d] transition">
                    <div class="bg-[#5c7e10] hover:bg-[#76a113] text-white px-3 py-1 rounded-[2px] text-xs font-bold uppercase tracking-wider shadow-lg flex items-center gap-2">
                        <span>Cart</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        @php $cartCount = count(session('cart', [])); @endphp
                        @if($cartCount > 0)
                            <span class="bg-black text-[#66c0f4] px-1.5 py-0.5 rounded text-[10px]">{{ $cartCount }}</span>
                        @endif
                    </div>
                </a>
                {{-- === END TOMBOL CART === --}}

                @auth
                    {{-- 2. Profile Picture & Dropdown (Sisa dari guest.blade.php) --}}
                    <div class="relative" x-data="{ open: false }">
                        {{-- Tombol Gambar Profile --}}
                        <button @click="open = !open" class="flex items-center gap-2 focus:outline-none group">
                            <div class="w-9 h-9 p-[2px] bg-gradient-to-b from-[#5c5c5c] to-[#2d2d2d] group-hover:from-[#66c0f4] group-hover:to-[#2a475e] rounded-[2px] transition duration-300">
                                <img src="{{ Auth::user()->profile_photo_url }}" 
                                     alt="{{ Auth::user()->name }}" 
                                     class="w-full h-full object-cover rounded-[1px] bg-[#171a21]">
                            </div>
                            <svg class="w-3 h-3 text-gray-500 group-hover:text-white transition" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="open" 
                             @click.outside="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-[#3d4450] text-[#c5c3c0] text-xs shadow-[0_0_15px_rgba(0,0,0,0.5)] border border-[#171a21] z-50 origin-top-right"
                             style="display: none;">
                            
                            <div class="px-4 py-3 bg-[#171a21] border-b border-gray-600">
                                <div class="uppercase tracking-widest text-[10px] font-bold text-gray-500 mb-1">Signed in as</div>
                                <div class="font-bold text-white truncate">{{ Auth::user()->email }}</div>
                            </div>
                            
                            <a href="{{ route('kukus.topup.create') }}" class="block px-4 py-2 hover:bg-[#dcdedf] hover:text-[#171a21] transition font-bold text-red-400">
                                + Top Up Kukus Money
                            </a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-[#dcdedf] hover:text-[#171a21] transition font-bold text-white">Edit Profile / Avatar</a>
                            <a href="{{ route('library.index') }}" class="block px-4 py-2 hover:bg-[#dcdedf] hover:text-[#171a21] transition">My Games</a>
                            
                            @if(Auth::user()->role === 'admin')
                                <div class="border-t border-gray-600 my-1"></div>
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-red-400 hover:bg-red-900 hover:text-white font-bold transition">⚠️ Admin Panel</a>
                            @endif

                            <div class="border-t border-gray-600 my-1"></div>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-[#dcdedf] hover:text-[#171a21] transition text-white">Sign out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-bold text-white hover:text-blue-400 uppercase">Login</a>
                    <span class="text-gray-600">|</span>
                    <a href="{{ route('register') }}" class="text-xs font-bold text-white hover:text-blue-400 uppercase">Register</a>
                    <a href="{{ route('login') }}" class="bg-[#5c7e10] hover:bg-[#76a113] text-white font-xs px-3 py-1 rounded-[2px] flex items-center gap-2 transition ml-2 shadow-lg">
                        <span class="font-bold">Install Steam</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- 2. KONTEN UTAMA --}}
    <main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @isset($slot)
            {{ $slot }}
        @else
            @yield('content')
        @endif
    </main>

    {{-- FOOTER (TETAP SAMA) --}}
    <footer class="bg-[#171a21] text-[#8f98a0] py-12 mt-auto border-t-4 border-black shadow-[0_-10px_40px_rgba(0,0,0,0.5)] relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start gap-8 mb-8 border-b border-gray-700 pb-8">
                <div class="max-w-sm">
                    <div class="flex items-center gap-2 mb-4 opacity-80 hover:opacity-100 transition">
                        <img src="{{ asset('Logo.png') }}" alt="Logo" class="h-8 grayscale">
                        <span class="text-xl font-bold text-white tracking-widest">STEAMCLONE</span>
                    </div>
                    <p class="text-xs leading-relaxed">
                        © 2025 SteamClone Corporation. <br>
                        Tugas Kelompok Pemrograman Web.
                    </p>
                </div>
                <div class="flex gap-12 text-sm">
                    <div>
                        <h5 class="font-bold text-white mb-3 uppercase text-xs tracking-wider">Perusahaan</h5>
                        <ul class="space-y-2">
                            <li><a href="{{ route('pages.about') }}" class="hover:text-white hover:underline">Tentang Kami</a></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-bold text-white mb-3 uppercase text-xs tracking-wider">Bantuan</h5>
                        <ul class="space-y-2">
                            <li><a href="{{ route('pages.stats') }}" class="hover:text-white hover:underline">System Status</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row justify-between items-center text-xs font-bold gap-4">
                <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                    <a href="{{ route('pages.privacy') }}" class="hover:text-white">Kebijakan Privasi</a>
                    <span class="text-gray-600">|</span>
                    <a href="{{ route('pages.terms') }}" class="hover:text-white">Perjanjian Pelanggan</a>
                    <span class="text-gray-600">|</span>
                    <a href="{{ route('refunds.create') }}" class="hover:text-white text-[#66c0f4]">Refunds</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')

        @livewireScripts
        
        {{-- AFK / Zen Mode Overlay --}}
        <x-afk-overlay />
    </body>
</html>