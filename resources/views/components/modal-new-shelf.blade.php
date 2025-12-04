<div id="newShelfModal" class="fixed inset-0 z-[110] hidden flex items-center justify-center pointer-events-none font-sans">
    
    {{-- BACKDROP --}}
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm opacity-0 transition-opacity duration-300 pointer-events-auto" 
         id="newShelfBackdrop" onclick="closeNewShelfModal()"></div>
    
    {{-- MODAL CARD --}}
    <div id="newShelfCard" class="bg-[#1b2838] w-full max-w-2xl p-1 border-t-4 border-[#3d4450] shadow-2xl transform scale-95 opacity-0 transition-all duration-300 pointer-events-auto relative">
        
        {{-- HEADER --}}
        <div class="bg-[#1b2838] p-4 flex justify-between items-center">
            <h2 class="text-2xl font-light text-white tracking-wide">Create New Collection</h2>
            <button onclick="closeNewShelfModal()" class="text-gray-400 hover:text-white transition">X</button>
        </div>

        {{-- FORM BODY --}}
        <div class="p-8 bg-[#212b36] border-t border-black">
            
            <form action="{{ route('shelf.store') }}" method="POST">
                @csrf
                
                {{-- 1. INPUT NAMA SHELF --}}
                <div class="mb-6">
                    <label class="block text-[#3b9de9] text-xs font-bold mb-2 uppercase tracking-wider">COLLECTION NAME</label>
                    <input type="text" name="name" required
                           class="w-full bg-[#1b2838] border border-[#10161d] text-white px-4 py-3 rounded-sm shadow-inner focus:outline-none focus:border-[#3b9de9] focus:bg-[#253347] transition placeholder-gray-500" 
                           placeholder="Enter collection name...">
                </div>

                {{-- 2. PILIHAN MODE (MANUAL / DYNAMIC) --}}
                <div class="mb-6">
                    <label class="block text-[#67c1f5] text-xs font-bold mb-2 uppercase tracking-wider">COLLECTION TYPE</label>
                    <select name="mode" id="shelfMode" onchange="toggleShelfOptions()"
                        class="w-full bg-[#1b2838] border border-[#10161d] text-white px-4 py-3 rounded-sm shadow-inner focus:outline-none focus:border-[#3b9de9]">
                        <option value="manual">Manual Selection (Pick games yourself)</option>
                        <option value="dynamic">Dynamic Collection (Auto-fill by Genre)</option>
                    </select>
                </div>

                {{-- 3. OPSI DINAMIS (Muncul jika pilih Dynamic) --}}
                <div id="dynamicOptions" class="hidden mb-6 p-4 bg-[#1b2838] border border-[#3d4450]">
                    <label class="block text-gray-400 text-xs font-bold mb-2 uppercase">CHOOSE GENRE</label>
                    <select name="genre" class="w-full bg-[#2a3f5a] text-white px-3 py-2 border border-gray-600 rounded-sm">
                        <option value="">-- Select Genre --</option>
                        <option value="Action">Action</option>
                        <option value="RPG">RPG</option>
                        <option value="Adventure">Adventure</option>
                        <option value="Strategy">Strategy</option>
                        <option value="Simulation">Simulation</option>
                        <option value="Racing">Racing</option>
                        <option value="Sports">Sports</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-2">New games with this genre will be added automatically.</p>
                </div>

                {{-- 4. OPSI MANUAL (Muncul jika pilih Manual - Default) --}}
                <div id="manualOptions" class="mb-6">
                    <label class="block text-gray-400 text-xs font-bold mb-2 uppercase">SELECT GAMES</label>
                    <div class="max-h-48 overflow-y-auto bg-[#1b2838] border border-[#10161d] p-2 space-y-1 custom-scrollbar">
                        @if(isset($ownedGames) && $ownedGames->count() > 0)
                            @foreach($ownedGames as $game)
                                <label class="flex items-center space-x-3 p-2 hover:bg-[#2a3f5a] cursor-pointer transition group">
                                    <input type="checkbox" name="selected_games[]" value="{{ $game->id }}" 
                                           class="form-checkbox bg-gray-700 border-gray-600 text-[#3b9de9] rounded-sm focus:ring-0">
                                    <span class="text-gray-300 group-hover:text-white text-sm">{{ $game->title }}</span>
                                </label>
                            @endforeach
                        @else
                            <p class="text-gray-500 text-sm p-2">No games found in your library.</p>
                        @endif
                    </div>
                </div>

                {{-- TOMBOL SUBMIT --}}
                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="closeNewShelfModal()" 
                            class="px-6 py-2 text-gray-400 hover:text-white hover:bg-[#3d4450] rounded-sm transition text-sm font-bold">
                        CANCEL
                    </button>
                    <button type="submit" 
                            class="px-8 py-2 bg-gradient-to-r from-[#47bfff] to-[#1a44c2] hover:brightness-110 text-white rounded-sm shadow-lg text-sm font-bold tracking-wide transition transform hover:translate-y-[-1px]">
                        CREATE COLLECTION
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    function toggleShelfOptions() {
        const mode = document.getElementById('shelfMode').value;
        const manualDiv = document.getElementById('manualOptions');
        const dynamicDiv = document.getElementById('dynamicOptions');

        if (mode === 'dynamic') {
            manualDiv.classList.add('hidden');
            dynamicDiv.classList.remove('hidden');
        } else {
            manualDiv.classList.remove('hidden');
            dynamicDiv.classList.add('hidden');
        }
    }
</script>