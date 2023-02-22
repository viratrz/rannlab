<?php
require_once('../../config.php');
require_once('lib.php');
require_once('../../user/lib.php');

GLOBAL $USER,$DB;
$del_id = (int)$_GET['del_id'];

if (isset($del_id)) 
{
    $DB->delete_records('universityadmin', array('userid'=>$del_id));
    $DB->delete_records('university_user', array('userid'=>$del_id));

    $user = $DB->get_record('user', array('id' => $del_id, 'deleted' => 0), '*', MUST_EXIST);
    $status = user_delete_user($user);
    
    if ($status) 
    {
        redirect("user_list.php", "User Deleted Successfully", null, \core\output\notification::NOTIFY_INFO);
    }
    else
    {
        redirect("user_list.php", "Something Wrong User Not Delete", null, \core\output\notification::NOTIFY_WARNING);
    }
}
?>
