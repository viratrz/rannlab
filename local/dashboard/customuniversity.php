<?php
use core\task\manager;
use local_dashboard\task\assign_courses;
require_once('../../config.php');
require_once('lib.php');
require_once('../../user/lib.php');
date_default_timezone_set('Asia/Kolkata');
global $USER, $DB, $CFG;

$select_course = count($_POST["courses"]);
$package_id = $_POST['package'];
$phone_no = $_POST['phone_no'];

$package = $DB->get_record_sql("SELECT * FROM mdl_package WHERE id=$package_id");
$num_of_course = (int)$package->num_of_course;

// var_dump($package_id,$select_course, $num_of_course);
// die;

if ($package_id && ($select_course > $num_of_course)) 
{
    $json = array();
    $json['success'] = false;
    $json['max_course'] = "Please select max number of course according to selected package";
    echo json_encode($json);
    exit;
}


if(isset($_POST['username']))
{
    $username =$_POST['username'];
}

if(isset($_POST['firstname']))
{
    $firstname =$_POST['firstname'];
}

$lastname =   $_POST['lastname'];
$password = $_POST['password'];

if(isset($_POST['longname']))
{
    $schoolname = $_POST['longname'];
    $schoolnamedata = $DB->get_record('school', array('name'=>$schoolname));
    if($schoolnamedata){
        $json = array();
        $json['success'] = false;
        $json['msg1'] = "University already exist";
        echo json_encode($json);
        exit;
    }
}

if(isset($_POST['shortname']))
{
    $shortname = $_POST['shortname'];
    $schoolnamedata = $DB->get_record('school', array('shortname'=>$shortname));
    if($schoolnamedata)
    {
        $json = array();
        $json['success'] = false;
        $json['msg4'] = "Shortname already exist";
        echo json_encode($json);
        exit;
    }
}

if(isset($_POST['client_id']))
{
    $client_id = $_POST['client_id'];
    // $school_client_id = $DB->get_record('school', array('client_id'=>"$client_id"));
    $school_client_id = $DB->get_record_sql("SELECT * FROM {school} WHERE client_id ='$client_id'");
    if($school_client_id)
    {
        $json = array();
        $json['success'] = false;
        $json['client_id_msg'] = "Client id already exist";
        echo json_encode($json);
        exit;
    }
}

if(isset($_POST['rto_code']))
{
    $rto_code = $_POST['rto_code'];
    // $school_rto_code = $DB->get_record('school', array('rto_code'=>"$rto_code"));
    $school_rto_code = $DB->get_record_sql("SELECT * FROM {school} WHERE rto_code ='$rto_code'");
    if($school_rto_code)
    {
        $json = array();
        $json['success'] = false;
        $json['rto_code_msg'] = "RTO code id already exist";
        echo json_encode($json);
        exit;
    }
}

if(isset($_POST['domain']))
{
    $domain = $_POST['domain'];
    $get_domain = $DB->get_record_sql("SELECT * FROM {school} WHERE domain= '$domain'");
    if($get_domain)
    {
        $json = array();
        $json['success'] = false;
        $json['unique'] = "Domain already exist";
        echo json_encode($json);
        exit;
    }
}

if(isset($username))
{
    // $usernamedata = $DB->get_record('user', array('username'=>$username));
    $usernamedata = $DB->get_record_sql("SELECT * FROM {user} WHERE username='.$username.'");
    if($usernamedata)
    {
        $json = array();
        $json['success'] = false;
        $json['msg2'] = "Username already exist";
        echo json_encode($json);
        exit;
    }       
}

if(isset($_POST['email']))
{
    $email = $_POST['email'];
    $emaildata = $DB->get_record('user', array('email'=>$email));
    if($emaildata)
    {
        $json = array();
        $json['success'] = false;
        $json['msg3'] = "Email already exist";
        echo json_encode($json);
        exit;
    }
}
$package_id = $_POST['package'];

