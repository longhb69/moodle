<?php

require_once('../../config.php');
require_once('lib.php');

$PAGE->set_url('/attendance/manage.php');
$PAGE->requires->css('/local/attendance/styles.css');
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Điểm Danh');

require_login(); 

global $USER;
$userid = $USER->id;


$data = [
    'userid' => $userid, 
    'username' => fullname($USER),
    'attendance' => get_attendance_data($userid)
];


echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_attendance/manage', $data);

echo $OUTPUT->footer();