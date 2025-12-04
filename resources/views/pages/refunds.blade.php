<x-app-layout>
    <div class="py-12 bg-[#1b2838] min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="bg-green-600 text-white p-4 rounded mb-6 border border-green-400 shadow-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-600 text-white p-4 rounded mb-6 border border-red-400 shadow-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-[#171a21] border border-black shadow-2xl p-8">
                <h1 class="text-3xl font-black text-white mb-2 uppercase tracking-wide">SteamClone Refunds</h1>
                <p class="text-gray-400 text-sm mb-8">
                    Tidak puas dengan pembelian Anda? Ajukan permintaan refund di bawah ini. Permintaan akan ditinjau oleh Admin.
                </p>

                <form action="{{ route('refunds.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    {{-- Select Game --}}
                    <div>
                        <label class="block text-[#66c0f4] text-sm font-bold uppercase tracking-wider mb-2">Pilih Game untuk di-Refund</label>
                        <select name="game_id" class="w-full bg-[#2a3f5a] text-white border border-[#4c84a5] rounded-sm p-2 focus:ring-0 focus:border-white h-12">
                            @forelse($games as $game)
                                <option value="{{ $game->id }}">{{ $game->title }} (Rp {{ number_format($game->price, 0, ',', '.') }})</option>
                            @empty
                                <option disabled>Anda tidak memiliki game di library.</option>
                            @endforelse
                        </select>
                    </div>

                    {{-- Reason --}}
                    <div>
                        <label class="block text-[#66c0f4] text-sm font-bold uppercase tracking-wider mb-2">Alasan Refund</label>
                        <textarea name="reason" rows="4" class="w-full bg-[#2a3f5a] text-white border border-[#4c84a5] rounded-sm p-3 focus:ring-0 focus:border-white" placeholder="Jelaskan mengapa Anda ingin mengembalikan game ini..."></textarea>
                    </div>

                    <div class="pt-4 border-t border-gray-700">
                        <button type="submit" class="bg-gradient-to-r from-[#66c0f4] to-[#419ec0] hover:brightness-110 text-white font-bold py-3 px-6 rounded-sm shadow-md w-full uppercase tracking-widest transition">
                            Kirim Permintaan Refund
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="mt-8 text-center">
                <a href="{{ route('store.index') }}" class="text-gray-500 hover:text-white text-xs">Kembali ke Store</a>
            </div>
        </div>
    </div>
</x-app-layout>