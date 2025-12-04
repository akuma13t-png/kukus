<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#1b2838] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- TOMBOL KEMBALI --}}
            <a href="{{ route('store.index') }}" class="text-gray-400 hover:text-white text-sm mb-4 inline-block">&larr; Back to Store</a>

            {{-- 1. BAGIAN PUBLISHER / CREATOR DASHBOARD (UPDATE) --}}
            @if(Auth::user()->role === 'user')
                {{-- User Biasa: Kotak Request Publisher --}}
                <div class="p-6 bg-[#16202d] border border-[#66c0f4] shadow-lg rounded-lg">
                    <h3 class="text-lg font-bold text-[#66c0f4] mb-2 uppercase tracking-wide">Become a Creator</h3>
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <p class="text-gray-300 text-sm">Want to publish your own games on SteamClone? Apply for a publisher account today.</p>
                        
                        @if(Auth::user()->publisher_request_status === 'pending')
                            <span class="bg-yellow-600 text-white px-4 py-2 rounded text-xs font-bold uppercase tracking-wider">
                                ⏳ Request Pending
                            </span>
                        @elseif(Auth::user()->publisher_request_status === 'rejected')
                            <div class="text-right">
                                <span class="bg-red-600 text-white px-4 py-2 rounded text-xs font-bold uppercase tracking-wider block mb-1">
                                    ❌ Request Rejected
                                </span>
                                <p class="text-[10px] text-gray-500">Contact admin for details.</p>
                            </div>
                        @else
                            <form action="{{ route('user.request_publisher') }}" method="POST">
                                @csrf
                                <button class="bg-gradient-to-r from-[#66c0f4] to-[#419ec0] hover:brightness-110 text-black font-black px-6 py-2 rounded-sm shadow-md uppercase text-xs tracking-widest transition transform hover:scale-105">
                                    Request Publisher Access
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

            @elseif(Auth::user()->role === 'publisher' || Auth::user()->role === 'admin')
                {{-- PUBLISHER/ADMIN: Kotak Dashboard Publisher --}}
                <div class="p-8 bg-gradient-to-r from-[#16202d] to-[#1e2a3a] border-t-4 border-green-500 shadow-2xl relative overflow-hidden">
                    {{-- Background Decoration --}}
                    <div class="absolute top-0 right-0 opacity-10 pointer-events-none">
                        <svg width="200" height="200" viewBox="0 0 24 24" fill="currentColor" class="text-white transform rotate-12 translate-x-10 -translate-y-10"><path d="M21 6H3c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-10 7H8v3H6v-3H3v-2h3V8h2v3h3v2zm4.5 2c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm4-3c-.83 0-1.5-.67-1.5-1.5S18.67 9 19.5 9s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>
                    </div>

                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                        <div>
                            <h3 class="text-2xl font-black text-white mb-1 uppercase tracking-widest">
                                @if(Auth::user()->role === 'admin') Admin / @endif Publisher Dashboard
                            </h3>
                            <p class="text-green-400 font-bold text-sm">✅ Verified Account: {{ Auth::user()->name }}</p>
                            <p class="text-gray-400 text-xs mt-2 max-w-md">Manage your uploaded content and check approval status.</p>
                        </div>
                        
                        {{-- TOMBOL DASHBOARD & UPLOAD --}}
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('publisher.dashboard') }}" class="group relative inline-flex items-center justify-center px-6 py-3 font-black text-white transition-all duration-200 bg-blue-600 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 rounded shadow-lg hover:-translate-y-1 text-sm uppercase tracking-widest">
                                <span>Manage Games</span>
                            </a>
                            <a href="{{ route('games.create') }}" class="group relative inline-flex items-center justify-center px-6 py-3 font-black text-white transition-all duration-200 bg-green-600 hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600 rounded shadow-lg hover:-translate-y-1 text-sm uppercase tracking-widest">
                                <span class="mr-2 text-xl">+</span> Upload Game
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- 2. FORM EDIT PROFILE (Existing) --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-700">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- 3. UPDATE PASSWORD (Existing) --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-700">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- 4. DELETE ACCOUNT (Existing) --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-700">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            {{-- 5. ADMIN PANEL SHORTCUT (Existing) --}}
            @if(Auth::user()->role === 'admin')
                <div class="mt-12 border-t-4 border-red-900 pt-8 text-center">
                    <h3 class="text-red-500 font-black text-2xl uppercase mb-4">⚠️ Administrator Zone</h3>
                    <div class="flex justify-center gap-4">
                        <a href="{{ route('admin.dashboard') }}" class="inline-block bg-red-700 hover:bg-red-600 text-white font-bold py-4 px-8 rounded shadow-lg uppercase tracking-widest transition transform hover:scale-105">
                            Access Admin Panel
                        </a>
                        <a href="{{ route('admin.history') }}" class="inline-block bg-gray-700 hover:bg-gray-600 text-white font-bold py-4 px-8 rounded shadow-lg uppercase tracking-widest transition transform hover:scale-105">
                            View Audit History
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>