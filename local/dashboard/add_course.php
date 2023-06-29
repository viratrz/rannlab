<?php
use core\task\manager;
use local_dashboard\task\assign_courses;
require_once('../../user/lib.php');
// require_once($CFG->libdir.'/adminlib.php');
require_once('../../config.php');
require_once('lib.php');
GLOBAL $USER,$DB;
$uni_id = $_GET['uni_id'];
$course_id = $_POST['course_id'];
$user_id = $DB->get_record_sql("SELECT userid FROM mdl_universityadmin WHERE university_id=$uni_id");
$package_id = $DB->get_record_sql("SELECT p.* FROM mdl_package p JOIN mdl_admin_subscription mas ON p.id = mas.package_id  WHERE mas.university_id= $uni_id ");
$assign_course = new stdClass();
$count_add = 0;
$tomorrow = new DateTime("now", core_date::get_server_timezone_object());
try {
	$transaction = $DB->start_delegated_transaction();
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
				$assign_course->is_pending = 1;
				$inserted = $DB->insert_record('assign_course', $assign_course);
			}

			if($inserted)
			{
				$adhoc_assign_courses = new assign_courses();
				manager::queue_adhoc_task($adhoc_assign_courses, true);
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
			$transaction->allow_commit();

			$json = array();
			$json['success'] = false;
			if ($count_add > 0 ) 
			{
				$json['add'] = "Only $count_add Course Added";
			}
			$json['msg'] = "Courses Assign Limit Exeed";
			echo json_encode($json);
			exit;
		}
	}
	$transaction->allow_commit();

} catch (Exception $e) {
	$transaction->rollback($e);
	$json = array();
	$json['success'] = false;
	$json['msg'] = "Courses Not Added Successfully!";
	echo json_encode($json);
	exit();
}


if($inserted_count || $updated)
{
	$json = array();
	$json['success'] = true;
	$json['msg'] = "Courses Added Successfully!";
	echo json_encode($json);
}
else{
	$json = array();
	$json['success'] = false;
	$json['msg'] = "Courses Not Added Successfully!";
	echo json_encode($json);
}

?>