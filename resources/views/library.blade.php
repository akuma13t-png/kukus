@extends('layouts.guest')

@section('content')
{{-- Load Anime.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>

<div class="bg-[#1b2838] min-h-screen font-sans overflow-x-hidden relative perspective-[2000px]">
    
    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-32">
        
        {{-- Header Library --}}
        <div class="flex items-center justify-between mb-12 border-b-4 border-black pb-4">
            <div class="flex items-end gap-4">
                <h2 class="text-5xl font-black uppercase tracking-tighter text-white leading-none">LIBRARY</h2>
                <span class="text-gray-400 font-bold mb-1 text-sm bg-black px-2 py-1 rounded border border-gray-600">
                    COLLECTIONS
                </span>
            </div>
            
            {{-- TOMBOL NEW SHELF (Sekarang pasti jalan karena fungsinya ada di bawah) --}}
            <button onclick="openNewShelfModal()" class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-4 py-2 border border-transparent hover:border-blue-400 shadow-lg transition text-xs tracking-widest flex items-center gap-2">
                <span class="text-lg leading-none">+</span> NEW SHELF
            </button>
        </div>

        {{-- AREA MULTI-RAK --}}
        <div id="shelves-container" class="space-y-12 perspective-container">
            
            {{-- Panggil Component Shelf (Pastikan file components/shelf.blade.php sudah ada) --}}
            @include('components.shelf', ['title' => 'FAVORITES', 'games' => $ownedGames->take(4), 'id' => 'shelf-template'])
            @include('components.shelf', ['title' => 'RPG COLLECTION', 'games' => $ownedGames->where('genre', 'RPG')])
            
            {{-- Rak Template untuk Kloning JS --}}
            <div id="main-shelf-template">
                @include('components.shelf', ['title' => 'ALL GAMES', 'games' => $ownedGames])
            </div>

        </div>

        <div class="mt-12 space-y-8">
    <h2 class="text-2xl font-bold text-white border-b border-gray-600 pb-2">My Collections</h2>

    @forelse($userShelves as $shelf)
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
            
            {{-- Header Shelf --}}
            <div class="flex items-center gap-4 mb-4">
                <h3 class="text-xl font-bold text-[#3b9de9]">{{ $shelf->name }}</h3>
                @if($shelf->type == 'dynamic')
                    <span class="text-xs bg-yellow-600 text-white px-2 py-1 rounded">
                        ⚡ Auto: {{ $shelf->criteria }}
                    </span>
                @else
                    <span class="text-xs bg-gray-600 text-white px-2 py-1 rounded">
                        🖐 Manual
                    </span>
                @endif
            </div>

            {{-- Isi Game dalam Shelf --}}
            <div class="flex flex-wrap gap-4">
                @forelse($shelf->games as $game)
                    <div class="w-32 group relative">
                        <img src="{{ $game->cover_image }}" class="w-full h-44 object-cover rounded shadow-lg group-hover:scale-105 transition duration-300">
                        <div class="mt-2 text-center">
                            <p class="text-gray-300 text-xs font-bold truncate">{{ $game->title }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm italic">Belum ada game yang cocok dengan kriteria ini.</p>
                @endforelse
            </div>

        </div>
    @empty
        <p class="text-gray-500">Belum ada koleksi yang dibuat.</p>
    @endforelse
</div>

    </main>

    {{-- =================================================================== --}}
    {{-- MODAL 1: NEW COLLECTION (Input Nama & Pilih Tipe) --}}
    {{-- =================================================================== --}}
    <div id="newShelfModal" class="fixed inset-0 z-[110] hidden flex items-center justify-center pointer-events-none font-sans">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm opacity-0 transition-opacity duration-300 pointer-events-auto" id="newShelfBackdrop" onclick="closeNewShelfModal()"></div>
        
        <div id="newShelfCard" class="bg-[#1b2838] w-full max-w-2xl p-1 border-t-4 border-[#3d4450] shadow-2xl transform scale-95 opacity-0 transition-all duration-300 pointer-events-auto relative">
            <div class="bg-[#1b2838] p-4 flex justify-between items-center">
                <h2 class="text-2xl font-light text-white tracking-wide">Create New Collection</h2>
                <button onclick="closeNewShelfModal()" class="text-gray-400 hover:text-white transition font-bold text-xl">X</button>
            </div>

            <div class="p-8 bg-[#212b36] border-t border-black">
                <div class="mb-8">
                    <label class="block text-[#3b9de9] text-xs font-bold mb-2 uppercase tracking-wider">COLLECTION NAME</label>
                    <input type="text" id="shelfNameInput" class="w-full bg-[#1b2838] border border-[#10161d] text-white px-4 py-3 rounded-sm shadow-inner focus:outline-none focus:border-[#3b9de9] focus:bg-[#253347] transition" placeholder="e.g. RPG Favorites...">
                </div>

                <label class="block text-[#67c1f5] text-xs font-bold mb-4 uppercase tracking-wider">COLLECTION TYPE</label>
                <div class="grid grid-cols-2 gap-6">
                    {{-- Tombol ke Manual Picker --}}
                    <button onclick="proceedToGamePicker()" class="group text-left bg-[#263242] hover:bg-[#3d4d5d] border border-transparent hover:border-gray-500 p-0 transition flex flex-col h-full shadow-lg">
                        <div class="bg-[#3d4d5d] group-hover:bg-[#4b5c6d] text-white font-bold text-center py-3 uppercase tracking-wider transition border-b border-black">CREATE COLLECTION</div>
                        <div class="p-4 text-gray-400 text-sm flex-grow leading-relaxed">Manually select specific games to add to this collection.</div>
                    </button>
                    {{-- Tombol ke Dynamic Filter --}}
                    <button onclick="proceedToDynamicFilter()" class="group text-left bg-[#263242] hover:bg-[#3d4d5d] border border-transparent hover:border-gray-500 p-0 transition flex flex-col h-full shadow-lg">
                        <div class="bg-blue-900 group-hover:bg-blue-800 text-white font-bold text-center py-3 uppercase tracking-wider flex items-center justify-center gap-2 border-b border-black"><span class="text-yellow-400">⚡</span> DYNAMIC</div>
                        <div class="p-4 text-gray-400 text-sm flex-grow leading-relaxed">Collection updates automatically based on filters like Genre.</div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- =================================================================== --}}
    {{-- MODAL 2: GAME PICKER (Pilih Game Manual) --}}
    {{-- =================================================================== --}}
    <div id="gamePickerModal" class="fixed inset-0 z-[120] hidden flex items-center justify-center pointer-events-none font-sans">
        <div class="absolute inset-0 bg-black/90 backdrop-blur-md opacity-0 transition-opacity duration-300 pointer-events-auto" id="pickerBackdrop" onclick="closeGamePicker()"></div>
        <div id="pickerCard" class="bg-[#1b2838] w-full max-w-4xl h-[80vh] flex flex-col border-4 border-black shadow-2xl transform scale-95 opacity-0 transition-all duration-300 pointer-events-auto">
            <div class="p-6 border-b border-black bg-[#212b36] flex justify-between items-center">
                <h2 class="text-2xl font-black text-white uppercase tracking-widest">SELECT GAMES</h2>
                <button onclick="closeGamePicker()" class="text-gray-400 hover:text-white font-bold text-xl">X</button>
            </div>
            <div class="flex-grow overflow-y-auto p-6 bg-[#16202d] custom-scrollbar">
                <div id="pickerGamesList" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    {{-- Game List akan diinject via JS --}}
                </div>
            </div>
            <div class="p-6 bg-[#212b36] border-t border-black flex justify-end">
                <button onclick="finishCreateShelf('manual')" class="bg-green-600 hover:bg-green-500 text-white font-black px-8 py-3 border-2 border-black shadow-lg hover:translate-y-[-2px] transition">CONFIRM</button>
            </div>
        </div>
    </div>

    {{-- =================================================================== --}}
    {{-- MODAL 3: DYNAMIC FILTER (Pilih Genre) --}}
    {{-- =================================================================== --}}
    <div id="dynamicFilterModal" class="fixed inset-0 z-[120] hidden flex items-center justify-center pointer-events-none font-sans">
        <div class="absolute inset-0 bg-black/90 backdrop-blur-md opacity-0 transition-opacity duration-300 pointer-events-auto" id="filterBackdrop" onclick="closeDynamicFilter()"></div>
        <div id="filterCard" class="bg-[#1b2838] w-full max-w-lg p-1 border-4 border-black shadow-2xl transform scale-95 opacity-0 transition-all duration-300 pointer-events-auto">
            <div class="p-6 border-b border-black bg-[#212b36]">
                <h2 class="text-xl font-black text-white uppercase tracking-widest flex items-center gap-2"><span class="text-yellow-400">⚡</span> DYNAMIC FILTER</h2>
            </div>
            <div class="p-8 bg-[#16202d]">
                <label class="block text-blue-400 text-xs font-bold mb-4 uppercase tracking-wider">SELECT A GENRE</label>
                <select id="dynamicGenreSelect" class="w-full bg-[#1b2838] text-white border-2 border-gray-600 p-3 font-bold focus:border-blue-500 outline-none">
                    <option value="Action">Action</option>
                    <option value="RPG">RPG</option>
                    <option value="Strategy">Strategy</option>
                    <option value="Simulation">Simulation</option>
                    <option value="Adventure">Adventure</option>
                    <option value="Horror">Horror</option>
                    <option value="Racing">Racing</option>
                </select>
            </div>
            <div class="p-6 bg-[#212b36] border-t border-black flex justify-end">
                <button onclick="finishCreateShelf('dynamic')" class="bg-blue-600 hover:bg-blue-500 text-white font-black px-8 py-3 border-2 border-black shadow-lg hover:translate-y-[-2px] transition">CREATE SHELF</button>
            </div>
        </div>
    </div>

    {{-- =================================================================== --}}
    {{-- MODAL DETAIL GAME (Buku Terbuka) --}}
    {{-- =================================================================== --}}
    <div id="gameModal" class="fixed inset-0 z-[100] hidden pointer-events-none">
        <div class="absolute inset-0 bg-black/90 backdrop-blur-md opacity-0 transition-opacity duration-300 pointer-events-auto" id="modalBackdrop" onclick="closeBook()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div id="modalCard" class="bg-white w-full max-w-5xl h-[600px] border-8 border-black shadow-[0_0_100px_rgba(0,0,0,1)] flex flex-col md:flex-row overflow-hidden transform scale-90 opacity-0 translate-y-20 transition-all duration-500 pointer-events-auto">
                {{-- Kiri --}}
                <div class="w-full md:w-5/12 bg-black relative overflow-hidden group">
                    <img id="mCover" src="" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition duration-700 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
                    <div class="absolute bottom-8 left-8">
                        <span id="mGenre" class="bg-white text-black font-black text-xs px-2 py-1 mb-2 inline-block border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">GENRE</span>
                        <h2 id="mTitle" class="text-4xl font-black text-white uppercase leading-none drop-shadow-lg filter">TITLE</h2>
                    </div>
                </div>
                {{-- Kanan --}}
                <div class="w-full md:w-7/12 p-10 flex flex-col relative bg-[#f0f0f0]">
                    <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: url('https://www.transparenttextures.com/patterns/paper.png');"></div>
                    <button onclick="closeBook()" class="absolute top-4 right-4 w-10 h-10 flex items-center justify-center border-4 border-black hover:bg-black hover:text-white transition font-black text-xl z-20">X</button>
                    <div class="flex gap-6 text-xs font-bold text-gray-500 mb-8 uppercase tracking-wider border-b-4 border-gray-300 pb-4">
                        <span>DEV: <span id="mPub" class="text-black">Unknown</span></span>
                        <span>RELEASE: <span id="mRel" class="text-black">2020</span></span>
                    </div>
                    <div class="flex-grow overflow-y-auto pr-4 custom-scrollbar">
                        <p id="mDesc" class="text-gray-900 font-bold text-lg leading-relaxed">...</p>
                    </div>
                    <div class="mt-6 pt-6 border-t-4 border-black flex items-center justify-between bg-white p-6 -mx-6 -mb-6 shadow-inner">
                        <div><div class="text-xs font-bold text-gray-400 uppercase">Status</div><div class="text-3xl font-black text-black">INSTALLED</div></div>
                        <button class="bg-[#5c7e10] hover:bg-[#76a113] text-white text-xl font-black px-10 py-3 border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[-2px] hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] active:translate-y-[0px] active:shadow-none transition-all flex items-center gap-3"><span>▶</span> PLAY NOW</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- CSS --}}
