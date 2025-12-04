<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Daily Challenge') }}
            </h2>
            <div class="flex items-center gap-4">
                <div class="bg-yellow-600 text-white px-4 py-2 rounded-full font-bold flex items-center gap-2">
                    <span>🪙</span> {{ number_format($user->kukus_coins) }} Coins
                </div>
                <a href="{{ route('voucher.shop') }}" class="bg-[#66c0f4] hover:bg-[#1999d6] text-white px-4 py-2 rounded font-bold transition">
                    Voucher Shop
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-[#1b2838] min-h-screen text-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-[#16202d] overflow-hidden shadow-xl sm:rounded-lg p-8 border-t-4 border-yellow-500">
                
                @if($completion)
                    <div class="text-center py-12">
                        <h3 class="text-3xl font-black text-green-400 mb-4">Challenge Completed!</h3>
                        <p class="text-gray-300 text-lg mb-8">You have already earned your coins for today. Come back tomorrow!</p>
                        <div class="text-6xl mb-4">🎉</div>
                        <p class="text-yellow-500 font-bold text-xl">+{{ $completion->coins_earned }} Coins Earned</p>
                    </div>
                @else
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold mb-2">Today's Game: <span class="text-[#66c0f4] uppercase">{{ $currentGame }}</span></h3>
                        <p class="text-gray-400">Win this game to earn <span class="text-yellow-500 font-bold">100 Kukus Coins</span>!</p>
                    </div>

                    {{-- GAME CONTAINER --}}
                    <div id="game-area" class="flex justify-center">
                        @if($currentGame === 'tictactoe')
                            @include('community.games.tictactoe')
                        @elseif($currentGame === 'rps')
                            @include('community.games.rps')
                        @elseif($currentGame === 'memory')
                            @include('community.games.memory')
                        @endif
                    </div>
                @endif

            </div>

            {{-- ADMIN CONTROL PANEL --}}
            @if(Auth::user()->role === 'admin')
                <div class="mt-8 bg-red-900/50 border border-red-500 p-6 rounded-lg">
                    <h3 class="text-xl font-bold text-red-300 mb-4">⚠️ Admin Test Controls</h3>
                    <p class="text-sm text-gray-300 mb-4">Force switch today's game for testing purposes.</p>
                    
                    <div class="flex gap-4">
                        <form action="{{ route('admin.daily.setGame') }}" method="POST">
                            @csrf <input type="hidden" name="game" value="tictactoe">
                            <button class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded text-xs font-bold uppercase">Force Tic-Tac-Toe</button>
                        </form>
                        <form action="{{ route('admin.daily.setGame') }}" method="POST">
                            @csrf <input type="hidden" name="game" value="rps">
                            <button class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded text-xs font-bold uppercase">Force RPS</button>
                        </form>
                        <form action="{{ route('admin.daily.setGame') }}" method="POST">
                            @csrf <input type="hidden" name="game" value="memory">
                            <button class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded text-xs font-bold uppercase">Force Memory</button>
                        </form>
                        <form action="{{ route('admin.daily.setGame') }}" method="POST">
                            @csrf <input type="hidden" name="game" value="reset">
                            <button class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded text-xs font-bold uppercase">Reset to Auto</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function claimReward(gameType) {
            fetch("{{ route('daily.complete') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    game_type: gameType,
                    result: 'win'
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.message) {
                    alert(data.message);
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</x-app-layout>
