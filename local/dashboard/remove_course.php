<?php

require_once('../../config.php');
require_once('lib.php');
GLOBAL $USER,$DB;
$c_id = $_GET['c_id'];
$u_id= (int)$_GET['uid'];
$user_id = $DB->get_record_sql("SELECT userid FROM {universityadmin} WHERE university_id=$u_id");


if(isset($c_id))
{
    foreach($c_id as $id)
    {
        $deleted = $DB->get_record_sql("SELECT id FROM {assign_course}  WHERE university_id=$u_id AND course_id= '$id'");	        
        if($deleted)
        {
            $DB->delete_records('assign_course', array("id"=>$deleted->id));


            $resourse_course=$DB->get_record_sql("SELECT * FROM {courseresource}  WHERE userid='$user_id->userid' AND course_id= '$id'");
            $instances2 = $DB->get_records('enrol', array('courseid' => $resourse_course->resourcecourseid));
            foreach ($instances2 as $instance2) 
            {
                $plugin = enrol_get_plugin($instance2->enrol);
                $plugin->unenrol_user($instance2, $user_id->userid);
            }
            $sql="DELETE FROM {courseresource} WHERE userid='$user_id->userid' AND course_id= '$id'";
            $DB->execute($sql);

            $DB->delete_records('courseresource', array("id"=>$deleted->id));

            $instances = $DB->get_records('enrol', array('courseid' => $id));
            foreach ($instances as $instance) 
            {
                $plugin = enrol_get_plugin($instance->enrol);
                $plugin->unenrol_user($instance, $user_id->userid);
            }

            $check_uni_id = $DB->get_record_sql("SELECT id,university_id FROM {university_user_course_count} WHERE university_id = $u_id");
            $total_course = $DB->count_records('assign_course', array('university_id'=>$u_id));

            $user_course =  new stdClass();

            $user_course->id = $check_uni_id->id;
            $user_course->course_count = $total_course;
            $updated = $DB->update_record("university_user_course_count", $user_course, false);
        }
    }
}

$json = array();
$json['success'] = true;
$json['msg'] = "Courses remove successfully!";	
echo json_encode($json);
