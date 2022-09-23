<?php

require_once('../../config.php');
require_once('lib.php');
GLOBAL $USER,$DB;
$c_id = $_GET['c_id'];
$u_id= (int)$_GET['uid'];
$user_id = $DB->get_record_sql("SELECT userid FROM {universityadmin} WHERE universityid=$u_id");


if(isset($c_id))
{
    foreach($c_id as $id)
    {
        $deleted = $DB->get_record_sql("SELECT id FROM {assign_course}  WHERE university_id=$u_id AND course_id= '$id'");	        
        if($deleted)
        {
            $DB->delete_records('assign_course', array("id"=>$deleted->id));
            $instances = $DB->get_records('enrol', array('courseid' => $id));
            foreach ($instances as $instance) {
            $plugin = enrol_get_plugin($instance->enrol);
            $plugin->unenrol_user($instance, $user_id->userid);
            }
        }
    }
}

$json = array();
$json['success'] = true;
$json['msg'] = "Courses remove successfully!";	
echo json_encode($json);
