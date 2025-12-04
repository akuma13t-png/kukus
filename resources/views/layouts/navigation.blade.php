<nav x-data="{ open: false }" class="bg-[#171a21] border-b border-black text-white font-sans shadow-2xl relative z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-24"> 
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center mr-8">
                    <a href="{{ route('store.index') }}" class="group">
                        <h1 class="text-3xl font-black uppercase tracking-[0.15em] text-[#c5c3c0] group-hover:text-white transition duration-300 drop-shadow-md transform group-hover:scale-105">
                            STEAM<span class="text-[#66c0f4]">CLONE</span>
                        </h1>
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden sm:flex space-x-2 h-full items-center">
                    <x-nav-link :href="route('store.index')" :active="request()->routeIs('store.index')" 
                        class="text-[#c5c3c0] hover:text-[#66c0f4] text-sm font-bold uppercase tracking-wider px-4 py-2 rounded-sm hover:bg-white/5 transition-all border-b-0 h-auto leading-normal">
                        {{ __('Store') }}
                    </x-nav-link>

                    <x-nav-link :href="route('library.index')" :active="request()->routeIs('library.index')" 
                        class="text-[#c5c3c0] hover:text-[#66c0f4] text-sm font-bold uppercase tracking-wider px-4 py-2 rounded-sm hover:bg-white/5 transition-all border-b-0 h-auto leading-normal">
                        {{ __('Library') }}
                    </x-nav-link>

                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                        class="text-[#c5c3c0] hover:text-[#66c0f4] text-sm font-bold uppercase tracking-wider px-4 py-2 rounded-sm hover:bg-white/5 transition-all border-b-0 h-auto leading-normal">
                        {{ __('Community') }}
                    </x-nav-link>

                    @auth
                        @if(auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" 
                                class="text-red-400 hover:text-red-300 text-sm font-black uppercase tracking-wider px-4 py-2 rounded-sm hover:bg-red-900/20 border border-red-900/50 hover:border-red-500 transition-all border-b-0 h-auto leading-normal ml-4">
                                {{ __('⚠️ Admin') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    {{-- TAMPILAN JIKA LOGIN --}}
                    <x-dropdown align="right" width="60">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-3 group focus:outline-none">
                                <div class="text-right hidden md:block leading-tight">
                                    <div class="text-[#66c0f4] font-bold text-sm group-hover:text-white transition duration-200">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-gray-400 group-hover:text-gray-300 font-mono">Rp 0</div> 
                                </div>
                                <div class="relative">
                                    <div class="w-10 h-10 p-[2px] bg-gradient-to-b from-[#5c5c5c] to-[#2d2d2d] group-hover:from-[#66c0f4] group-hover:to-[#2a475e] rounded-[2px] transition duration-300">
                                        <img class="w-full h-full object-cover rounded-[1px]" 
                                             src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=1b2838&color=66c0f4&bold=true" 
                                             alt="{{ Auth::user()->name }}" />
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-[#66c0f4] border-2 border-[#171a21] rounded-full shadow-sm"></div>
                                </div>
                                <svg class="fill-current h-3 w-3 text-gray-500 group-hover:text-white ml-1 transition" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="bg-[#3d4450] text-[#c5c3c0] text-xs shadow-[0_0_15px_rgba(0,0,0,0.5)] border border-[#171a21]">
                                <div class="px-4 py-3 bg-[#171a21] border-b border-gray-700">
                                    <div class="uppercase tracking-widest text-[10px] font-bold text-gray-500 mb-1">Signed in as</div>
                                    <div class="font-bold text-white text-sm truncate">{{ Auth::user()->email }}</div>
                                </div>
                                <div class="py-1">
                                    <x-dropdown-link :href="route('profile.edit')" class="hover:bg-[#dcdedf] hover:text-[#171a21] px-4 py-2 block text-left transition-colors">{{ __('View Profile') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('dashboard')" class="hover:bg-[#dcdedf] hover:text-[#171a21] px-4 py-2 block text-left transition-colors">{{ __('Account Details') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('library.index')" class="hover:bg-[#dcdedf] hover:text-[#171a21] px-4 py-2 block text-left transition-colors">{{ __('My Games') }}</x-dropdown-link>
                                </div>
                                <div class="border-t border-[#171a21]"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="hover:bg-[#dcdedf] hover:text-[#171a21] px-4 py-2 block text-left transition-colors text-red-300">{{ __('Sign out') }}</x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @else
                    {{-- TAMPILAN JIKA BELUM LOGIN (GUEST) --}}
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-[#c5c3c0] hover:text-white font-bold text-xs uppercase tracking-wider transition">Login</a>
                        <span class="text-gray-600">|</span>
                        <a href="{{ route('register') }}" class="text-[#c5c3c0] hover:text-white font-bold text-xs uppercase tracking-wider transition">Register</a>
                        <a href="{{ route('login') }}" class="bg-[#5c7e10] hover:bg-[#76a113] text-white font-xs px-3 py-1 rounded-[2px] flex items-center gap-2 transition ml-2 shadow-lg">
                            <span class="font-bold">Install Steam</span>
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#171a21] border-t border-gray-700 absolute w-full shadow-xl">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('store.index')" :active="request()->routeIs('store.index')" class="text-gray-300 hover:bg-[#2a475e] hover:text-white">{{ __('Store') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('library.index')" :active="request()->routeIs('library.index')" class="text-gray-300 hover:bg-[#2a475e] hover:text-white">{{ __('Library') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-300 hover:bg-[#2a475e] hover:text-white">{{ __('Community') }}</x-responsive-nav-link>
        </div>
        
        @auth
        <div class="pt-4 pb-1 border-t border-gray-700 bg-[#212429]">
            <div class="px-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-600 rounded"><img class="w-full h-full object-cover rounded" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2a475e&color=66c0f4" /></div>
                <div><div class="font-medium text-base text-[#66c0f4]">{{ Auth::user()->name }}</div><div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div></div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-300 hover:bg-[#2a475e] hover:text-white">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">@csrf<x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-400 hover:bg-[#2a475e] hover:text-white">{{ __('Log Out') }}</x-responsive-nav-link></form>
            </div>
        </div>
        @else
        <div class="pt-4 pb-1 border-t border-gray-700">
            <x-responsive-nav-link :href="route('login')" class="text-gray-300 hover:bg-[#2a475e] hover:text-white">Login</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('register')" class="text-gray-300 hover:bg-[#2a475e] hover:text-white">Register</x-responsive-nav-link>
        </div>
        @endauth
    </div>
</nav>