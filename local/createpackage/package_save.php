<?php
    require_once('../../config.php');
    require_once('lib.php');
    require_login();

    global $USER, $DB;
    $value=$_POST['p_value'];
    $user=$_POST['num_of_user'];
    $course=$_POST['num_of_course'];

    if (isset($_POST['submit'])) 
    {
        $package_in= new stdClass();
        $package_in->package_value=$value;
        $package_in->num_of_user= $user;
        $package_in->num_of_course=$course;
        $success = $DB->insert_record('package',  $package_in);
    }
    if ( $success) 
    {
        redirect("package_list.php", "Package Created Successfully",null, \core\output\notification::NOTIFY_SUCCESS);
    }
?>