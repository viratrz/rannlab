<?php

require_once('../../config.php');
require_once('lib.php');
GLOBAL $USER,$DB;
$uni_id = $_GET['uni_id'];
$course_id = $_GET['course_id'];
$user_id = $DB->get_record_sql("SELECT userid FROM {universityadmin} WHERE universityid=$uni_id");

$data = new stdClass();

foreach($course_id as $c_id)
{
    if($c_id)
	{

	  $data->university_id = $uni_id;
	  $data->course_id = $c_id;
      $inserted = $DB->insert_record('assign_course', $data);
	  enrol_try_internal_enrol($c_id, $user_id->userid, 3, time());
	}
}
if($inserted){
$json = array();
$json['success'] = true;
$json['msg'] = "Courses Added Successfully!";
echo json_encode($json);
}

?>