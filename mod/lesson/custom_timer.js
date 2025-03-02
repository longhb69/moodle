document.addEventListener("DOMContentLoaded", async function () {
    const timerElement = document.getElementById("lesson-timer");
    const loadingElement = document.querySelector(".lesson-loading");

    function updateTimerDisplay(seconds) {
        const hours = Math.floor(seconds/3600);
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        timerElement.textContent = `${hours}:${minutes.toString().padStart(2,'0')}:${remainingSeconds.toString().padStart(2, '0')}`;
    }

    function startTimer(lesson_time) {
        updateTimerDisplay(lesson_time)

        setTimeout(() => {
            const countdown = setInterval(() => {
                if(lesson_time <= 0) {
                    console.log("Finish");
                    clearInterval(countdown);
                    return;
                }
                lesson_time --;
                updateTimerDisplay(lesson_time);
            }, 1000);
        })
    }

    async function getLessonTime() {
        return new Promise((resolve, reject) => {
            setTimeout(() => { 
                const id = getIdFromUrl();
                if(!id) {
                    console.log("ID not found in URL!");
                    reject("cmid not found");
                    return;
                }
                require(['jquery'], function($) {
                    $.ajax({
                        url: 'http://localhost/moodle/mod/lesson/ajax.php',
                        data: { cmid: id, action: 'get_lesson_time'},
                        type: 'GET',
                        dataType: 'json',
                        success: function (response) {
                            console.log("AJAX Response: ", response);
                            if(response.status === 'success' && response.lesson_time != null) {
                                resolve(parseInt(response.lesson_time, 10));
                            }
                            else {
                                reject("cannot get lesson time");
                            }
                        },
                        error: function () {
                            reject("AJAX request failed");
                        }
                    });
                });
            }, 3000);
        })
    }

    try {
        const lessonTime = await getLessonTime();
        if(lessonTime > 0) {
            loadingElement.style.display = "none";
            timerElement.style.display = "block";
            startTimer(lessonTime)
        }
    } catch(error) {
        console.error("Error fetching lesson time:", error);
    }
});



function getIdFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id'); 
}

function handleExit(logoutTime) {
    const id = getIdFromUrl();
     onsole.log(logoutTime)
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


