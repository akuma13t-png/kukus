@extends('layouts.guest')

@section('content')
<div class="bg-[#1b2838] min-h-screen flex items-center justify-center py-12">
    <div class="max-w-2xl w-full bg-[#16202d] border-t-4 border-[#66c0f4] p-8 shadow-2xl text-center">
        
        <div class="mb-6 flex justify-center">
            <div class="bg-green-500/20 p-4 rounded-full border-2 border-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-black text-white uppercase mb-2">Thank You!</h1>
        <p class="text-[#66c0f4] font-bold text-lg mb-6">Pembelian Anda Berhasil.</p>

        <p class="text-gray-400 mb-8 px-8">
            Tanda terima telah dikirim ke email Anda. Game Anda sekarang tersedia di library Anda (Simulasi). Silakan cek library Anda untuk mengunduh.
        </p>

        <div class="flex justify-center gap-4">
            <a href="{{ route('store.index') }}" class="text-gray-400 hover:text-white font-bold text-sm uppercase py-3 px-6 border border-gray-600 hover:border-white transition rounded-sm">
                Kembali ke Toko
            </a>
            <a href="{{ route('library.index') }}" class="bg-gradient-to-r from-[#06BFFF] to-[#2D73FF] hover:brightness-110 text-white font-bold text-sm uppercase py-3 px-6 rounded-sm shadow-lg transition">
                Lihat Library
            </a>
        </div>

    </div>
</div>
@endsection