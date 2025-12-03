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

{{-- LAYOUT: Flex Column agar footer selalu di bawah --}}

<body class="font-sans antialiased bg-[#1b2838] text-gray-200 flex flex-col min-h-screen">

    {{-- 1. HEADER --}}
    <header class="bg-[#171a21] border-b-4 border-black shadow-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex justify-between items-center gap-4">

            {{-- KIRI: LOGO & NAVIGASI --}}
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
                    <a href="#"
                        class="px-3 py-2 font-bold text-gray-400 hover:text-white transition uppercase text-sm">Community</a>
                </nav>
            </div>

            {{-- TENGAH: SEARCH BAR (BARU) --}}
            {{-- Mengarah ke route 'games.search' yang sudah kita buat sebelumnya --}}
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

            {{-- KANAN: USER / AUTH --}}
            <div class="flex items-center space-x-3 flex-shrink-0">
                @auth
                    <div class="text-right hidden sm:block leading-tight">
                        <span class="block text-[10px] text-gray-400 uppercase">Account</span>
                        <span
                            class="block text-sm font-bold text-blue-400 truncate max-w-[100px]">{{ Auth::user()->name }}</span>
                    </div>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="bg-gray-700 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-bold transition">LOGOUT</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-bold text-white hover:text-blue-400">Login</a>
                    <span class="text-gray-600">|</span>
                    <a href="{{ route('register') }}" class="text-xs font-bold text-white hover:text-blue-400">Register</a>
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

    {{-- 3. FOOTER (DARI KODE LAMA ANDA) --}}
    <footer
        class="bg-[#171a21] text-[#8f98a0] py-12 mt-auto border-t-4 border-black shadow-[0_-10px_40px_rgba(0,0,0,0.5)] relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Bagian Atas Footer: Logo & Link --}}
            <div class="flex flex-col md:flex-row justify-between items-start gap-8 mb-8 border-b border-gray-700 pb-8">

                {{-- Branding --}}
                <div class="max-w-sm">
                    <div class="flex items-center gap-2 mb-4 opacity-80 hover:opacity-100 transition">
                        <img src="{{ asset('logo.png') }}" alt="Logo" class="h-8 grayscale">
                        <span class="text-xl font-bold text-white tracking-widest">STEAMCLONE</span>
                    </div>
                    <p class="text-xs leading-relaxed">
                        © 2025 SteamClone Corporation. Semua hak dilindungi undang-undang. Semua merek dagang adalah
                        milik dari pemiliknya masing-masing di AS dan negara lain.
                    </p>
                </div>

                {{-- Link Navigasi --}}
                <div class="flex gap-12 text-sm">
                    <div>
                        <h5 class="font-bold text-white mb-3 uppercase text-xs tracking-wider">Perusahaan</h5>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:text-white hover:underline">Tentang Kami</a></li>
                            <li><a href="#" class="hover:text-white hover:underline">Karir</a></li>
                            <li><a href="#" class="hover:text-white hover:underline">Kontak</a></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-bold text-white mb-3 uppercase text-xs tracking-wider">Bantuan</h5>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:text-white hover:underline">Dukungan</a></li>
                            <li><a href="#" class="hover:text-white hover:underline">Forum</a></li>
                            <li><a href="#" class="hover:text-white hover:underline">Stats</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Bagian Bawah: Legal & Sosmed --}}
            <div class="flex flex-col md:flex-row justify-between items-center text-xs font-bold gap-4">
                <div class="flex gap-4">
                    <a href="#" class="hover:text-white">Kebijakan Privasi</a>
                    <span class="text-gray-600">|</span>
                    <a href="#" class="hover:text-white">Hukum</a>
                    <span class="text-gray-600">|</span>
                    <a href="#" class="hover:text-white">Perjanjian Pelanggan</a>
                    <span class="text-gray-600">|</span>
                    <a href="#" class="hover:text-white">Refunds</a>
                </div>

                <div class="flex gap-3 opacity-60">
                    <span class="hover:text-white cursor-pointer transition">Facebook</span>
                    <span class="hover:text-white cursor-pointer transition">Twitter</span>
                    <span class="hover:text-white cursor-pointer transition">YouTube</span>
                </div>
            </div>

        </div>
    </footer>

    @stack('scripts')
</body>

</html>