<div class="max-w-4xl mx-auto bg-[#3e2723] p-8 rounded-xl shadow-2xl border-4 border-[#5d4037]">
    <h3 class="text-3xl font-black text-center mb-8 text-[#d7ccc8] tracking-widest uppercase">Mancala</h3>

    <div class="flex flex-col items-center gap-6">
        
        {{-- GAME INFO --}}
        <div class="flex justify-between w-full px-4 text-[#d7ccc8] font-bold">
            <div class="text-red-300">CPU STORE: <span id="cpu-store-score">0</span></div>
            <div id="turn-indicator" class="text-yellow-400 text-xl animate-pulse">YOUR TURN</div>
            <div class="text-blue-300">YOUR STORE: <span id="p1-store-score">0</span></div>
        </div>

        {{-- BOARD --}}
        <div class="bg-[#5d4037] p-4 rounded-full shadow-inner flex items-center gap-4 border-2 border-[#8d6e63]">
            
            {{-- CPU STORE (Left) --}}
            <div class="w-24 h-48 bg-[#3e2723] rounded-full shadow-inner flex items-center justify-center border border-[#8d6e63] relative">
                <div id="store-cpu" class="text-3xl font-bold text-[#d7ccc8]">0</div>
                <div class="absolute top-2 text-[10px] text-gray-500">CPU</div>
            </div>

            {{-- PITS CONTAINER --}}
            <div class="flex flex-col gap-4">
                {{-- CPU ROW (Top, indices 12-7) --}}
                <div class="flex gap-2">
                    @for($i = 12; $i >= 7; $i--)
                        <div id="pit-{{ $i }}" class="w-16 h-16 bg-[#4e342e] rounded-full shadow-inner flex items-center justify-center cursor-not-allowed border border-[#6d4c41] relative group">
                            <span class="text-xl font-bold text-[#d7ccc8] stones-count">4</span>
                            <div class="absolute -top-6 text-[10px] text-gray-500 opacity-0 group-hover:opacity-100 transition">Pit {{ $i }}</div>
                        </div>
                    @endfor
                </div>

                {{-- PLAYER ROW (Bottom, indices 0-5) --}}
                <div class="flex gap-2">
                    @for($i = 0; $i <= 5; $i++)
                        <button id="pit-{{ $i }}" onclick="playerMove({{ $i }})" class="w-16 h-16 bg-[#5d4037] hover:bg-[#6d4c41] rounded-full shadow-inner flex items-center justify-center transition transform hover:scale-105 border border-[#8d6e63] relative group">
                            <span class="text-xl font-bold text-[#ffcc80] stones-count">4</span>
                            <div class="absolute -bottom-6 text-[10px] text-gray-500 opacity-0 group-hover:opacity-100 transition">Pit {{ $i }}</div>
                        </button>
                    @endfor
                </div>
            </div>

            {{-- PLAYER STORE (Right) --}}
            <div class="w-24 h-48 bg-[#3e2723] rounded-full shadow-inner flex items-center justify-center border border-[#8d6e63] relative">
                <div id="store-p1" class="text-3xl font-bold text-[#ffcc80]">0</div>
                <div class="absolute bottom-2 text-[10px] text-gray-500">YOU</div>
            </div>

        </div>

        <div id="game-message" class="h-8 text-[#ffcc80] font-bold"></div>

    </div>
</div>

