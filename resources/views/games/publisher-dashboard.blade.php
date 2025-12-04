<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Publisher Game Management') }}
            </h2>
            <a href="{{ route('games.create') }}" class="bg-green-600 hover:bg-green-500 text-white font-bold py-2 px-4 rounded text-xs uppercase tracking-wider flex items-center gap-2">
                <span class="text-lg leading-none">+</span> Upload New Game
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-[#1b2838] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded shadow-lg font-bold mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-[#16202d] overflow-hidden shadow-xl sm:rounded-lg p-6 border-t-4 border-[#66c0f4]">
                <h3 class="text-xl font-black text-white mb-6 uppercase tracking-widest">
                    My Uploaded Games ({{ $userGames->count() }})
                </h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-black/20">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Game Title</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Updated At</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700 text-gray-300">
                            @forelse($userGames as $game)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-4 py-4 font-bold text-white flex items-center gap-3">
                                        <img src="{{ $game->cover_image }}" class="h-10 w-6 object-cover rounded border border-gray-600">
                                        {{ $game->title }}
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($game->is_approved)
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900 text-green-200 border border-green-700">✅ Published</span>
                                        @else
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-900 text-yellow-200 border border-yellow-700">⏳ Pending Review</span>
                                        @endif
                                        {{-- Catatan: Jika ingin status "Ditolak", kita perlu kolom baru di tabel `games`. Untuk saat ini, game yang ditolak admin dihapus, jadi tidak muncul di sini. Jika diedit, status kembali Pending. --}}
                                    </td>
                                    <td class="px-4 py-4 text-sm">{{ $game->updated_at->format('d M Y H:i') }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex gap-2">
                                            {{-- Tombol View --}}
                                            <a href="{{ route('game.show', $game) }}" class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-1 rounded text-xs uppercase font-bold" target="_blank">View Page</a>
                                            
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('games.edit', $game) }}" class="bg-gray-600 hover:bg-gray-500 text-white px-3 py-1 rounded text-xs uppercase font-bold">Edit Details</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500 italic">You have not uploaded any games yet. <a href="{{ route('games.create') }}" class="text-[#66c0f4] hover:underline">Click here to start!</a></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>