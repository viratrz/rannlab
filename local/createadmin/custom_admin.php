<?php

require_once('../../config.php');
require_once('lib.php');
require_once('../../user/lib.php');

global $USER, $DB;


$uni_id=$DB->get_record('universityadmin', ['userid' => $USER->id]);

$username =$_POST['username'];
$firstname =$_POST['firstname'];
$lastname =   $_POST['lastname'];
$email = $_POST['email'];
$password = $_POST['password'];

$get_username = $DB->get_record('user', array('username'=>$username));


if($get_username)
{
    $json = array();
    $json['success'] = false;
    $json['msg2'] = "Username already exist";
    echo json_encode($json);
    exit;
}

$get_email = $DB->get_record('user', array('email'=>$email));
if($get_email)
{
    $json = array();
    $json['success'] = false;
    $json['msg3'] = "email already exist";
    echo json_encode($json);
    exit;
}

$total_user = $DB->count_records('university_user', array('university_id'=>$uni_id->university_id));
$package_id = $DB->get_record_sql("SELECT p.* FROM mdl_package p JOIN mdl_admin_subscription mas ON p.id = mas.package_id  WHERE mas.university_id= $uni_id->university_id ");

if ($package_id->num_of_user > $total_user) 
{

    $userdata = new stdClass();

    $userdata->auth = 'manual';
    $userdata->confirmed = 1;
    $userdata->policyagreed = 0;
    $userdata->deleted = 0;
    $userdata->suspended = 0;
    $userdata->mnethostid = $CFG->mnet_localhost_id; // Always local user.
    $userdata->username = $username;
    $userdata->password = hash_internal_user_password($password);
    $userdata->firstname = $firstname;
    $userdata->lastname = $lastname;
    $userdata->email = $email;
    $userdata->emailstop = 0;
    $userdata->phone1 = '';
    $userdata->city = '';
    $userdata->country = '';
    $userdata->lang = 'en';
    $userdata->calendartype = 'gregorian';
    $userdata->timezone = 99;
    $userdata->descriptionformat = 1;
    $userdata->mailformat = 1;
    $userdata->maildigest = 0;
    $userdata->maildisplay = 2;
    $userdata->autosubscribe = 1;
    $userdata->trackforums = 0;
    $userdata->timecreated = time();
    $userdata->trustbitmask = 0;


    $user_id  = user_create_user($userdata, false, false);
    $roleid = 10;
    $contextid = 1;

    role_assign($roleid, $user_id ,$contextid);

    if($user_id)
    {
        $admininfo =  new stdClass();
        $admininfo->userid = $user_id;
        $admininfo->university_id = $uni_id->university_id;

        $insert_admin = $DB->insert_record('universityadmin', $admininfo, true, false);
        $insert_user = $DB->insert_record('university_user', $admininfo, true, false);

        if($insert_admin && $insert_user)
        {	
            $total_user = $DB->count_records('university_user', array('university_id'=>$uni_id->university_id));
            $total_course = $DB->count_records('assign_course', array('university_id'=>$uni_id->university_id));

            $user_course =  new stdClass();

            $check_uni_id = $DB->get_record_sql("SELECT id,university_id FROM {university_user_course_count} WHERE university_id = $uni_id->university_id");
            if ($check_uni_id) 
            {
                $user_course->id = $check_uni_id->id;
                $user_course->user_count = $total_user;
                $user_course->course_count = $total_course;
                $updated = $DB->update_record("university_user_course_count", $user_course, false);
            } 
            else 
            {
                $user_course->university_id = $uni_id->university_id;
                $user_course->user_count = $total_user;
                $user_course->course_count = $total_course;
                $inserted = $DB->insert_record("university_user_course_count", $user_course, false);
            }
        }
    }
    } 
    else 
    {
        $json = array();
        $json['success'] = false;
        $json['msg'] = "User Limit Exeed";
        echo json_encode($json);
}
if($updated || $inserted)
{
    $json = array();
    $json['success'] = true;
    $json['msg'] = "New Admin Created Successfully!";
    echo json_encode($json);
}

?>
