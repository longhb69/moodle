console.log("custom.js Loaded !");
document.addEventListener("DOMContentLoaded", function () {
    let countdownElement = document.getElementById("m-countdown");

    if (!countdownElement) {
        console.error("Countdown element not found!");
        return;
    }

    // Set initial countdown time (in seconds)
    let remainingTime = 3600; // 1 hour

    function updateCountdown() {
        let hours = Math.floor(remainingTime / 3600);
        let minutes = Math.floor((remainingTime % 3600) / 60);
        let seconds = remainingTime % 60;

        // Format time as HH:MM:SS
        let formattedTime = 
            String(hours).padStart(2, "0") + ":" +
            String(minutes).padStart(2, "0") + ":" +
            String(seconds).padStart(2, "0");

        countdownElement.textContent = formattedTime;

        if (remainingTime > 0) {
            remainingTime--;
        } else {
            clearInterval(timer);
        }
    }

    // Start countdown
    let timer = setInterval(updateCountdown, 1000);
    updateCountdown(); // Run immediately to prevent delay
});


