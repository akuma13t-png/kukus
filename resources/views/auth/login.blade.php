<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SteamClone</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

    {{-- BACKGROUND HALAMAN --}}
    <div class="min-h-screen flex items-center justify-center relative p-4"
        style="background-image: url('https://picsum.photos/1920/1080?random=99'); background-size: cover; background-position: center;">
        {{-- Overlay Gelap --}}
        <div class="absolute inset-0 bg-black opacity-60"></div>

        {{-- PANEL LOGIN --}}
        <div class="relative z-10 w-full sm:max-w-md rounded-xl shadow-2xl overflow-hidden"
            style="background: linear-gradient(to bottom, #2c2c2cff 80%, #000000ff 100%);">

            <div class="p-8 pb-4">

                {{-- HEADER --}}
                <div class="text-center mb-6">
                    <div class="flex items-center justify-center gap-4">

                        {{-- 1. LOGO --}}
                        <a href="{{ url('/') }}" class="hover:opacity-80 transition-opacity duration-200">
                            <img src="{{ asset('logo.png') }}" alt="Logo"
                                class="h-24 w-auto object-contain drop-shadow-lg">
                        </a>

                        {{-- 2. JUDUL (Tetap dengan style stroke putih-hitam) --}}
                        <h1 class="text-3xl font-black tracking-widest uppercase">
                            <span class="text-gray-900">KU</span>
                            <span class="text-white" style="-webkit-text-stroke: 2px black; paint-order: stroke fill;">
                                KUS
                            </span>
                        </h1>

                    </div>
                    <p class="text-gray-400 text-sm mt-1">Masuk untuk melanjutkan</p>
                </div>

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-500 text-center">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Input Email --}}
                    <div class="mb-4">
                        {{-- LABEL DIUBAH: Hitam Polos, Tebal, Tanpa Stroke --}}
                        <label for="email" class="block font-bold text-xs text-gray-900 mb-1 tracking-widest">
                            EMAIL
                        </label>

                        {{-- Input: Putih dengan Border Hitam --}}
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            autocomplete="username"
                            class="w-full bg-white border-2 border-black text-gray-900 font-bold rounded p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors shadow-sm placeholder-gray-500"
                            placeholder="user@example.com">
                    </div>

                    {{-- Input Password --}}
                    <div class="mb-6">
                        {{-- LABEL DIUBAH: Hitam Polos, Tebal, Tanpa Stroke --}}
                        <label for="password" class="block font-bold text-xs text-gray-900 mb-1 tracking-widest">
                            PASSWORD
                        </label>

                        {{-- Input: Putih dengan Border Hitam --}}
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="w-full bg-white border-2 border-black text-gray-900 font-bold rounded p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors shadow-sm placeholder-gray-500"
                            placeholder="••••••••">
                    </div>

                    {{-- Remember Me & Tombol Login --}}
                    <div class="flex items-center justify-between mb-6">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                            <input id="remember_me" type="checkbox"
                                class="rounded bg-gray-700 border-gray-600 text-blue-500 focus:ring-blue-500 group-hover:border-blue-400"
                                name="remember">
                            <span
                                class="ms-2 text-sm text-gray-400 group-hover:text-gray-200 transition-colors select-none">Ingat
                                saya</span>
                        </label>

                        <button type="submit"
                            class="bg-gradient-to-r from-black to-black hover:from-blue-500 hover:to-blue-400 text-white font-bold py-2 px-6 rounded shadow-lg transform hover:translate-y-[-1px] transition-all duration-200 flex items-center gap-2 border border-black/20">
                            <span>MASUK</span>
                        </button>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-center mb-4">
                            <a class="text-xs text-gray-400 hover:text-white underline transition-colors"
                                href="{{ route('password.request') }}">
                                Lupa Password Anda?
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            {{-- FOOTER PANEL --}}
            <div class="px-8 py-4 border-t border-gray-700/30 text-center">
                <p class="text-sm text-white font-medium">
                    Baru di SteamClone?
                    <a href="{{ route('register') }}"
                        class="text-blue-600 hover:text-blue-800 font-bold ml-1 hover:underline">
                        Buat Akun Gratis
                    </a>
                </p>
            </div>

        </div>
    </div>
</body>

</html>