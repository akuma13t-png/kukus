@extends('layouts.app')

@section('content')
<div class="bg-[#1b2838] min-h-screen text-white font-sans py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-10 border-b border-gray-600 pb-4">
            <h1 class="text-4xl font-black uppercase tracking-widest text-[#66c0f4]">Admin Command Center</h1>
            <span class="bg-red-600 text-white px-3 py-1 text-xs font-bold rounded uppercase">Administrator Access</span>
        </div>

        {{-- SECTION 1: APPROVAL PUBLISHER --}}
        <div class="mb-12">
            <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
                <span class="text-yellow-400">⚠️</span> Pending Publisher Requests
            </h2>
            <div class="bg-[#16202d] p-6 rounded shadow-lg border border-gray-700">
                @forelse($pendingPublishers as $user)
                    <div class="flex justify-between items-center bg-[#2a475e] p-4 mb-2 rounded">
                        <div>
                            <p class="font-bold text-lg text-white">{{ $user->name }}</p>
                            <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                            <p class="text-xs text-blue-300 mt-1">Requested: {{ $user->updated_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex gap-2">
                            <form action="{{ route('admin.approve_publisher', $user) }}" method="POST">
                                @csrf
                                <button class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 text-xs font-bold rounded uppercase">Approve</button>
                            </form>
                            <form action="{{ route('admin.reject_publisher', $user) }}" method="POST">
                                @csrf
                                <button class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 text-xs font-bold rounded uppercase">Reject</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 italic">No pending publisher requests.</p>
                @endforelse
            </div>
        </div>

        {{-- SECTION 2: APPROVAL GAMES --}}
        <div class="mb-12">
            <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
                <span class="text-blue-400">🎮</span> Pending Game Approvals
            </h2>
            <div class="bg-[#16202d] p-6 rounded shadow-lg border border-gray-700">
                @forelse($pendingGames as $game)
                    <div class="flex gap-4 bg-[#2a475e] p-4 mb-2 rounded items-start">
                        <img src="{{ $game->cover_image }}" class="w-24 h-14 object-cover rounded">
                        <div class="flex-grow">
                            <p class="font-bold text-lg text-[#66c0f4]">{{ $game->title }}</p>
                            <p class="text-gray-400 text-xs">{{ $game->genre }} | Price: Rp {{ number_format($game->price) }}</p>
                            <p class="text-gray-300 text-sm mt-1 line-clamp-2">{{ $game->description }}</p>
                        </div>
                        <div class="flex flex-col gap-2">
                             <a href="{{ route('game.show', $game) }}" class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 text-xs font-bold rounded uppercase text-center">Inspect</a>
                            <form action="{{ route('admin.approve_game', $game) }}" method="POST">
                                @csrf
                                <button class="w-full bg-green-600 hover:bg-green-500 text-white px-4 py-2 text-xs font-bold rounded uppercase">Publish</button>
                            </form>
                            <form action="{{ route('admin.reject_game', $game) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="w-full bg-red-600 hover:bg-red-500 text-white px-4 py-2 text-xs font-bold rounded uppercase">Take Down</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 italic">No pending games needing approval.</p>
                @endforelse
            </div>
        </div>

        {{-- SECTION 3: USER MANAGEMENT --}}
        <div>
            <h2 class="text-2xl font-bold mb-4 text-red-400">☠️ User Management</h2>
            <div class="bg-[#16202d] p-6 rounded shadow-lg border border-gray-700 max-h-96 overflow-y-auto">
                <table class="w-full text-left text-sm text-gray-400">
                    <thead class="bg-black text-white uppercase font-bold">
                        <tr>
                            <th class="p-3">User</th>
                            <th class="p-3">Role</th>
                            <th class="p-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allUsers as $u)
                        <tr class="border-b border-gray-700 hover:bg-[#2a475e]">
                            <td class="p-3">
                                <div class="font-bold text-white">{{ $u->name }}</div>
                                <div class="text-xs">{{ $u->email }}</div>
                            </td>
                            <td class="p-3">
                                @if($u->role === 'publisher') 
                                    <span class="text-yellow-400 font-bold">Publisher</span>
                                @else
                                    <span>User</span>
                                @endif
                            </td>
                            <td class="p-3 text-right">
                                <form action="{{ route('admin.ban_user', $u) }}" method="POST" onsubmit="return confirm('Are you sure you want to BAN this user? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500 hover:text-red-300 font-bold uppercase text-xs border border-red-900 px-2 py-1 hover:bg-red-900 rounded">Ban Hammer</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection