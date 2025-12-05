<header class="bg-[#2f2e2e] border-b-4 border-black shadow-xl sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex justify-between items-center gap-4">

        {{-- KIRI: LOGO & NAVIGASI --}}
        <div class="flex items-center space-x-6 flex-shrink-0">
            <a href="{{ route('store.index') }}" class="flex items-center gap-2 group">
                <img src="{{ asset('logo.png') }}" alt="Logo"
                    class="h-10 w-auto object-contain drop-shadow-md hover:scale-105 transition">
                <h1 class="text-2xl font-black tracking-widest uppercase hidden md:block">
                    <span class="text-white">KU</span>
                    <span class="text-gray-500 group-hover:text-white transition"
                        style="-webkit-text-stroke: 0px;">KUS</span>
                </h1>
            </a>

            <nav class="hidden lg:flex space-x-1 ml-4">
                <a href="{{ route('store.index') }}"
                    class="px-3 py-2 font-bold text-gray-400 hover:text-white transition uppercase text-sm">Store</a>
                <a href="{{ route('library.index') }}"
                    class="px-3 py-2 font-bold text-gray-400 hover:text-white transition uppercase text-sm">Library</a>
                <a href="{{ route('daily.challenge') }}"
                    class="px-3 py-2 font-bold text-gray-400 hover:text-white transition uppercase text-sm">Daily Challenge</a>
            </nav>
        </div>

        {{-- TENGAH: SEARCH BAR --}}
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
                {{-- TOMBOL TOPUP KUKUS MONEY --}}
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
                    {{-- Tampilkan Kukus Coins --}}
                    <div class="text-[10px] text-yellow-500 font-mono">
                        🪙 {{ number_format(Auth::user()->kukus_coins ?? 0) }} Coins
                    </div>
                </div>
            @endauth
            
            {{-- TOMBOL CART --}}
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

            @auth
                {{-- Profile Picture & Dropdown --}}
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
                        <a href="{{ route('daily.challenge') }}" class="block px-4 py-2 hover:bg-[#dcdedf] hover:text-[#171a21] transition">Daily Challenge</a>
                        
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