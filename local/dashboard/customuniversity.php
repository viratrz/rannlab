<?php
require_once('../../config.php');
require_once('lib.php');
require_once('../../user/lib.php');

global $USER,$DB;

if(isset($_GET['username']))
{
    $username =$_GET['username'];
}

if(isset($_GET['firstname']))
{
    $firstname =$_GET['firstname'];
}

$lastname =   $_GET['lastname'];
$password = $_GET['password'];

if(isset($_GET['longname']))
{
    $schoolname = $_GET['longname'];
    $schoolnamedata = $DB->get_record('school', array('name'=>$schoolname));
    if($schoolnamedata){
        $json = array();
        $json['success'] = false;
        $json['msg1'] = "University already exist";
        echo json_encode($json);
        exit;
        }
}

if(isset($_GET['shortname']))
{
    $shortname = $_GET['shortname'];
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
if(isset($_GET['domain']))
{
    $domain = $_GET['domain'];
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
    $usernamedata = $DB->get_record('user', array('username'=>$username));
    if($usernamedata)
    {
        $json = array();
        $json['success'] = false;
        $json['msg2'] = "Username already exist";
        echo json_encode($json);
        exit;
    }       
}

if(isset($_GET['email']))
{
    $email = $_GET['email'];
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
$package_id = $_GET['package'];
if ($package_id ) 
{
    $data = new stdClass();
    $data->name = $_GET['longname'];
    $data->shortname = $_GET['shortname'];
    $data->address = $_GET['address'];
    $data->city = $_GET['city'];
    $data->country = $_GET['country'];
    $data->domain = $_GET['domain'];
    $inserted = $DB->insert_record('school', $data, true);
}

if ($inserted) 
{
    $date =date("d/m/Y");
    $package_sub = new stdClass();

    $package_sub->package_id=$package_id;	
    $package_sub->university_id= $inserted;
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
    if ($user_id ) 
    {
        $roleid = 9;
        $contextid = 1;
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
        $json = array();
        $json['success'] = true;
        $json['msg'] = "University and University Admin Created Successfully!";
        echo json_encode($json);
    }
}
?>