try {
    $transaction = $DB->start_delegated_transaction();
    if ($package_id ) 
    {

        $data = new stdClass();
        $data->name = $_POST['longname'];
        $data->shortname = $_POST['shortname'];
        $data->client_id = $client_id;
        $data->rto_code = $rto_code;
        $data->address = $_POST['address'];
        $data->city = $_POST['city'];
        $data->country = $_POST['country'];
        $data->domain = $_POST['domain'];
        $inserted = $DB->insert_record('school', $data, true);

        if (basename($_FILES["university_logo"]["name"]) && $inserted) 
        {
            $path_filename ="/local/changelogo/logo/". basename($_FILES["university_logo"]["name"]);
            $target_file = $CFG->dirroot.$path_filename;
            $uploaed = move_uploaded_file($_FILES["university_logo"]["tmp_name"], $target_file);
            if ($uploaed ) 
            {
                $set_logo = new stdclass();
                $set_logo->id = $inserted;
                $set_logo->logo_path = $path_filename;
                $DB->update_record('school', $set_logo);
            }
        }

    }
    $category = null;
    if ($inserted) 
    {
    // create a category with same name as RTO and link it with RTO
        $category_details = new \stdClass();
        $category_details->parent = \core_course_category::top()->id;
        $category_details->name = $_POST['longname'];
    //$category_details->idnumber = $rto_code;
        try {
            $category = \core_course_category::create($category_details);
            $school_update_obj = new stdclass();
            $school_update_obj->id = $inserted;
            $school_update_obj->coursecategory = $category->id;
            $DB->update_record('school', $school_update_obj);
        } catch (Exception $e) {
           $school_update_obj = new stdclass();
           $school_update_obj->id = $inserted;
           $school_update_obj->coursecategory = core_course_category::top()->id;
           $DB->update_record('school', $school_update_obj);
       }
       $date = date('Y-m-d', strtotime('+1 month'));
       $package_sub = new stdClass();
       $package_sub->package_id=$package_id;  
       $package_sub->university_id= $inserted;
       $package_sub->sub_date = date('Y/m/d H:i:s');
       $package_sub->end_date= $date;
       $pack = $DB->insert_record('admin_subscription', $package_sub, true);
   }
   if ($pack) 
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
    $userdata->phone1 = $phone_no;
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
    if ($user_id ) 
    {
        $roleid = 9;
        $contextid = 1;
        // if ($category) {
        //     $category_context = context_coursecat::instance($category->id);
        //     $contextid = $category_context->id;
        // }
        role_assign($roleid, $user_id ,$contextid);
    } 
    
    if ($user_id) {
        $sub = "Welcome";
        $msg = "Hi";
        $to_user = new stdClass();
        $to_user->email= $email;
        $to_user->id =(int)$user_id;

        $from_user = new stdClass();
        $from_user->email= 'clientsmtp@dcc.rannlab.com';
        $from_user->maildisplay= true;

        email_to_user($to_user,$from_user,$sub,$msg);
    }
}

if($user_id)
{

    $admininfo = new stdClass();
    $admininfo->userid = $user_id;
    $admininfo->university_id = $inserted;
    $admininfo->cb_userid = $USER->id;
    $inserted1 = $DB->insert_record('universityadmin', $admininfo);
    if($inserted1)
    {   
        $course_id = $_POST["courses"];
        $user_id = $DB->get_record_sql("SELECT userid FROM {universityadmin} WHERE university_id=$inserted");
        $package_id = $DB->get_record_sql("SELECT p.* FROM mdl_package p JOIN mdl_admin_subscription mas ON p.id = mas.package_id  WHERE mas.university_id= $inserted ");
        $assign_course = new stdClass();
        $count_add = 0;
        $adhoc_assign_courses = new assign_courses();
        manager::queue_adhoc_task($adhoc_assign_courses, true);
        foreach($course_id as $c_id)
        {
            $total_course = $DB->count_records('assign_course', array('university_id'=>$inserted));

            if ($package_id->num_of_course > $total_course) 
            {
                $count_add = $count_add+1;
                if($c_id)
                {
                    $assign_course->university_id = $inserted;
                    $assign_course->course_id = $c_id;
                    $assign_course->is_pending = 1;
                    $assign_id = $DB->insert_record('assign_course', $assign_course);
                    //createresource($c_id,$inserted,$user_id->userid);
                }

                // if($inserted)
                // {
                //     $check_uni_id = $DB->get_record_sql("SELECT id,university_id FROM {university_user_course_count} WHERE university_id = $inserted");
                //     $total_course = $DB->count_records('assign_course', array('university_id'=>$inserted));

                //     $user_course =  new stdClass();
                //     if ($check_uni_id) 
                //     {
                //         $user_course->id = $check_uni_id->id;
                //         $user_course->course_count = $total_course;
                //         $updated = $DB->update_record("university_user_course_count", $user_course, false);
                //     } 
                //     else 
                //     {
                //         $user_course->university_id = $inserted;
                //         $user_course->course_count = $total_course;
                //         $inserted_count = $DB->insert_record("university_user_course_count", $user_course, false);
                //     }
                // }
            }
            else 
            {
                $transaction->allow_commit();
                $json = array();
                $json['success'] = true;
                // $json['msg'] = "Courses Assign Limit Exeed";
                $json['msg'] = "RTO and RTO Admin Created Successfully, But only $count_add Course Addded";
                echo json_encode($json);
                exit;
            }
        }
        $transaction->allow_commit();
        $json = array();
        $json['success'] = true;
        $json['msg'] = "RTO and RTO Admin Created Successfully!";
        echo json_encode($json);
    }
}
} catch (Exception $e) {
    $transaction->rollback($e);
    $json = array();
    $json['success'] = false;
    $json['msg'] = $e->getMessage();
    echo json_encode($json);
}

?>
