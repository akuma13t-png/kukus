<div id="afk-overlay" 
     class="fixed inset-0 z-[9999] bg-black/95 text-white flex flex-col items-center justify-center opacity-0 pointer-events-none transition-opacity duration-1000 backdrop-blur-sm hidden">
    
    {{-- Background Animation (Subtle Particles/Gradient) --}}
    <div class="absolute inset-0 overflow-hidden z-0 opacity-30">
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-blue-900 via-black to-purple-900 animate-pulse"></div>
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl animate-blob"></div>
        <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-1/4 left-1/3 w-96 h-96 bg-teal-500/20 rounded-full blur-3xl animate-blob animation-delay-4000"></div>
    </div>

    {{-- Content --}}
    <div class="z-10 text-center space-y-8 relative">
        {{-- Clock --}}
        <div class="font-mono">
            <div id="afk-time" class="text-9xl font-thin tracking-wider text-white drop-shadow-[0_0_15px_rgba(255,255,255,0.5)]">
                00:00
            </div>
            <div id="afk-date" class="text-2xl text-gray-400 tracking-[0.5em] uppercase mt-4">
                Monday, 01 January
            </div>
        </div>

        {{-- Divider --}}
        <div class="w-24 h-1 bg-white/20 mx-auto rounded-full"></div>

        {{-- Reminder --}}
        <div class="h-12 overflow-hidden relative">
            <div id="afk-reminder" class="text-xl font-light text-blue-200 italic transition-all duration-1000 transform translate-y-0">
                "Time to hydrate."
            </div>
        </div>
    </div>

    {{-- Footer Hint --}}
    <div class="absolute bottom-10 text-gray-500 text-xs tracking-widest uppercase animate-pulse">
        Move mouse to resume
    </div>
</div>

<style>
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 10s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const overlay = document.getElementById('afk-overlay');
        const timeDisplay = document.getElementById('afk-time');
        const dateDisplay = document.getElementById('afk-date');
        const reminderDisplay = document.getElementById('afk-reminder');
        
        let idleTime = 0;
        const idleLimit = 30; // 30 seconds
        let isAfk = false;
        let clockInterval;
        let reminderInterval;

        const reminders = [
            "Remember to drink some water.",
            "Take a deep breath and relax.",
            "Rest your eyes for a moment.",
            "Stretch your back and shoulders.",
            "Stay hydrated, stay focused.",
            "A moment of calm in a busy day.",
            "Check your posture."
        ];

        // Update Clock
        function updateClock() {
            const now = new Date();
            timeDisplay.textContent = now.toLocaleTimeString('en-US', { hour12: false, hour: '2-digit', minute: '2-digit' });
            dateDisplay.textContent = now.toLocaleDateString('en-US', { weekday: 'long', day: '2-digit', month: 'long' });
        }

        // Cycle Reminders
        function updateReminder() {
            // Fade out
            reminderDisplay.style.opacity = '0';
            reminderDisplay.style.transform = 'translateY(10px)';
            
            setTimeout(() => {
                // Change text
                const randomReminder = reminders[Math.floor(Math.random() * reminders.length)];
                reminderDisplay.textContent = `"${randomReminder}"`;
                
                // Fade in
                reminderDisplay.style.opacity = '1';
                reminderDisplay.style.transform = 'translateY(0)';
            }, 500);
        }

        // Activate AFK Mode
        function goAfk() {
            if (isAfk) return;
            isAfk = true;
            
            // Show Overlay
            overlay.classList.remove('hidden');
            // Small delay to allow display:block to apply before opacity transition
            setTimeout(() => {
                overlay.classList.remove('opacity-0', 'pointer-events-none');
            }, 10);

            // Start Clock & Reminders
            updateClock();
            updateReminder();
            clockInterval = setInterval(updateClock, 1000);
            reminderInterval = setInterval(updateReminder, 8000); // Change reminder every 8s
        }

        // Deactivate AFK Mode
        function wakeUp() {
            idleTime = 0; // Reset counter
            
            if (!isAfk) return;
            isAfk = false;

            // Hide Overlay
            overlay.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => {
                overlay.classList.add('hidden');
            }, 1000); // Wait for fade out

            // Stop Intervals
            clearInterval(clockInterval);
            clearInterval(reminderInterval);
        }

        // Idle Timer
        setInterval(() => {
            idleTime++;
            if (idleTime >= idleLimit) {
                goAfk();
            }
        }, 1000);

        // Event Listeners for Activity
        const events = ['mousemove', 'mousedown', 'keypress', 'DOMMouseScroll', 'mousewheel', 'touchmove', 'MSPointerMove'];
        events.forEach(event => {
            document.addEventListener(event, wakeUp, false);
        });
    });
</script>
