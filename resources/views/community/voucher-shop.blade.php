<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Voucher Shop') }}
            </h2>
            <div class="bg-yellow-600 text-white px-4 py-2 rounded-full font-bold flex items-center gap-2">
                <span>🪙</span> {{ number_format($user->kukus_coins) }} Coins
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-[#1b2838] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded shadow-lg font-bold mb-8">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-500 text-white p-4 rounded shadow-lg font-bold mb-8">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($vouchers as $voucher)
                    <div class="bg-[#16202d] rounded-lg shadow-lg overflow-hidden border border-gray-700 hover:border-[#66c0f4] transition relative">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-2xl font-bold text-white">{{ $voucher->name }}</h3>
                                <span class="bg-[#66c0f4] text-[#1b2838] text-xs font-bold px-2 py-1 rounded">
                                    {{ $voucher->type === 'percent' ? $voucher->discount_percent . '%' : 'Rp ' . number_format($voucher->discount_amount) }} OFF
                                </span>
                            </div>
                            <p class="text-gray-400 text-sm mb-6">{{ $voucher->description }}</p>
                            
                            <div class="flex justify-between items-center">
                                <div class="text-yellow-500 font-bold text-xl flex items-center gap-1">
                                    <span>🪙</span> {{ $voucher->cost_in_coins }}
                                </div>
                                
                                <form action="{{ route('voucher.buy', $voucher) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                        class="px-4 py-2 rounded font-bold uppercase text-sm transition
                                        {{ $user->kukus_coins >= $voucher->cost_in_coins ? 'bg-green-600 hover:bg-green-500 text-white' : 'bg-gray-600 text-gray-400 cursor-not-allowed' }}"
                                        {{ $user->kukus_coins < $voucher->cost_in_coins ? 'disabled' : '' }}>
                                        Redeem
                                    </button>
                                </form>

                                @if(Auth::user()->role === 'admin')
                                    <form action="{{ route('admin.voucher.grant', $voucher) }}" method="POST" class="ml-2">
                                        @csrf
                                        <button class="bg-red-900 hover:bg-red-700 text-white px-2 py-2 rounded text-xs font-bold uppercase" title="Admin Test: Get for Free">
                                            Free (Admin)
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Badge if owned --}}
                        @if($user->vouchers->contains($voucher->id))
                            <div class="absolute top-0 right-0 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-bl">
                                OWNED
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
