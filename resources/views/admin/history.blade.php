<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Admin History & Logs') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-xs uppercase tracking-wider">
                &larr; Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-[#1b2838] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 1. REJECTED PUBLISHERS --}}
            <div class="bg-[#16202d] overflow-hidden shadow-xl sm:rounded-lg p-6 border-t-4 border-red-500">
                <h3 class="text-xl font-black text-white mb-4 uppercase tracking-widest text-red-400">
                    ❌ Rejected Publisher Requests
                </h3>
                <ul class="divide-y divide-gray-700">
                    @forelse($rejectedPublishers as $user)
                        <li class="py-3 flex justify-between items-center text-gray-400 hover:bg-white/5 px-2 rounded transition">
                            <div>
                                <span class="text-white font-bold">{{ $user->name }}</span> 
                                <span class="text-sm">({{ $user->email }})</span>
                            </div>
                            <span class="text-xs bg-red-900 text-red-200 px-2 py-1 rounded">REJECTED</span>
                        </li>
                    @empty
                        <li class="text-gray-500 italic text-sm">No rejection history available.</li>
                    @endforelse
                </ul>
            </div>

            {{-- 2. REFUND HISTORY --}}
            <div class="bg-[#16202d] overflow-hidden shadow-xl sm:rounded-lg p-6 border-t-4 border-gray-500">
                <h3 class="text-xl font-black text-white mb-4 uppercase tracking-widest text-gray-400">
                    📜 Refund History
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-black/20">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">User</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Game</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700 text-sm text-gray-300">
                            @forelse($processedRefunds as $refund)
                                <tr class="hover:bg-white/5">
                                    <td class="px-4 py-2">{{ $refund->updated_at->format('d M Y') }}</td>
                                    <td class="px-4 py-2">{{ $refund->user->name }}</td>
                                    <td class="px-4 py-2">{{ $refund->game->title }}</td>
                                    <td class="px-4 py-2">
                                        @if($refund->status == 'approved')
                                            <span class="text-green-400 font-bold">APPROVED</span>
                                        @else
                                            <span class="text-red-400 font-bold">REJECTED</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-center text-gray-500 italic">No refund history.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 3. PUBLISHED GAMES HISTORY (ACTIVE) --}}
            <div class="bg-[#16202d] overflow-hidden shadow-xl sm:rounded-lg p-6 border-t-4 border-green-500">
                <h3 class="text-xl font-black text-white mb-4 uppercase tracking-widest text-green-400">
                    ✅ Currently Published Games
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($publishedGames as $game)
                        <div class="bg-black/30 p-2 rounded border border-gray-700 flex items-center gap-3">
                            <img src="{{ $game->cover_image }}" class="w-10 h-14 object-cover rounded">
                            <div class="overflow-hidden">
                                <div class="text-white font-bold text-sm truncate">{{ $game->title }}</div>
                                <div class="text-gray-500 text-xs">{{ $game->publisher }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>