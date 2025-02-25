<?php

function local_time_track_before_footer() {
    global $PAGE;

    $PAGE->requires->js_call_amd('local_time_track/tracker', 'init');
}
