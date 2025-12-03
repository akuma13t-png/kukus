@props(['games'])

<div id="gamePickerModal" class="fixed inset-0 z-[120] hidden flex items-center justify-center pointer-events-none font-sans">
    
    <div class="absolute inset-0 bg-black/90 backdrop-blur-md opacity-0 transition-opacity duration-300 pointer-events-auto" 
         id="pickerBackdrop" onclick="closeGamePicker()"></div>
    
    <div id="pickerCard" class="bg-[#1b2838] w-full max-w-4xl h-[80vh] flex flex-col border-4 border-black shadow-2xl transform scale-95 opacity-0 transition-all duration-300 pointer-events-auto">
        
        <div class="p-6 border-b border-black bg-[#212b36] flex justify-between items-center">
            <h2 class="text-2xl font-black text-white uppercase tracking-widest">SELECT GAMES</h2>
            <button onclick="closeGamePicker()" class="text-gray-400 hover:text-white font-bold text-xl">X</button>
        </div>

        {{-- LIST GAME (GRID WITH CHECKBOXES) --}}
        <div class="flex-grow overflow-y-auto p-6 bg-[#16202d] custom-scrollbar">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($games as $game)
                    <label class="cursor-pointer group relative">
                        <input type="checkbox" name="selected_games" value="{{ $game->id }}" class="peer hidden">
                        
                        {{-- Tampilan Card --}}
                        <div class="border-4 border-transparent peer-checked:border-green-500 peer-checked:bg-[#2a475e] bg-[#222b35] hover:bg-[#2a3540] p-2 transition rounded relative h-full flex flex-col">
                            <img src="{{ $game->cover_image }}" class="w-full h-32 object-cover mb-2 opacity-80 peer-checked:opacity-100">
                            <span class="text-gray-300 font-bold text-xs peer-checked:text-green-400 leading-tight">{{ $game->title }}</span>
                            
                            {{-- Checkmark Icon --}}
                            <div class="absolute top-2 right-2 bg-green-500 text-black w-6 h-6 flex items-center justify-center rounded-full opacity-0 peer-checked:opacity-100 transition shadow-lg font-bold">
                                ✓
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="p-6 bg-[#212b36] border-t border-black flex justify-end">
            <button onclick="finishCreateShelf('manual')" class="bg-green-600 hover:bg-green-500 text-white font-black px-8 py-3 border-2 border-black shadow-lg hover:translate-y-[-2px] transition">
                CONFIRM SELECTION
            </button>
        </div>
    </div>
</div>