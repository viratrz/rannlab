<?php
require_once('../../config.php');
require_once('lib.php');

GLOBAL $USER,$DB;

$del_id = $_GET['del_id'];
$deletedadminid = $DB->get_record_sql("SELECT userid FROM {universityadmin} WHERE university_id = $del_id");
$name =$DB->get_record_sql("SELECT name FROM {school} WHERE id='$del_id'");
$table ='user';
$table1 ='user';
$deleteduniversity = $DB->delete_records('school', array('id'=>$del_id));
$deletedadmin = $DB->delete_records('user', array('id'=>$deletedadminid->userid));

if ($deleteduniversity) 
{
    if ($deletedadmin) 
    {
        redirect("table.php", "University '$name->name' Deleted Successfully", null, \core\output\notification::NOTIFY_INFO);

    }
}
