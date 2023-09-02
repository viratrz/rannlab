<?php

require_once('../../config.php');
require_login();
$roles = get_user_roles(context_system::instance(), $USER->id, true);
$role_shortnames = [];
foreach ($roles as $role) {
  $role_shortnames[] = $role->shortname;
}
if(!in_array('trainer',$role_shortnames) && !in_array('rtoadmin',$role_shortnames)){
  $homepage = new moodle_url('/my');
  redirect($homepage,'Not authorise to see assessment due.',null,\core\output\notification::NOTIFY_INFO);
}
$title = 'Assessment Due';
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_url(new moodle_url('/local/dashboard/assessment_due.php'));
$PAGE->set_pagelayout('standard');
$assessment_due_rtoadmin = new \local_dashboard\table\assessment_due_rtoadmin('assessment_due_rtoadmin');
echo $OUTPUT->header();
echo $assessment_due_rtoadmin->out(10,'user');
echo $OUTPUT->footer();
