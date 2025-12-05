<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

    {{-- BACKGROUND HALAMAN (Sama dengan Login) --}}
    <div class="min-h-screen flex items-center justify-center relative p-4"
        style="background-image: url('https://picsum.photos/1920/1080?random=99'); background-size: cover; background-position: center;">
        {{-- Overlay Gelap --}}
        <div class="absolute inset-0 bg-black opacity-60"></div>

        {{-- PANEL REGISTER (Style Gradient sama dengan Login) --}}
        <div class="relative z-10 w-full sm:max-w-md rounded-xl shadow-2xl overflow-hidden"
            style="background:black;">

            <div class="p-8 pb-4">

                {{-- HEADER: LOGO & JUDUL (Sama persis dengan Login) --}}
                <div class="text-center mb-6">
                    <div class="flex items-center justify-center gap-4">

                        {{-- LOGO --}}
                        <a href="{{ url('/') }}" class="hover:opacity-80 transition-opacity duration-200">
                            <img src="{{ asset('logo.png') }}" alt="Logo"
                                class="h-24 w-auto object-contain drop-shadow-lg">
                        </a>

                        {{-- JUDUL DENGAN STROKE --}}
                        <h1 class="text-3xl font-black tracking-widest uppercase">
                            <span class="text-black">KU</span>
                            <span class="text-white" style="-webkit-text-stroke: 2px black; paint-order: stroke fill;">
                                KUS
                            </span>
                        </h1>

                    </div>
                    <p class="text-white text-sm mt-1">Buat akun baru</p>
                </div>

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div
                        class="mb-4 p-2bg-red-500/10 border border-red-500/50 rounded text-red-500 text-sm text-center font-bold">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- Input NAMA (Style baru: Putih Border Hitam) --}}
                    <div class="mb-4">
                        <label for="name" class="block font-bold text-xs text-gray-900 mb-1 tracking-widest">
                            NAMA
                        </label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                            autocomplete="name"
                            class="w-full bg-white border-2 border-black text-gray-900 font-bold rounded p-2.5 focus:ring-2 focus:ring-black focus:border-black outline-none transition-colors shadow-sm placeholder-white"
                            placeholder="Nama Lengkap">
                    </div>

                    {{-- Input EMAIL (Style baru) --}}
                    <div class="mb-4">
                        <label for="email" class="block font-bold text-xs text-gray-900 mb-1 tracking-widest">
                            EMAIL
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            autocomplete="username"
                            class="w-full bg-white border-2 border-black text-gray-900 font-bold rounded p-2.5 focus:ring-2 focus:ring-black focus:border-black outline-none transition-colors shadow-sm placeholder-white"
                            placeholder="user@example.com">
                    </div>

                    {{-- Input PASSWORD (Style baru) --}}
                    <div class="mb-4">
                        <label for="password" class="block font-bold text-xs text-gray-900 mb-1 tracking-widest">
                            PASSWORD
                        </label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            class="w-full bg-white border-2 border-black text-gray-900 font-bold rounded p-2.5 focus:ring-2 focus:ring-black focus:border-black- outline-none transition-colors shadow-sm placeholder-white"
                            placeholder="••••••••">
                    </div>

                    {{-- Input KONFIRMASI PASSWORD (Style baru) --}}
                    <div class="mb-6">
                        <label for="password_confirmation"
                            class="block font-bold text-xs text-gray-900 mb-1 tracking-widest">
                            KONFIRMASI PASSWORD
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            autocomplete="new-password"
                            class="w-full bg-white border-2 border-black text-gray-900 font-bold rounded p-2.5 focus:ring-2 focus:ring-black focus:border-black outline-none transition-colors shadow-sm placeholder-white"
                            placeholder="••••••••">
                    </div>

                    {{-- Tombol Daftar --}}
                    <div class="flex items-center justify-end mt-6">
                        <button type="submit"
                            class="bg-gradient-to-r from-black to-black hover:from-yellow-500 hover:to-yellow-400 text-white font-bold py-2 px-6 rounded shadow-lg transform hover:translate-y-[-1px] transition-all duration-200 flex items-center gap-2 border border-black/20">
                            <span>DAFTAR</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- FOOTER PANEL (Link balik ke Login) --}}
            <div class="px-8 py-4 border-t border-gray-700/30 text-center">
                <p class="text-sm text-white font-medium">
                    Sudah punya akun?
                    <a href="{{ route('login') }}"
                        class="text-blue-600 hover:text-blue-800 font-bold ml-1 hover:underline">
                        Login di sini
                    </a>
                </p>
            </div>

        </div>
    </div>
</body>

</html>