<?php

require_once('../../config.php');
require_once('lib.php');
require_once('../../user/lib.php');

global $USER, $DB;

$username = $_POST['username'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$password = $_POST['password'];

$user_id =(int)$_POST['user_id'];
$get_username = $DB->get_record_sql("SELECT username FROM {user} WHERE id != $user_id AND username='$username'");
$get_email = $DB->get_record_sql("SELECT username FROM {user} WHERE id != $user_id AND email='$email'");

if($get_username)
{
    $json = array();
    $json['success'] = false;
    $json['username'] = "Username already exist";
    echo json_encode($json);
    exit;
}
else if($get_email)
{
    $json = array();
    $json['success'] = false;
    $json['email'] = "Email already exist";
    echo json_encode($json);
    exit;
}
else
{
    $user_admin = new stdClass();
    $user_admin->id = $user_id;
    $user_admin->username = $username;
    $user_admin->firstname = $firstname;
    $user_admin->lastname = $lastname;
    $user_admin->email = $email;
    if ($password) 
    {
        $user_admin->password =hash_internal_user_password($password);
    }
    $updated = $DB->update_record("user", $user_admin, false);
}

if($updated)
{
    $json = array();
    $json['success'] = true;
    $json['msg'] = "Details Updated Successfully!";
    echo json_encode($json);
}

?>
