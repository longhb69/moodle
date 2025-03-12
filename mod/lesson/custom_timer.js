document.addEventListener("DOMContentLoaded", async function () {
    const timerElement = document.getElementById("lesson-timer");
    const loadingElement = document.querySelector(".lesson-loading");

    function updateTimerDisplay(seconds) {
        const hours = Math.floor(seconds/3600);
        const minutes = Math.floor( (seconds % 3600) / 60);
        const remainingSeconds = seconds % 60;
        timerElement.textContent = `${hours}:${minutes.toString().padStart(2,'0')}:${remainingSeconds.toString().padStart(2, '0')}`;
    }

    function startTimer(lesson_time) {
        let remainingTime = lesson_time;
        let lastPauseTime = Date.now()
        let countdownActive = true

        function update() {
            if(!countdownActive) return;

            const elapsedTime = Math.floor((Date.now() - lastPauseTime) / 1000);
            remainingTime = Math.max(0, lesson_time - elapsedTime)
            updateTimerDisplay(remainingTime)

            if(remainingTime > 0) {
                requestAnimationFrame(update)
            } else {
                console.log('Finish')
                handleExit()
            }
        }

        update()

        document.addEventListener('visibilitychange', function handleVisibility() {
            if(document.hidden) {
                countdownActive = false;
                lastPauseTime = Date.now()
                console.log("Paused");
            } else {
                countdownActive = true;
                lesson_time = remainingTime //Reset to reaming time
                lastPauseTime = Date.now();
                console.log("Resumed");
                update(); // Restart the timer
            }
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
            }, 0);
        })
    }

    async function startLesson() {
        const id = getIdFromUrl();
        require(['jquery'], function($) {
            $.ajax({
                url: 'http://localhost/moodle/mod/lesson/ajax.php',
                data: { cmid: id, action: 'start_lesson_time'},
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if(response.status === 'success' ) {
                        console.log("Begin track lesson")
                    }
                },
                error: function () {
                    reject("AJAX request failed");
                }
            });
        });
    }

    try {
        await startLesson();
        const lessonTime = await getLessonTime();
        if(lessonTime >= 0) {
            loadingElement.style.display = "none";
            timerElement.style.display = "block";
            startTimer(lessonTime)
        }
    } catch(error) {
        console.error("Error fetching lesson time:", error);
    }

    window.addEventListener("beforeunload", function (event) {
        handleExit();
    });
});



function getIdFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id'); 
}

//don't use logouttime
function handleExit() {
    const id = getIdFromUrl();
    if (!id) {
        console.error("ID not found in URL!");
        return;
    }

    require(['jquery'], function($) {
        $.ajax({
            url: 'http://localhost/moodle/mod/lesson/ajax.php',
            data: { 
                cmid: id,
                action: 'user_finish_lesson'
            },
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    console.log("AJAX Success:");
                }
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


