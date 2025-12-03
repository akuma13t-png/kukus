<div id="newShelfModal" class="fixed inset-0 z-[110] hidden flex items-center justify-center pointer-events-none font-sans">
    
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm opacity-0 transition-opacity duration-300 pointer-events-auto" 
         id="newShelfBackdrop" onclick="closeNewShelfModal()"></div>
    
    <div id="newShelfCard" class="bg-[#1b2838] w-full max-w-2xl p-1 border-t-4 border-[#3d4450] shadow-2xl transform scale-95 opacity-0 transition-all duration-300 pointer-events-auto relative">
        
        <div class="bg-[#1b2838] p-4 flex justify-between items-center">
            <h2 class="text-2xl font-light text-white tracking-wide">Create New Collection</h2>
            <button onclick="closeNewShelfModal()" class="text-gray-400 hover:text-white transition">X</button>
        </div>

        <div class="p-8 bg-[#212b36] border-t border-black">
            
            <div class="mb-8">
                <label class="block text-[#3b9de9] text-xs font-bold mb-2 uppercase tracking-wider">COLLECTION NAME</label>
                <input type="text" id="shelfNameInput" 
                       class="w-full bg-[#1b2838] border border-[#10161d] text-white px-4 py-3 rounded-sm shadow-inner focus:outline-none focus:border-[#3b9de9] focus:bg-[#253347] transition" 
                       placeholder="Collection Name...">
            </div>

            <label class="block text-[#67c1f5] text-xs font-bold mb-4 uppercase tracking-wider">SELECT A COLLECTION TYPE</label>

            <div class="grid grid-cols-2 gap-6">
                {{-- OPSI 1: MANUAL PICKER --}}
                <button onclick="proceedToGamePicker()" class="group text-left bg-[#263242] hover:bg-[#3d4d5d] border border-transparent hover:border-gray-500 p-0 transition flex flex-col h-full shadow-lg">
                    <div class="bg-[#3d4d5d] group-hover:bg-[#4b5c6d] text-white font-bold text-center py-3 uppercase tracking-wider transition border-b border-black">
                        CREATE COLLECTION
                    </div>
                    <div class="p-4 text-gray-400 text-sm flex-grow leading-relaxed">
                        Manually select specific games to add to this collection. Good for favorites or custom lists.
                    </div>
                </button>
                
                {{-- OPSI 2: DYNAMIC FILTER --}}
                <button onclick="proceedToDynamicFilter()" class="group text-left bg-[#263242] hover:bg-[#3d4d5d] border border-transparent hover:border-gray-500 p-0 transition flex flex-col h-full shadow-lg">
                    <div class="bg-blue-900 group-hover:bg-blue-800 text-white font-bold text-center py-3 uppercase tracking-wider flex items-center justify-center gap-2 border-b border-black">
                        <span class="text-yellow-400">⚡</span> DYNAMIC COLLECTION
                    </div>
                    <div class="p-4 text-gray-400 text-sm flex-grow leading-relaxed">
                        Select filters (like Genre). New games matching these filters will be added automatically.
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>