<script>
    // Board State: 0-5 (P1 Pits), 6 (P1 Store), 7-12 (CPU Pits), 13 (CPU Store)
    // P1 plays 0-5. CPU plays 7-12.
    // Movement is counter-clockwise (increasing index).
    let board = Array(14).fill(4);
    board[6] = 0; // P1 Store
    board[13] = 0; // CPU Store
    
    let isPlayerTurn = true;
    let gameOver = false;

    function updateUI() {
        // Update pits
        for (let i = 0; i < 14; i++) {
            if (i === 6) {
                document.getElementById('store-p1').innerText = board[i];
                document.getElementById('p1-store-score').innerText = board[i];
            } else if (i === 13) {
                document.getElementById('store-cpu').innerText = board[i];
                document.getElementById('cpu-store-score').innerText = board[i];
            } else {
                const pit = document.getElementById(`pit-${i}`);
                if (pit) {
                    pit.querySelector('.stones-count').innerText = board[i];
                    // Visual feedback for empty pits
                    if (board[i] === 0) pit.classList.add('opacity-50');
                    else pit.classList.remove('opacity-50');
                }
            }
        }

        // Update Turn Indicator
        const ind = document.getElementById('turn-indicator');
        if (gameOver) {
            ind.innerText = "GAME OVER";
            ind.className = "text-white font-black text-2xl";
        } else if (isPlayerTurn) {
            ind.innerText = "YOUR TURN";
            ind.className = "text-blue-400 text-xl animate-pulse font-bold";
        } else {
            ind.innerText = "CPU THINKING...";
            ind.className = "text-red-400 text-xl font-bold";
        }
    }

    function playerMove(pitIndex) {
        if (!isPlayerTurn || gameOver) return;
        if (board[pitIndex] === 0) {
            showMessage("That pit is empty!");
            return;
        }

        sowStones(pitIndex, true);
    }

    function cpuMove() {
        if (gameOver) return;

        // Simple AI: Pick random non-empty pit
        // Better AI: Pick pit that lands in store, or captures
        let validMoves = [];
        for (let i = 7; i <= 12; i++) {
            if (board[i] > 0) validMoves.push(i);
        }

        if (validMoves.length === 0) {
            checkGameOver(); // Should be caught by checkGameOver already
            return;
        }

        // Prioritize move that lands in store (index 13)
        let bestMove = -1;
        for (let move of validMoves) {
            let stones = board[move];
            let endPos = (move + stones) % 14;
            if (endPos === 13) {
                bestMove = move;
                break;
            }
        }

        if (bestMove === -1) {
            bestMove = validMoves[Math.floor(Math.random() * validMoves.length)];
        }

        setTimeout(() => {
            sowStones(bestMove, false);
        }, 1000);
    }

    function sowStones(startIndex, isP1) {
        let stones = board[startIndex];
        board[startIndex] = 0;
        updateUI();

        let currentPos = startIndex;
        
        const interval = setInterval(() => {
            currentPos = (currentPos + 1) % 14;
            
            // Skip opponent's store
            if (isP1 && currentPos === 13) currentPos = 0; // Skip CPU store (13) -> 0
            if (!isP1 && currentPos === 6) currentPos = 7; // Skip P1 store (6) -> 7

            board[currentPos]++;
            stones--;
            updateUI();

            if (stones === 0) {
                clearInterval(interval);
                handleTurnEnd(currentPos, isP1);
            }
        }, 200); // Animation speed
    }

    function handleTurnEnd(endPos, isP1) {
        // Rule: Extra Turn if land in own store
        if (isP1 && endPos === 6) {
            showMessage("Landed in store! Extra turn!");
            checkGameOver();
            return; // Player goes again
        }
        if (!isP1 && endPos === 13) {
            showMessage("CPU gets extra turn!");
            checkGameOver();
            setTimeout(cpuMove, 1000);
            return;
        }

        // Rule: Capture if land in empty pit on own side
        // P1 side: 0-5. CPU side: 7-12.
        const isOwnSide = isP1 ? (endPos >= 0 && endPos <= 5) : (endPos >= 7 && endPos <= 12);
        
        if (isOwnSide && board[endPos] === 1) {
            // Capture opposite
            // Opposite of 0 is 12, 1 is 11... sum is 12.
            // Wait, indices are 0-5 and 7-12.
            // 0 <-> 12, 1 <-> 11, 2 <-> 10, 3 <-> 9, 4 <-> 8, 5 <-> 7.
            // Formula: 12 - index.
            let oppositeIndex = 12 - endPos;
            
            if (board[oppositeIndex] > 0) {
                let captured = board[oppositeIndex] + 1; // +1 for the stone just placed
                board[oppositeIndex] = 0;
                board[endPos] = 0;
                
                if (isP1) board[6] += captured;
                else board[13] += captured;
                
                showMessage(`Captured ${captured} stones!`);
                updateUI();
            }
        }

        checkGameOver();
        
        // Switch turn
        isPlayerTurn = !isPlayerTurn;
        updateUI();
        
        if (!isPlayerTurn && !gameOver) {
            setTimeout(cpuMove, 1000);
        }
    }

    function checkGameOver() {
        let p1Empty = true;
        for (let i = 0; i <= 5; i++) if (board[i] > 0) p1Empty = false;

        let cpuEmpty = true;
        for (let i = 7; i <= 12; i++) if (board[i] > 0) cpuEmpty = false;

        if (p1Empty || cpuEmpty) {
            gameOver = true;
            
            // Collect remaining stones
            for (let i = 0; i <= 5; i++) {
                board[6] += board[i];
                board[i] = 0;
            }
            for (let i = 7; i <= 12; i++) {
                board[13] += board[i];
                board[i] = 0;
            }
            updateUI();

            let p1Score = board[6];
            let cpuScore = board[13];

            if (p1Score > cpuScore) {
                showMessage(`YOU WIN! ${p1Score} - ${cpuScore}`);
                setTimeout(() => claimReward('mancala'), 2000);
            } else if (cpuScore > p1Score) {
                showMessage(`CPU WINS! ${cpuScore} - ${p1Score}`);
                alert("CPU Won! Try again.");
            } else {
                showMessage("It's a TIE!");
            }
        }
    }

    function showMessage(msg) {
        document.getElementById('game-message').innerText = msg;
        setTimeout(() => {
            document.getElementById('game-message').innerText = "";
        }, 2000);
    }

    updateUI();
</script>
