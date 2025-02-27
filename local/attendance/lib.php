<?php
defined('MOODLE_INTERNAL') || die();

function local_myplugin_extend_navigation(global_navigation $nav) {
    global $PAGE;
    $node = $nav->add('My Plugin', new moodle_url('/local/myplugin/index.php'));
    $PAGE->requires->css('/local/attendance/styles.css'); // Load custom CSS
}

function get_attendance_data($userid) {
    global $DB;
    $sql = "SELECT * FROM {lesson_time_tracking} Where userid = :userid";
    $params = ['userid' => $userid];
    $records = $DB->get_records_sql($sql, $params);
    $attendance = [];

    if(empty($records)) {
        return [];
    }

    foreach ($records as $record) {
        $attendance[] = [
            'userid' => $record->userid,
            'lessonid' => $record->lessonid,
            'login' => userdate($record->login),
            'logout' => userdate($record->logout),
            'classid' => $record->classid,
            'courseid' => $record->courseid,
            'timespent' => gmdate("H:i:s", $record->timespent)
        ];
    }

    return $attendance;
}