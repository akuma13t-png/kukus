<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
            <a href="{{ route('admin.history') }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-xs uppercase tracking-wider">
                📜 View History
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-[#1b2838] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Alert Success --}}
            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded shadow-lg font-bold mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- 1. GAME APPROVALS (NEW) --}}
            <div class="bg-[#16202d] overflow-hidden shadow-xl sm:rounded-lg p-6 border-l-4 border-yellow-500">
                <h3 class="text-xl font-black text-white mb-4 uppercase tracking-widest flex items-center gap-2">
                    <span class="text-2xl">🎮</span> Pending Game Approvals
                </h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-black/20">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Game Title</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Publisher</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Genre</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Cover</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700 text-gray-300">
                            @forelse($pendingGames as $game)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-6 py-4 font-bold text-white">{{ $game->title }}</td>
                                    <td class="px-6 py-4">{{ $game->publisher }}</td>
                                    <td class="px-6 py-4">Rp {{ number_format($game->price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4"><span class="bg-gray-700 px-2 py-1 rounded text-xs">{{ $game->genre }}</span></td>
                                    <td class="px-6 py-4">
                                        <img src="{{ $game->cover_image }}" class="h-12 w-8 object-cover rounded border border-gray-600">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex gap-2">
                                            {{-- PERBAIKAN BUG VIEW: Pastikan rute ke game.show benar --}}
                                            <a href="{{ route('game.show', $game) }}" class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-1 rounded text-xs uppercase font-bold" target="_blank">View</a>
                                            
                                            <form action="{{ route('admin.game.approve', $game) }}" method="POST">
                                                @csrf
                                                <button class="bg-green-600 hover:bg-green-500 text-white px-3 py-1 rounded text-xs uppercase font-bold">Approve</button>
                                            </form>
                                            
                                            <form action="{{ route('admin.game.reject', $game) }}" method="POST" onsubmit="return confirm('Tolak game ini? Game akan dihapus permanen dari antrian.');">
                                                @csrf
                                                <button class="bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded text-xs uppercase font-bold">Reject</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">No games waiting for approval.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 2. PUBLISHER REQUESTS (TETAP SAMA) --}}
            <div class="bg-[#16202d] overflow-hidden shadow-xl sm:rounded-lg p-6 border-l-4 border-blue-500">
                <h3 class="text-xl font-black text-white mb-4 uppercase tracking-widest flex items-center gap-2">
                    <span class="text-2xl">👥</span> Publisher Requests
                </h3>
                 <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-black/20">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700 text-gray-300">
                            @forelse($publisherRequests as $user)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-6 py-4 font-bold text-white">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-900 text-yellow-200 border border-yellow-700">Pending</span></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex gap-2">
                                            <form action="{{ route('admin.approvePublisher', $user->id) }}" method="POST">
                                                @csrf
                                                <button class="bg-green-600 hover:bg-green-500 text-white px-3 py-1 rounded text-xs uppercase font-bold">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.rejectPublisher', $user->id) }}" method="POST">
                                                @csrf
                                                <button class="bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded text-xs uppercase font-bold">Reject</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">No pending publisher requests.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 3. REFUND REQUESTS (TETAP SAMA) --}}
            <div class="bg-[#16202d] overflow-hidden shadow-xl sm:rounded-lg p-6 border-l-4 border-purple-500">
                <h3 class="text-xl font-black text-white mb-4 uppercase tracking-widest flex items-center gap-2">
                    <span class="text-2xl">💸</span> Refund Requests
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-black/20">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Game</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Reason</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700 text-gray-300">
                            @forelse($refunds as $refund)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-white">{{ $refund->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $refund->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">{{ $refund->game->title }}</td>
                                    <td class="px-6 py-4 text-sm italic">"{{ $refund->reason }}"</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex gap-2">
                                            <form action="{{ route('admin.refund.approve', $refund->id) }}" method="POST" onsubmit="return confirm('Setujui refund?');">
                                                @csrf
                                                <button class="bg-green-600 hover:bg-green-500 text-white px-3 py-1 rounded text-xs uppercase font-bold">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.refund.reject', $refund->id) }}" method="POST" onsubmit="return confirm('Tolak refund?');">
                                                @csrf
                                                <button class="bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded text-xs uppercase font-bold">Reject</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">No refund requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>