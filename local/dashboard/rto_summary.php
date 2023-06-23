<?php
    require_once '../../config.php';  
    global $CFG, $DB;
    require_once($CFG->dirroot.'/lib/odslib.class.php');
    require_once($CFG->dirroot.'/grade/export/lib.php');

    $all_university_admin = $DB->get_records_sql("SELECT DISTINCT university_id, userid FROM mdl_universityadmin");
    
    $workbook = new MoodleODSWorkbook("-");
    $workbook->send('test.ods');
    $myxls=$workbook->add_worksheet('RTO Summary');
    $myxls->write_string(0,0,'Client ID');
    $myxls->write_string(0,1,'RTO Code');
    $myxls->write_string(0,2,'RTO Name');
    $myxls->write_string(0,3,'LMS URL');
    $myxls->write_string(0,4,'Admin emailL');
    $myxls->write_string(0,5,'Contact person name');
    $myxls->write_string(0,6,'Contact phone no');
    $myxls->write_string(0,7,'Package Information');
    $myxls->write_string(0,8,'Units  assigned');
    $myxls->write_string(0,9,'Users number');
    $myxls->write_string(0,10,'Support Ticket number');
    $myxls->write_string(0,11,'Support ticket resolved');
    $myxls->write_string(0,12,'Support ticket pending');

    $total_admin_records = count($all_university_admin);
    $increment = 1;
    foreach ($all_university_admin as $university_admin) { 

        $school = $DB->get_record("school", ['id'=>$university_admin->university_id]);
        $user = $DB->get_record("user", ['id'=>$university_admin->userid]);
        $get_package_id = $DB->get_record("admin_subscription", ['university_id'=>$university_admin->university_id]);
        $get_package_info = $DB->get_record("package", ['id'=>$get_package_id->package_id]);
        $course_users_info = $DB->get_record("university_user_course_count", ['university_id'=>$university_admin->university_id]);
        
        $myxls->write_string($increment,0,"$school->client_id");
        $myxls->write_string($increment,1,"$school->rto_code");
        $myxls->write_string($increment,2,"$school->name");
        $myxls->write_string($increment,3,"$school->domain");
        $myxls->write_string($increment,4,"$user->email");
        $myxls->write_string($increment,5,"$user->phone1");
        $myxls->write_string($increment,6,"$get_package_info->num_of_user users, $get_package_info->num_of_course units, $get_package_info->package_value Rs monthly");
        $myxls->write_string($increment,8,"$course_users_info->user_count");
        $myxls->write_string($increment,7,"$course_users_info->course_count");

        $increment++;
    }
    $workbook->close();
?>