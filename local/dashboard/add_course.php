<?php

require_once('../../config.php');
require_once('lib.php');
GLOBAL $USER,$DB;
$uni_id = $_GET['uni_id'];
$course_id = $_GET['course_id'];
$user_id = $DB->get_record_sql("SELECT userid FROM {universityadmin} WHERE university_id=$uni_id");

$package_id = $DB->get_record_sql("SELECT p.* FROM mdl_package p JOIN mdl_admin_subscription mas ON p.id = mas.package_id  WHERE mas.university_id= $uni_id ");


$assign_course = new stdClass();
$count_add = 0;
foreach($course_id as $c_id)
{
	$total_course = $DB->count_records('assign_course', array('university_id'=>$uni_id));

	if ($package_id->num_of_course > $total_course) 
	{
		$count_add = $count_add+1;
		if($c_id)
		{
			$assign_course->university_id = $uni_id;
			$assign_course->course_id = $c_id;
			$inserted = $DB->insert_record('assign_course', $assign_course);
			$role = enrol_try_internal_enrol($c_id, $user_id->userid, 9, time());
			createresource($c_id,$uni_id,$user_id->userid);
		}

		if($inserted)
		{
			$check_uni_id = $DB->get_record_sql("SELECT id,university_id FROM {university_user_course_count} WHERE university_id = $uni_id");
			$total_course = $DB->count_records('assign_course', array('university_id'=>$uni_id));

			$user_course =  new stdClass();
			if ($check_uni_id) 
			{
				$user_course->id = $check_uni_id->id;
				$user_course->course_count = $total_course;
				$updated = $DB->update_record("university_user_course_count", $user_course, false);
			} 
			else 
			{
				$user_course->university_id = $uni_id;
				$user_course->course_count = $total_course;
				$inserted_count = $DB->insert_record("university_user_course_count", $user_course, false);
			}
		}
	}
	else 
	{
		$json = array();
		$json['success'] = false;
		if ($count_add > 0 ) 
		{
			$json['add'] = "Only $count_add Course Addded ";
		}
		$json['msg'] = "Courses Assign Limit Exeed";
		echo json_encode($json);
		exit;
	}
    
}

if($inserted_count || $updated)
{
	$json = array();
	$json['success'] = true;
	$json['msg'] = "Courses Added Successfully!";
	echo json_encode($json);
}

?>