@extends('layouts.guest')

@section('content')
<div class="bg-[#1b2838] min-h-screen flex items-center justify-center py-12">
    <div class="max-w-3xl w-full bg-[#16202d] border-t-4 border-[#66c0f4] p-8 shadow-2xl text-white">
        
        <div class="mb-6 flex justify-center">
            <div class="bg-green-500/20 p-4 rounded-full border-2 border-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-black uppercase mb-2">Thank You, {{ $transaction['user_name'] ?? 'Customer' }}!</h1>
        <p class="text-[#66c0f4] font-bold text-lg mb-6">Pembelian Anda Berhasil Diproses.</p>

        {{-- DETAIL TRANSAKSI --}}
        <div class="bg-[#2a3f5a] p-6 rounded-sm mb-8 text-left border border-gray-700">
            <h3 class="text-xl font-bold mb-3 uppercase tracking-wider text-white">Transaction Details</h3>
            <div class="space-y-1 text-sm text-gray-300">
                <div class="flex justify-between border-b border-gray-600 pb-1">
                    <span class="font-bold">Transaction ID:</span>
                    <span class="text-white">{{ $transaction['id'] ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-600 pb-1">
                    <span class="font-bold">Date:</span>
                    <span>{{ $transaction['date'] ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-bold">Payment Method:</span>
                    <span>{{ ucfirst($transaction['method'] ?? 'N/A') }}</span>
                </div>
            </div>

            <h4 class="text-base font-bold mt-4 mb-2 text-[#66c0f4]">Purchased Games:</h4>
            <ul class="space-y-1 text-sm">
                @foreach ($transaction['items'] ?? [] as $item)
                    <li class="flex justify-between text-gray-400">
                        <span>{{ $item['title'] }}</span>
                        <span class="text-right">Rp {{ number_format($item['final_price'], 0, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
            
            <div class="border-t-2 border-white/50 pt-3 mt-3 flex justify-between items-center">
                <span class="text-xl font-black text-white uppercase">Total Paid:</span>
                <span class="text-2xl font-black text-green-400">Rp {{ number_format($transaction['total'], 0, ',', '.') }}</span>
            </div>

        </div>

        <p class="text-gray-400 mb-8 px-8">
            Game Anda sekarang tersedia di library Anda. Silakan cek library Anda untuk mengunduh dan mulai bermain!
        </p>

        <div class="flex justify-center gap-4">
            
            {{-- Tombol Download Nota --}}
            <a href="{{ route('cart.receipt') }}" target="_blank"
                class="bg-gray-700 hover:bg-gray-600 text-white font-bold text-sm uppercase py-3 px-6 border border-gray-600 hover:border-white transition rounded-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Download Nota
            </a>

            <a href="{{ route('library.index') }}" class="bg-gradient-to-r from-[#06BFFF] to-[#2D73FF] hover:brightness-110 text-white font-bold text-sm uppercase py-3 px-6 rounded-sm shadow-lg transition">
                Lihat Library
            </a>
        </div>

    </div>
</div>
@endsection