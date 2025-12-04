<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#1b2838] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- STATUS / REQUEST PUBLISHER CARD --}}
            <div class="bg-[#16202d] overflow-hidden shadow-sm sm:rounded-lg border border-gray-700 p-6 text-white">
                <h3 class="text-xl font-bold mb-4 text-[#66c0f4]">Account Status</h3>
                
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 mb-2">Current Role: <span class="text-white font-bold uppercase">{{ Auth::user()->role }}</span></p>
                        
                        @if(Auth::user()->role === 'user')
                            @if(Auth::user()->publisher_request_status === 'pending')
                                <span class="bg-yellow-600 text-white px-3 py-1 rounded text-sm font-bold">⏳ Publisher Request Pending Approval</span>
                            @elseif(Auth::user()->publisher_request_status === 'rejected')
                                <span class="bg-red-600 text-white px-3 py-1 rounded text-sm font-bold">❌ Publisher Request Rejected</span>
                            @else
                                <p class="text-sm text-gray-500">Want to publish your own games? Become a verified publisher.</p>
                            @endif
                        @elseif(Auth::user()->role === 'publisher')
                            <span class="bg-green-600 text-white px-3 py-1 rounded text-sm font-bold">✅ Verified Publisher Account</span>
                        @elseif(Auth::user()->role === 'admin')
                            <div class="flex gap-4">
                                <span class="bg-red-600 text-white px-3 py-1 rounded text-sm font-bold">🛡️ Administrator</span>
                                <a href="{{ route('admin.dashboard') }}" class="text-[#66c0f4] underline font-bold hover:text-white">Go to Admin Panel &rarr;</a>
                            </div>
                        @endif
                    </div>

                    {{-- Tombol Request --}}
                    @if(Auth::user()->role === 'user' && Auth::user()->publisher_request_status !== 'pending')
                        <form action="{{ route('user.request_publisher') }}" method="POST">
                            @csrf
                            <button class="bg-[#66c0f4] hover:bg-[#419ec0] text-black font-bold px-6 py-3 rounded shadow-lg uppercase text-xs tracking-widest transition">
                                Request to be Publisher
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }} <a href="{{ route('store.index') }}" class="text-blue-600 underline ml-2">Go to Store</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>