<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');
require_once($CFG->dirroot . '/mod/lesson/lib.php'); // Include lib.php
require_login();

$userid = $USER->id;
$cmid = required_param('cmid', PARAM_INT);
$logout = optional_param('logout', 0, PARAM_INT);
$action = optional_param('action', '', PARAM_ALPHANUMEXT); //for underscores _

$cm = get_coursemodule_from_id('lesson', $cmid);
if (!$cm) {
    echo json_encode(['status' => 'failed', 'error' => 'Invalid cmid']);
    die();
}

$lessonid = $cm->instance;
$courseid = $cm->course;

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch($action) {
        case 'get_lesson_time':
            $lesson_time = get_lesson_time_for_this_user($lessonid, $courseid);
            if($lesson_time >= 0) {
                echo json_encode([
                    'status' => 'success',
                    'lesson_time' => $lesson_time //time in seconds
                ]); 
            }
            break; 
        default: 
            echo json_encode(['status' => 'failed', 'error' => "Invalid action ($action)"]);
    }
    die();
}

if($_SERVER['REQUEST_METHOD' === 'POST']) {
    switch($action) {
        default:
            echo json_decode(['status' => 'failed', 'error' => "Invalid action ($action)"]);
    }
}

$success = track_lesson_end($lessonid, $userid, $logout);

if($success) {
    echo json_encode(['status' => 'success', 'message' => 'Lesson ended successfully', 'logout' => $logout]);
}
else {
    echo json_encode(['status' => 'failed']);
}


