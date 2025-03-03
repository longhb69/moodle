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


$attendance_data = get_attendance_data($userid, $order, $page);
$total_page = $attendance_data['total_pages'];

$data = [
    'userid' => $userid, 
    'username' => fullname($USER),
    'attendance' => $attendance_data['attendance'], 
    'ASC' => $order == 'ASC',
    'DESC' => $order == 'DESC'
];



echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_attendance/manage', $data);

echo $OUTPUT->footer();