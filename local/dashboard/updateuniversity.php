<?php
require_once('../../config.php');
require_once('lib.php');
require_once('../../user/lib.php');

 GLOBAL $USER,$DB;

 $universityid=$_GET['editid'];
 $address1=$_GET['address'];
 $city1=$_GET['city'];
 $country1=$_GET['country'];

 

if(isset($_GET['longname'])){
$universitylongname = $_GET['longname'];
// if (isset($_GET['editid'])) {

$universityname = $DB->get_record('school', array('name'=>$universitylongname));
foreach($universityname as $universityname1){
    if($universityname1 == $universityid){
        break;
    }else{
        $json = array();
        $json['success'] = false;
        $json['msg1'] = "Longname already exist";

        echo json_encode($json);
        exit;
    }
}
}
// }

if(isset($_GET['shortname'])){
    $universityshortname = $_GET['shortname'];
    $universityshortname1 = $DB->get_record('school', array('shortname'=>$universityshortname));
    foreach($universityshortname1 as $universityshortname2){
        if($universityshortname2 == $universityid){
            break;
        }else{
            $json = array();
            $json['success'] = false;
            $json['msg2'] = "Shortname already exist ";
    
            echo json_encode($json);
            exit;
        }
    
    }
    }



$inserted1 = $DB->execute("UPDATE {school} AS s  SET s.name = '$universitylongname', s.shortname = '$universityshortname', s.address = '$address1', s.city = '$city1', s.country = '$country1' WHERE s.id = '$universityid' ");

$id1=$_GET['adminid'];
$firstname1 = $_GET['firstname'];
$lastname1 = $_GET['lastname'];
$email1 = $_GET['email'];
$id1=$_GET['adminid'];
$pass2= $_GET['password'];

if(isset($_GET['username'])){
$username1 = $_GET['username'];
$usernamedata = $DB->get_record('user', array('username'=>$username1));
foreach($usernamedata as $usernamedata1){
    if($usernamedata1 == $id1){
        break;
    }else{
        $json = array();
        $json['success'] = false;
        $json['msg3'] = "username already exist";

        echo json_encode($json);
        exit;
    }

}
}



$updateduser = $DB->get_record('user', array('id' => $id1));

        // If password was set, then update its hash.
        if (isset($pass2)) {
            $authplugin = get_auth_plugin($updateduser->auth);
            if ($authplugin->can_change_password()) {
                $authplugin->user_update_password($updateduser, $pass2);
            }
        }

$inserted = $DB->execute("UPDATE {user} AS u  SET u.username = '$username1', u.firstname = '$firstname1', u.lastname = '$lastname1', u.email = '$email1' WHERE u.id = '$id1' ");

if($inserted){
    if($inserted1){ 
$json = array();
$json['success'] = true;
$json['msg'] = "Details updated Successfully!";
echo json_encode($json);
}
}