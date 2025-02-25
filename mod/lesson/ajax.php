<?php
define('AJAX_SCRIPT', true);

require_once('../../config.php');
require_once($CFG->dirroot . '/mod/lesson/lib.php'); // Include lib.php

require_login();

$userid = $USER->id;
$cmid = required_param('cmid', PARAM_INT);
$logout = required_param('logout', PARAM_INT);

$cm = get_coursemodule_from_id('lesson', $cmid);
if (!$cm) {
    echo json_encode(['status' => 'failed', 'error' => 'Invalid cmid']);
    die();
}

$lessonid = $cm->instance;


$success = track_lesson_end($lessonid, $userid, $logout);
if($success) {
    echo json_encode(['status' => 'success', 'message' => 'Lesson ended successfully', 'logout' => $logout]);
}
else {
    echo json_encode(['status' => 'failed']);
}

die;