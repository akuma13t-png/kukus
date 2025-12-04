@extends('layouts.guest')

@section('content')
<div class="bg-[#1b2838] min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <h1 class="text-3xl font-light text-white uppercase tracking-wider mb-8">
            Top Up <span class="font-bold text-[#66c0f4]">Kukus Money</span>
        </h1>

        @if(session('success'))
            <div class="bg-green-600/20 border border-green-500 text-green-400 p-4 rounded mb-6 font-bold">
                {{ session('success') }}
            </div>
        @endif
        
        {{-- Menampilkan Error Validasi --}}
        @if ($errors->any())
            <div class="bg-red-600/20 border border-red-500 text-red-400 p-4 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-[#16202d] p-8 border border-black shadow-2xl" x-data="{ manualInput: '' }">
            
            {{-- Current Balance Display --}}
            <div class="mb-8 p-4 bg-[#2a3f5a] rounded-sm border-l-4 border-[#66c0f4]">
                <p class="text-gray-400 text-sm uppercase font-bold tracking-wider">Saldo Anda Saat Ini</p>
                <p class="text-4xl font-black text-white">
                    Rp {{ number_format(Auth::user()->kukus_money_balance ?? 0, 0, ',', '.') }}
                </p>
            </div>

            <form action="{{ route('kukus.topup.store') }}" method="POST" class="space-y-6">
                @csrf
                
                {{-- 1. Pilih Jumlah Topup (Preset) --}}
                <div>
                    <label class="block text-[#66c0f4] text-sm font-bold uppercase mb-4">Pilih Nominal Top Up (IDR)</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach($amounts as $amount)
                            <label class="block cursor-pointer group">
                                {{-- Perhatikan name="preset_amount" --}}
                                {{-- Script @click: Jika radio diklik, kosongkan input manual --}}
                                <input type="radio" 
                                       name="preset_amount" 
                                       value="{{ $amount }}" 
                                       class="peer hidden" 
                                       @click="manualInput = ''"
                                >
                                <div class="text-center bg-[#2a3f5a] p-4 rounded-sm border-2 border-transparent peer-checked:border-red-500 peer-checked:bg-[#3d4d5d] group-hover:bg-[#324558] transition-all shadow-md">
                                    <span class="text-white font-bold text-lg block">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- 2. Input Manual (Opsional) --}}
                <div class="border-t border-gray-700 pt-6">
                    <label for="manual_amount" class="block text-gray-400 text-sm font-bold uppercase mb-2">Atau Masukkan Jumlah Lain (Min Rp 10.000)</label>
                    
                    {{-- Perhatikan name="manual_amount" --}}
                    {{-- x-model menyambungkan input ini dengan data Alpine, jika diketik, radio button otomatis ter-reset karena logika di controller memprioritaskan manual --}}
                    <input type="number" 
                           id="manual_amount" 
                           name="manual_amount" 
                           min="10000"
                           x-model="manualInput"
                           @input="document.querySelectorAll('input[name=preset_amount]').forEach(el => el.checked = false)"
                           class="w-full bg-[#2a3f5a] text-white border border-black p-3 focus:outline-none focus:border-white rounded-sm placeholder-gray-500 focus:ring-0" 
                           placeholder="Rp 50.000 atau lebih">
                </div>
                
                <p class="text-xs text-gray-500">Pilih salah satu opsi di atas, atau masukkan jumlah manual.</p>

                {{-- Tombol Lanjutkan --}}
                <div class="pt-6">
                    <button type="submit" class="block w-full bg-gradient-to-r from-red-600 to-red-400 hover:brightness-110 text-white font-black py-3 px-8 rounded-sm shadow-lg uppercase tracking-wider text-lg transition transform hover:-translate-y-1">
                        Lanjutkan Pembayaran Top Up
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection