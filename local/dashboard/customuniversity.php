<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Main login page.
 *
 * @package    core
 * @subpackage auth
 * @copyright  1999 onwards Martin Dougiamas  http://dougiamas.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

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
        $json['msg3'] = "email already exist";
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

$package_id = $_GET['package'];

$data = new stdClass();
$data->name = $_GET['longname'];
$data->shortname = $_GET['shortname'];
$data->address = $_GET['address'];
$data->city = $_GET['city'];
$data->country = $_GET['country'];

$inserted = $DB->insert_record('school', $data, true);

if ($package_id) 
{
    $date =date("d/m/Y");
    $package_sub = new stdClass();

    $package_sub->package_id=$package_id;	
    $package_sub->university_id= $inserted;
    $DB->insert_record('admin_subscription', $package_sub);
}
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

$roleid = 9;
$contextid = 1;
role_assign($roleid, $user_id ,$contextid);

if($inserted){
    $admininfo = new stdClass();
    $admininfo->userid = $user_id;
    $admininfo->universityid = $inserted;

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
