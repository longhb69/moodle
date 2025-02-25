document.addEventListener("DOMContentLoaded", function () {
    let n = 5;
    const timerElement = document.getElementById("lesson-timer");

    function updateTimerDisplay(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        timerElement.textContent = `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
    }

    updateTimerDisplay(n); // Initialize display

     setTimeout(() => { // Small delay to prevent Moodle's caching issues
        const countdown = setInterval(() => {
            if (n <= 0) {
                clearInterval(countdown);
                const logoutTime = Math.floor(Date.now() / 1000);
                test(logoutTime);
                return;
            }
            n--;
            updateTimerDisplay(n);
        }, 1000);
    }, 0); // Start immediately
});

function getIdFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id'); 
}

function test(logoutTime) {
    const id = getIdFromUrl();
     console.log(logoutTime)
    console.log(id)
     if (!id) {
        console.error("ID not found in URL!");
        return;
    }

    require(['jquery'], function($) {
        $.ajax({
            url: 'http://localhost/moodle/mod/lesson/ajax.php',
            data: { 
                cmid: id,
                logout: logoutTime
            },
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                console.log("AJAX Success:", response);
                if (response.status !== 'success') {
                    console.error("Database update failed!", response);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error, xhr.responseText);
            }
        });
    }); 
}

require(['jquery'], function($) {
    console.log("jQuery is loaded!", $);

    $(document).ready(function () {
        console.log("Document ready - jQuery works!");
    });

});


