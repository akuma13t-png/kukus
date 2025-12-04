<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- 1. PUBLISHER REQUESTS (Existing) --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">📢 Publisher Requests</h3>
                {{-- (Isi tabel publisher lama tetap di sini) --}}
                {{-- ... --}}
                 <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @foreach($users as $user)
                                @if($user->publisher_request_status === 'pending')
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-white">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex gap-2">
                                            {{-- UPDATE: Menggunakan $user->id secara eksplisit --}}
                                            <form action="{{ route('admin.approvePublisher', $user->id) }}" method="POST">
                                                @csrf
                                                <button class="text-green-400 hover:text-green-300 font-bold">Approve</button>
                                            </form>
                                            {{-- UPDATE: Menggunakan $user->id secara eksplisit --}}
                                            <form action="{{ route('admin.rejectPublisher', $user->id) }}" method="POST">
                                                @csrf
                                                <button class="text-red-400 hover:text-red-300 font-bold">Reject</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 2. REFUND REQUESTS (BARU) --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-blue-500">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">💸 Refund Requests</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Game</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Reason</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @foreach($refunds as $refund)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-white">
                                        <div class="font-bold">{{ $refund->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $refund->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $refund->game->title }}</td>
                                    <td class="px-6 py-4 text-gray-300 text-sm max-w-xs truncate" title="{{ $refund->reason }}">{{ $refund->reason }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($refund->status == 'pending')
                                            <span class="px-2 py-1 text-xs font-bold rounded bg-yellow-600 text-white">PENDING</span>
                                        @elseif($refund->status == 'approved')
                                            <span class="px-2 py-1 text-xs font-bold rounded bg-green-600 text-white">APPROVED</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-bold rounded bg-red-600 text-white">REJECTED</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($refund->status == 'pending')
                                        <div class="flex gap-2">
                                            <form action="{{ route('admin.refund.approve', $refund->id) }}" method="POST" onsubmit="return confirm('Setujui refund? Game akan dihapus dari library user.');">
                                                @csrf
                                                <button class="bg-green-700 hover:bg-green-600 text-white px-3 py-1 rounded text-xs uppercase tracking-wide">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.refund.reject', $refund->id) }}" method="POST" onsubmit="return confirm('Tolak refund?');">
                                                @csrf
                                                <button class="bg-red-700 hover:bg-red-600 text-white px-3 py-1 rounded text-xs uppercase tracking-wide">Reject</button>
                                            </form>
                                        </div>
                                        @else
                                            <span class="text-gray-500 text-xs italic">Processed</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @if($refunds->isEmpty())
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">No refund requests found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>