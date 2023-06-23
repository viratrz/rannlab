<?php
    require_once '../../config.php';  
    global $CFG, $DB;
    require_once($CFG->dirroot.'/lib/odslib.class.php');
    require_once($CFG->dirroot.'/grade/export/lib.php');

    $all_university_admin = $DB->get_records_sql("SELECT DISTINCT university_id, userid FROM mdl_universityadmin");
    
    $workbook = new MoodleODSWorkbook("-");
    $workbook->send('Student Progress Report.ods');
    $myxls=$workbook->add_worksheet('Progress Report');
    $myxls->write_string(0,0,'Student First Name');
    $myxls->write_string(0,1,'Student Last Name');
    $myxls->write_string(0,2,'Student ID');
    $myxls->write_string(0,3,'Course code');
    $myxls->write_string(0,4,'Pass');
    $myxls->write_string(0,5,'Pending');
    $myxls->write_string(0,6,'Fail');
    $myxls->write_string(0,7,'Total');
    $total_admin_records = count($all_university_admin);
    $increment = 1;
    foreach ($all_university_admin as $university_admin) { 

        $school = $DB->get_record("school", ['id'=>$university_admin->university_id]);
        $user = $DB->get_record("user", ['id'=>$university_admin->userid]);
        $get_package_id = $DB->get_record("admin_subscription", ['university_id'=>$university_admin->university_id]);
        $get_package_info = $DB->get_record("package", ['id'=>$get_package_id->package_id]);
        $course_users_info = $DB->get_record("university_user_course_count", ['university_id'=>$university_admin->university_id]);
        $course = $DB->get_record("courseresource", ['university_id'=>$university_admin->university_id]);
        
        $myxls->write_string($increment,0,"$user->firstname");
        $myxls->write_string($increment,1,"$user->lastname");
        $myxls->write_string($increment,2,"$user->id");
        $myxls->write_string($increment,3,"$course->course_id");
        

        $increment++;
    }
    $workbook->close();
?>