@props(['title', 'games'])

@if($games->count() > 0)

{{-- WRAPPER UTAMA UNTUK CLONE TEMPLATE --}}
<div class="shelf-container mb-32">

    <div class="relative shelf-group">

        {{-- JUDUL RAK --}}
        <div class="relative z-40 flex items-center gap-4 mb-10 pl-4 border-l-8 border-white pointer-events-none">
            <h3 class="text-3xl font-black text-white uppercase tracking-widest leading-none drop-shadow-md pointer-events-auto">
                {{ $title }}
            </h3>
            <span class="text-sm font-bold bg-black text-white px-2 py-1 border border-gray-600 rounded">
                {{ $games->count() }}
            </span>
        </div>

        {{-- CONTAINER BUKU --}}
        <div class="flex items-end justify-start px-12 pb-0 relative z-20 min-h-[350px] overflow-x-visible custom-scrollbar gap-2">
            
            @foreach ($games as $game)
                {{-- SCENE WRAPPER --}}
                <div class="book-scene relative cursor-pointer mx-1 flex-shrink-0"
                     style="width: 50px; height: 320px;" 
                     onclick="openBook(this)"
                     onmouseenter="pullBook(this)"
                     onmouseleave="returnBook(this)"
                     data-data="{{ json_encode($game) }}"
                >
                    {{-- OBJEK BUKU 3D --}}
                    <div class="book-object w-full h-full relative preserve-3d origin-bottom">
                        
                        {{-- 1. SPINE --}}
                        <div class="book-face absolute inset-0 bg-white border-[3px] border-black flex flex-col items-center py-4 z-20 shadow-[inset_0_0_10px_rgba(0,0,0,0.1)] backface-hidden"
                             style="transform: translateZ(25px);">
                            
                            <img src="{{ asset('logo.png') }}" class="w-6 opacity-40 grayscale mb-4">

                            <h3 class="text-black font-black text-xs tracking-widest uppercase text-center w-full truncate" 
                                style="writing-mode: vertical-rl; text-orientation: mixed; transform: rotate(180deg); height: 70%;">
                                {{ $game->title }}
                            </h3>

                            <div class="mt-auto w-1 h-8 bg-black/10"></div>
                        </div>

                        {{-- 2. TOP (Kertas) --}}
                        <div class="book-face absolute left-0 bg-[#e0e0e0] border-x-[3px] border-t-[3px] border-gray-400 origin-top backface-hidden"
                             style="width: 50px; height: 150px; top: 0; transform: rotateX(90deg) translateZ(25px);
                                    background-image: repeating-linear-gradient(90deg, transparent, transparent 1px, #d1d1d1 1px, #d1d1d1 2px);">
                        </div>

                        {{-- 3. SIDE (Cover Art) --}}
                        <div class="book-face absolute top-0 bg-gray-800 border-y-[3px] border-r-[3px] border-black origin-right overflow-hidden backface-hidden"
                             style="width: 150px; height: 320px; right: 0; transform: rotateY(90deg) translateZ(25px);">
                            
                            <img src="{{ $game->cover_image }}" class="w-full h-full object-cover opacity-60 grayscale mix-blend-overlay">
                            <div class="absolute inset-0 bg-black/30"></div>
                        </div>

                    </div>

                    {{-- Bayangan --}}
                    <div class="book-shadow absolute -bottom-4 left-0 w-full h-4 bg-black/60 blur-md rounded-full opacity-0 transition-opacity"></div>
                </div>
            @endforeach

        </div>

        {{-- FISIK RAK --}}
        <div class="absolute bottom-0 left-0 w-full h-8 bg-[#0a0a0a] border-t-4 border-b-8 border-black z-10 shadow-2xl"></div>
        <div class="absolute bottom-8 left-0 w-full h-[360px] bg-[#121212] border-b-4 border-black z-0 shadow-inner"></div>

    </div>

</div>
@endif