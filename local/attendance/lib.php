<?php
defined('MOODLE_INTERNAL') || die();

function local_myplugin_extend_navigation(global_navigation $nav) {
    global $PAGE;
    $node = $nav->add('My Plugin', new moodle_url('/local/myplugin/index.php'));
    $PAGE->requires->css('/local/attendance/styles.css'); // Load custom CSS
}

function get_attendance_data($userid) {
    global $DB;
    $sql = "SELECT ltt.id, userid, login, logout, classid, c.fullname as coursename, l.name as lessonname, timespent
                FROM {lesson_time_tracking} ltt
                JOIN {lesson} l
                ON ltt.lessonid = l.id
                JOIN {course} c
                ON ltt.courseid = c.id
                Where userid = :userid"
            ;
    $params = ['userid' => $userid];
    $records = $DB->get_records_sql($sql, $params);
    $attendance = [];

    if(empty($records)) {
        return [];
    }

    $weekdays = [
        'Monday' => 'T2',
        'Tuesday' => 'T3',
        'Wednesday' => 'T4',
        'Thursday' => 'T5',
        'Friday' => 'T6',
        'Saturday' => 'T7',
        'Sunday' => 'CN'
    ];

    foreach ($records as $record) {
        $login_day_name = strftime('%A', $record->login);
        $logout_day_name = strftime('%A', $record->logout);
        $login_day_short = $weekdays[$login_day_name] ?? $login_day_name;
        $logout_day_short = $weekdays[$logout_day_name] ?? $logout_day_name;
        $login = $login_day_short . ', ' . userdate($record->login, '%d/%m/%Y, %I:%M%p');
        $logout = $logout_day_short . ', ' . userdate($record->logout, '%d/%m/%Y, %I:%M%p');

        $attendance[] = [
            'userid' => $record->userid,
            'lessonname' => $record->lessonname,
            'login' => $login,
            'logout' => $logout,
            'classid' => $record->classid,
            'coursename' => $record->coursename,
            'timespent' => gmdate("H:i:s", $record->timespent)
        ];
    }

    return $attendance;
}