<div class="flex flex-col items-center">
    <div id="board" class="grid grid-cols-3 gap-2 mb-4">
        @for($i = 0; $i < 9; $i++)
            <button onclick="makeMove({{ $i }})" class="cell w-24 h-24 bg-[#2a475e] text-4xl font-bold flex items-center justify-center hover:bg-[#3d5f7a] rounded"></button>
        @endfor
    </div>
    <p id="status" class="text-xl font-bold mb-4">Your Turn (X)</p>
    <button onclick="resetGame()" class="bg-gray-600 px-4 py-2 rounded">Reset</button>
</div>

<script>
    let board = ['', '', '', '', '', '', '', '', ''];
    let currentPlayer = 'X';
    let gameActive = true;

    function makeMove(index) {
        if (board[index] === '' && gameActive) {
            board[index] = currentPlayer;
            document.getElementsByClassName('cell')[index].innerText = currentPlayer;
            
            if (checkWin()) {
                document.getElementById('status').innerText = currentPlayer + ' Wins!';
                gameActive = false;
                if(currentPlayer === 'X') {
                    setTimeout(() => claimReward('tictactoe'), 1000);
                }
                return;
            }

            if (board.includes('')) {
                currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
                if (currentPlayer === 'O') {
                    document.getElementById('status').innerText = "AI's Turn...";
                    setTimeout(aiMove, 500);
                } else {
                    document.getElementById('status').innerText = "Your Turn (X)";
                }
            } else {
                document.getElementById('status').innerText = "Draw!";
                gameActive = false;
            }
        }
    }

    function aiMove() {
        if (!gameActive) return;
        let available = board.map((val, idx) => val === '' ? idx : null).filter(val => val !== null);
        let move = available[Math.floor(Math.random() * available.length)];
        makeMove(move);
    }

    function checkWin() {
        const conditions = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8],
            [0, 3, 6], [1, 4, 7], [2, 5, 8],
            [0, 4, 8], [2, 4, 6]
        ];
        return conditions.some(condition => {
            return condition.every(index => {
                return board[index] === currentPlayer;
            });
        });
    }

    function resetGame() {
        board = ['', '', '', '', '', '', '', '', ''];
        currentPlayer = 'X';
        gameActive = true;
        document.getElementById('status').innerText = "Your Turn (X)";
        Array.from(document.getElementsByClassName('cell')).forEach(cell => cell.innerText = '');
    }
</script>
