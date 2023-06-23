<?php
require_once('../../config.php');
require_once('lib.php');
require_login();

global $USER, $DB;
$categoryid = (int)$_POST['id'];
$all_packages = $DB->get_records_sql("SELECT * FROM {package}");
$category11 = $DB->get_records_sql("SELECT * FROM {course_categories}");
$resource_course_id = $DB->get_record("course_categories",['idnumber'=>'resourcecat']);
$all_courses = $DB->get_records_sql("SELECT * FROM {course} WHERE category !=0 AND category !=$resource_course_id->id AND category = $categoryid ");
echo json_encode(array('courses' => $all_courses ));
exit;

?>