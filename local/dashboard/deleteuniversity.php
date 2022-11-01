<?php
require_once('../../config.php');
require_once('lib.php');
require_once('../../user/lib.php');

GLOBAL $USER,$DB;

$del_id = $_GET['del_id'];
$del_university_users = $DB->get_records_sql("SELECT ua.userid FROM mdl_universityadmin ua WHERE ua.university_id = $del_id UNION  SELECT uu.userid FROM mdl_university_user uu WHERE uu.university_id =$del_id");

$name =$DB->get_record_sql("SELECT name FROM {school} WHERE id='$del_id'");
$deleted_university = $DB->delete_records('school', array('id'=>$del_id));

if ($deleted_university) 
{
    $DB->delete_records('admin_subscription', array('university_id'=>$del_id));
    $DB->delete_records('universityadmin', array('university_id'=>$del_id));
    $DB->delete_records('university_user', array('university_id'=>$del_id));

    foreach ($del_university_users as $del_user) 
    {
        $user = $DB->get_record('user', array('id' => $del_user->userid, 'deleted' => 0), '*', MUST_EXIST);
        user_delete_user($user);
    }
}

if ($deleted_university) 
{
    redirect("table.php", "University '$name->name' Deleted Successfully", null, \core\output\notification::NOTIFY_INFO);
}
?>
