<?php
require_once('../../config.php');
require_once('lib.php');
require_once('../../user/lib.php');

 GLOBAL $USER,$DB,$CFG;

 $universityid=$_POST['editid'];
 $address1=$_POST['address'];
 $city1=$_POST['city'];
 $country1=$_POST['country'];

 

if(isset($_POST['longname'])){
$universitylongname = $_POST['longname'];
// if (isset($_POST['editid'])) {

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

if(isset($_POST['shortname'])){
    $universityshortname = $_POST['shortname'];
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

if (basename($_FILES["university_logo"]["name"]) && $inserted1) 
{
    $path = "/local/changelogo/logo/".$universityid."/";
    $absolutepath = $CFG->dirroot.$path;
    if (!file_exists($absolutepath)) {
        mkdir($absolutepath, $CFG->directorypermissions, true);
    }
    $filename = str_replace(' ', '_', basename($_FILES["university_logo"]["name"]));
    $path_filename =$path. $filename;
    $target_file = $CFG->dirroot.$path_filename;
    $uploaed = move_uploaded_file($_FILES["university_logo"]["tmp_name"], $target_file);
    if ($uploaed ) 
    {
        $set_logo = new stdclass();
        $set_logo->id = $universityid;
        $set_logo->logo_path = $path_filename;
        $DB->update_record('school', $set_logo);
    }
}
$id1=$_POST['adminid'];
$firstname1 = $_POST['firstname'];
$lastname1 = $_POST['lastname'];
$email1 = $_POST['email'];
$id1=$_POST['adminid'];
$pass2= $_POST['password'];

if(isset($_POST['username'])){
$username1 = $_POST['username'];
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