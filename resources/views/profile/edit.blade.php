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

            {{-- 1. BAGIAN REQUEST PUBLISHER (KHUSUS USER) --}}
            @if(Auth::user()->role === 'user')
                <div class="p-6 bg-[#16202d] border border-[#66c0f4] shadow-lg rounded-lg">
                    <h3 class="text-lg font-bold text-[#66c0f4] mb-2 uppercase tracking-wide">Become a Creator</h3>
                    <div class="flex justify-between items-center">
                        <p class="text-gray-300 text-sm">Want to publish your own games on SteamClone? Apply for a publisher account today.</p>
                        
                        @if(Auth::user()->publisher_request_status === 'pending')
                            <span class="bg-yellow-600 text-white px-4 py-2 rounded text-xs font-bold uppercase tracking-wider">
                                ⏳ Request Pending
                            </span>
                        @elseif(Auth::user()->publisher_request_status === 'rejected')
                            <span class="bg-red-600 text-white px-4 py-2 rounded text-xs font-bold uppercase tracking-wider">
                                ❌ Request Rejected
                            </span>
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
            @elseif(Auth::user()->role === 'publisher')
                <div class="p-4 bg-green-900/30 border border-green-500 rounded text-green-400 text-sm font-bold text-center">
                    ✅ You are a verified Publisher. Access your game dashboard to publish titles.
                </div>
            @endif

            {{-- 2. FORM EDIT PROFILE (TERMASUK GAMBAR) --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-700">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- 3. UPDATE PASSWORD --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-700">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- 4. DELETE ACCOUNT --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-700">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            {{-- 5. ADMIN PANEL SHORTCUT (KHUSUS ADMIN) - DI PALING BAWAH --}}
            @if(Auth::user()->role === 'admin')
                <div class="mt-12 border-t-4 border-red-900 pt-8 text-center">
                    <h3 class="text-red-500 font-black text-2xl uppercase mb-4">⚠️ Administrator Zone</h3>
                    <a href="{{ route('admin.dashboard') }}" class="inline-block bg-red-700 hover:bg-red-600 text-white font-bold py-4 px-12 rounded shadow-[0_0_20px_rgba(220,38,38,0.5)] uppercase tracking-widest transition transform hover:scale-105">
                        Access Admin Panel
                    </a>
                    <p class="text-gray-500 text-xs mt-4">Restricted access. Only for authorized personnel.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>