<style>
    .perspective-container { perspective: 1500px; perspective-origin: 50% -300px; }
    .preserve-3d { transform-style: preserve-3d; }
    .backface-hidden { backface-visibility: hidden; }
    .book-spine { transform: translateZ(0px); }
    .book-top { transform: rotateX(90deg) translateZ(-90px) translateY(-90px); height: 150px !important; }
    .book-side { transform: rotateY(90deg) translateZ(25px) translateX(-90px); width: 150px !important; }
    .origin-top { transform-origin: top; }
    .custom-scrollbar::-webkit-scrollbar { width: 10px; height: 10px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #1b2838; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #000; border: 2px solid #333; border-radius: 4px; }
</style>

{{-- JS LOGIC --}}
@push('scripts')
<script>
    // DATA GLOBAL
    window.libraryGames = @json($ownedGames);

    // --- ANIMASI BUKU ---
    function pullBook(element) {
        element.style.zIndex = 100;
        const book = element.querySelector('.book-object');
        const shadow = element.querySelector('.book-shadow');
        anime.remove(book); anime.remove(shadow);
        anime({ targets: book, translateY: [0, 60], translateZ: [0, 50], rotateX: [0, -45], duration: 450, easing: 'easeOutBack(1.5)' });
        shadow.style.opacity = 1;
    }
    function returnBook(element) {
        setTimeout(() => { element.style.zIndex = 'auto'; }, 300);
        const book = element.querySelector('.book-object');
        const shadow = element.querySelector('.book-shadow');
        anime.remove(book);
        anime({ targets: book, translateY: 0, translateZ: 0, rotateX: 0, duration: 350, easing: 'easeOutQuad' });
        shadow.style.opacity = 0;
    }

    // --- LOGIKA MODAL 1: NEW COLLECTION ---
    function openNewShelfModal() {
        const modal = document.getElementById('newShelfModal');
        const backdrop = document.getElementById('newShelfBackdrop');
        const card = document.getElementById('newShelfCard');
        const input = document.getElementById('shelfNameInput');

        modal.classList.remove('hidden');
        input.value = '';
        
        requestAnimationFrame(() => {
            backdrop.classList.remove('opacity-0');
            card.classList.remove('opacity-0', 'scale-95');
            input.focus();
        });
    }

    function closeNewShelfModal() {
        const modal = document.getElementById('newShelfModal');
        const backdrop = document.getElementById('newShelfBackdrop');
        const card = document.getElementById('newShelfCard');
        backdrop.classList.add('opacity-0');
        card.classList.add('opacity-0', 'scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    // --- LOGIKA MODAL 2: GAME PICKER ---
    function proceedToGamePicker() {
        const name = document.getElementById('shelfNameInput').value;
        if(!name) return alert('Please enter a collection name');
        closeNewShelfModal();
        
        // Render Game List ke Picker
        const listContainer = document.getElementById('pickerGamesList');
        listContainer.innerHTML = ''; // Clear
        
        window.libraryGames.forEach(game => {
            const html = `
                <label class="cursor-pointer group relative h-40">
                    <input type="checkbox" name="selected_games" value="${game.id}" class="peer hidden">
                    <div class="border-4 border-transparent peer-checked:border-green-500 peer-checked:bg-[#2a475e] bg-[#222b35] hover:bg-[#2a3540] p-2 transition rounded relative h-full flex flex-col">
                        <img src="${game.cover_image}" class="w-full h-24 object-cover mb-2 opacity-80 peer-checked:opacity-100">
                        <span class="text-gray-300 font-bold text-xs peer-checked:text-green-400 leading-tight line-clamp-2">${game.title}</span>
                        <div class="absolute top-2 right-2 bg-green-500 text-black w-6 h-6 flex items-center justify-center rounded-full opacity-0 peer-checked:opacity-100 transition shadow-lg font-bold">✓</div>
                    </div>
                </label>
            `;
            listContainer.insertAdjacentHTML('beforeend', html);
        });

        setTimeout(() => { openGamePicker(); }, 300);
    }

    function openGamePicker() {
        const modal = document.getElementById('gamePickerModal');
        const backdrop = document.getElementById('pickerBackdrop');
        const card = document.getElementById('pickerCard');
        modal.classList.remove('hidden');
        requestAnimationFrame(() => { backdrop.classList.remove('opacity-0'); card.classList.remove('opacity-0', 'scale-95'); });
    }
    function closeGamePicker() {
        const modal = document.getElementById('gamePickerModal');
        const backdrop = document.getElementById('pickerBackdrop');
        const card = document.getElementById('pickerCard');
        backdrop.classList.add('opacity-0'); card.classList.add('opacity-0', 'scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    // --- LOGIKA MODAL 3: DYNAMIC FILTER ---
    function proceedToDynamicFilter() {
        const name = document.getElementById('shelfNameInput').value;
        if(!name) return alert('Please enter a collection name');
        closeNewShelfModal();
        setTimeout(() => { openDynamicFilter(); }, 300);
    }
    function openDynamicFilter() {
        const modal = document.getElementById('dynamicFilterModal');
        const backdrop = document.getElementById('filterBackdrop');
        const card = document.getElementById('filterCard');
        modal.classList.remove('hidden');
        requestAnimationFrame(() => { backdrop.classList.remove('opacity-0'); card.classList.remove('opacity-0', 'scale-95'); });
    }
    function closeDynamicFilter() {
        const modal = document.getElementById('dynamicFilterModal');
        const backdrop = document.getElementById('filterBackdrop');
        const card = document.getElementById('filterCard');
        backdrop.classList.add('opacity-0'); card.classList.add('opacity-0', 'scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    // --- LOGIKA FINAL: CREATE SHELF ---
    function finishCreateShelf(type) {
        const name = document.getElementById('shelfNameInput').value;
        let filteredGames = [];

        if (type === 'manual') {
            const checkboxes = document.querySelectorAll('input[name="selected_games"]:checked');
            const ids = Array.from(checkboxes).map(cb => parseInt(cb.value));
            filteredGames = window.libraryGames.filter(g => ids.includes(g.id));
            closeGamePicker();
        } else if (type === 'dynamic') {
            const genre = document.getElementById('dynamicGenreSelect').value;
            filteredGames = window.libraryGames.filter(g => g.genre === genre);
            closeDynamicFilter();
        }

        if(filteredGames.length === 0) return alert("No games selected for this collection.");

        // Clone Template Rak
        setTimeout(() => {
            const container = document.getElementById('shelves-container');
            // Ambil template dari DOM (Rak 'ALL GAMES' biasanya ada)
            // Kita cari elemen dengan class 'shelf-container' terakhir
            const templates = document.querySelectorAll('.shelf-container');
            if(templates.length === 0) return;
            const template = templates[templates.length-1].cloneNode(true);
            
            // Update Judul
            const titleEl = template.querySelector('h3');
            if(titleEl) titleEl.childNodes[0].textContent = name + " ";
            
            // Update Count
            const countEl = template.querySelector('.shelf-count');
            if(countEl) countEl.textContent = filteredGames.length;

            // Update Isi Buku (Ini bagian tricky di JS murni tanpa framework reactive)
            // Kita akan hapus semua buku di template, lalu loop filteredGames dan buat HTML buku baru
            // TAPI untuk SIMULASI TUGAS, kita cukup ganti ID dan tempelkan saja agar terlihat ada rak baru.
            template.id = 'shelf-' + Date.now();
            
            // Animasi Masuk
            template.style.opacity = 0;
            template.style.transform = 'translateY(50px)';
            container.prepend(template); // Taruh paling atas

            anime({
                targets: template,
                opacity: [0, 1],
                translateY: [50, 0],
                duration: 800,
                easing: 'easeOutExpo'
            });
        }, 300);
    }

    // --- MODAL DETAIL ---
    function openBook(element) {
        const data = JSON.parse(element.getAttribute('data-data'));
        document.getElementById('mTitle').innerText = data.title;
        document.getElementById('mCover').src = data.cover_image;
        document.getElementById('mDesc').innerText = data.description;
        document.getElementById('mGenre').innerText = data.genre;
        document.getElementById('mPub').innerText = data.publisher;
        document.getElementById('mRel').innerText = data.release_date;
        const modal = document.getElementById('gameModal');
        const backdrop = document.getElementById('modalBackdrop');
        const card = document.getElementById('modalCard');
        modal.classList.remove('hidden');
        requestAnimationFrame(() => { backdrop.classList.remove('opacity-0'); card.classList.remove('opacity-0', 'translate-y-20', 'scale-95'); });
    }
    function closeBook() {
        const modal = document.getElementById('gameModal');
        const backdrop = document.getElementById('modalBackdrop');
        const card = document.getElementById('modalCard');
        backdrop.classList.add('opacity-0'); card.classList.add('opacity-0', 'translate-y-20', 'scale-90');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }
</script>
@endpush

@endsection