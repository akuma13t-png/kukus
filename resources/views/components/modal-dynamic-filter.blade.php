<div id="dynamicFilterModal" class="fixed inset-0 z-[120] hidden flex items-center justify-center pointer-events-none font-sans">
    
    <div class="absolute inset-0 bg-black/90 backdrop-blur-md opacity-0 transition-opacity duration-300 pointer-events-auto" 
         id="filterBackdrop" onclick="closeDynamicFilter()"></div>
    
    <div id="filterCard" class="bg-[#1b2838] w-full max-w-lg p-1 border-4 border-black shadow-2xl transform scale-95 opacity-0 transition-all duration-300 pointer-events-auto">
        
        <div class="p-6 border-b border-black bg-[#212b36]">
            <h2 class="text-xl font-black text-white uppercase tracking-widest flex items-center gap-2">
                <span class="text-yellow-400">⚡</span> DYNAMIC FILTER
            </h2>
            <p class="text-gray-400 text-xs mt-1">Collection will update automatically.</p>
        </div>

        <div class="p-8 bg-[#16202d]">
            <label class="block text-blue-400 text-xs font-bold mb-4 uppercase tracking-wider">SELECT A GENRE</label>
            
            <select id="dynamicGenreSelect" class="w-full bg-[#1b2838] text-white border-2 border-gray-600 p-3 font-bold focus:border-blue-500 outline-none rounded-none">
                <option value="Action">Action</option>
                <option value="RPG">RPG</option>
                <option value="Strategy">Strategy</option>
                <option value="Simulation">Simulation</option>
                <option value="Adventure">Adventure</option>
                <option value="Indie">Indie</option>
                <option value="Horror">Horror</option>
                <option value="Racing">Racing</option>
            </select>

            <div class="mt-4 p-3 bg-blue-900/30 border border-blue-500/50 text-blue-300 text-xs">
                ℹ️ All current and future games with this genre will appear in this shelf.
            </div>
        </div>

        <div class="p-6 bg-[#212b36] border-t border-black flex justify-end">
            <button onclick="finishCreateShelf('dynamic')" class="bg-blue-600 hover:bg-blue-500 text-white font-black px-8 py-3 border-2 border-black shadow-lg hover:translate-y-[-2px] transition">
                CREATE DYNAMIC SHELF
            </button>
        </div>
    </div>
</div>