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

$order = optional_param('order', 'DESC', PARAM_ALPHA);
$page = optional_param('page', 1,PARAM_INT);

//$new_order = ($order === 'DESC') ? 'ASC' : 'DESC';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$attendance_data = get_attendance_data($userid, $order, $page);

$data = [
    'userid' => $userid, 
    'username' => fullname($USER),
    'attendance' => $attendance_data['attendance'], 
    'total_pages' => $attendance_data['total_pages'], 
    'page' => $page ? $page : 1,
    'order' => $order,
    'ASC' => $order == 'ASC',
    'DESC' => $order == 'DESC'
];



echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_attendance/manage', $data);

echo $OUTPUT->footer();