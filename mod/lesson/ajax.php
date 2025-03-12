<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');
require_once($CFG->dirroot . '/mod/lesson/lib.php'); // Include lib.php
require_once($CFG->dirroot . '/course/lib.php');
require_login();

$userid = $USER->id;
$logout = optional_param('logout', 0, PARAM_INT);
$action = optional_param('action', '', PARAM_ALPHANUMEXT); //for underscores _


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch($action) {
        case 'get_lesson_time':
            $cmid = required_param('cmid', PARAM_INT);
            $cm = get_coursemodule_from_id('lesson', $cmid);
            if (!$cm) {
                echo json_encode(['status' => 'failed', 'error' => 'Invalid cmid']);
                die();
            }

            $lessonid = $cm->instance;
            $courseid = $cm->course;
            $lesson_time = get_lesson_time_for_this_user($lessonid, $courseid);
            if($lesson_time >= 0) {
                echo json_encode([
                    'status' => 'success',
                    'lesson_time' => $lesson_time //time in seconds
                ]); 
            }
            break; 
        case 'start_lesson_time':
            $cmid = required_param('cmid', PARAM_INT);
            $cm = get_coursemodule_from_id('lesson', $cmid);
            if (!$cm) {
                echo json_encode(['status' => 'failed', 'error' => 'Invalid cmid']);
                die();
            }

            $lessonid = $cm->instance;
            $courseid = $cm->course;
            track_lesson_start($lessonid, $courseid, 1, $userid);
            break;
        case 'get_course_progression':
            $courseid = required_param('courseid', PARAM_INT);
            $outcome = get_course_user_data($courseid);
            if($outcome) {
                echo json_encode([
                    'status' => 'success',
                    'data' => $outcome
                ]);
            } else {
                echo json_encode(['status' => 'failed', 'error' => 'Course progression data not found']);
            }
            break;
        default: 
            echo json_encode(['status' => 'failed', 'error' => "Invalid action ($action)"]);
    }
    die();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch($action) {
        case 'user_finish_lesson':
            $cmid = required_param('cmid', PARAM_INT);
            $cm = get_coursemodule_from_id('lesson', $cmid);
            if (!$cm) {
                echo json_encode(['status' => 'failed', 'error' => 'Invalid cmid']);
                die();
            }

            $lessonid = $cm->instance;
            $success = track_lesson_end($lessonid, $userid);
            if($success) {
                echo json_encode([
                    'status' => 'success',
                ]);
            }
            break;
        default:
            echo json_encode(['status' => 'failed', 'error' => "Invalid action ($action)"]);
    }
    die();
}

// if($success) {
//     echo json_encode(['status' => 'success', 'message' => 'Lesson ended successfully', 'logout' => $logout]);
// }
// else {
//     echo json_encode(['status' => 'failed']);
// }


