<div class="flex flex-col items-center gap-8">
    <div class="flex gap-8 text-center">
        <div>
            <p class="mb-2 font-bold">YOU</p>
            <div id="player-choice" class="text-6xl">❓</div>
        </div>
        <div class="flex items-center text-2xl font-bold">VS</div>
        <div>
            <p class="mb-2 font-bold">AI</p>
            <div id="ai-choice" class="text-6xl">❓</div>
        </div>
    </div>

    <p id="rps-result" class="text-2xl font-bold text-yellow-400 h-8"></p>

    <div class="flex gap-4">
        <button onclick="playRPS('rock')" class="bg-[#2a475e] p-4 rounded-full text-4xl hover:bg-[#3d5f7a] transition transform hover:scale-110">🪨</button>
        <button onclick="playRPS('paper')" class="bg-[#2a475e] p-4 rounded-full text-4xl hover:bg-[#3d5f7a] transition transform hover:scale-110">📄</button>
        <button onclick="playRPS('scissors')" class="bg-[#2a475e] p-4 rounded-full text-4xl hover:bg-[#3d5f7a] transition transform hover:scale-110">✂️</button>
    </div>
</div>

<script>
    function playRPS(playerChoice) {
        const choices = ['rock', 'paper', 'scissors'];
        const icons = {'rock': '🪨', 'paper': '📄', 'scissors': '✂️'};
        const aiChoice = choices[Math.floor(Math.random() * 3)];

        document.getElementById('player-choice').innerText = icons[playerChoice];
        document.getElementById('ai-choice').innerText = icons[aiChoice];

        let result = '';
        if (playerChoice === aiChoice) {
            result = "It's a Draw!";
            document.getElementById('rps-result').className = "text-2xl font-bold text-gray-400 h-8";
        } else if (
            (playerChoice === 'rock' && aiChoice === 'scissors') ||
            (playerChoice === 'paper' && aiChoice === 'rock') ||
            (playerChoice === 'scissors' && aiChoice === 'paper')
        ) {
            result = "You Win!";
            document.getElementById('rps-result').className = "text-2xl font-bold text-green-400 h-8";
            setTimeout(() => claimReward('rps'), 1000);
        } else {
            result = "You Lose!";
            document.getElementById('rps-result').className = "text-2xl font-bold text-red-400 h-8";
        }

        document.getElementById('rps-result').innerText = result;
    }
</script>
