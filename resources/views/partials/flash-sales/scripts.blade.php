@pushOnce('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Flash sale countdown timers
        document.querySelectorAll('.flash-sale-timer').forEach(timerEl => {
            const endTime = new Date(timerEl.dataset.end).getTime();
            const timerDisplay = timerEl.querySelector('.timer-display') || 
                               document.getElementById('flash-timer');
            
            if (!timerDisplay) return;
            
            const updateTimer = function() {
                const now = new Date().getTime();
                const distance = endTime - now;
                
                if (distance <= 0) {
                    timerDisplay.textContent = 'Expired';
                    return;
                }
                
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                // If more than a day is left, show days
                if (days > 0) {
                    timerDisplay.textContent = 
                        days + 'd ' +
                        (hours < 10 ? '0' + hours : hours) + ':' +
                        (minutes < 10 ? '0' + minutes : minutes) + ':' +
                        (seconds < 10 ? '0' + seconds : seconds);
                } else {
                    timerDisplay.textContent = 
                        (hours < 10 ? '0' + hours : hours) + ':' +
                        (minutes < 10 ? '0' + minutes : minutes) + ':' +
                        (seconds < 10 ? '0' + seconds : seconds);
                }
            };
            
            updateTimer();
            setInterval(updateTimer, 1000);
        });
    });
</script>
@endPushOnce 