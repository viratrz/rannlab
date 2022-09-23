<?php 
require_once('../../config.php');
require_once('lib.php');
require_login();

global $USER, $DB;
$package_id = (int)$_POST['id'];
$del_package = $DB->delete_records('package', [ 'id' => $package_id]);

if ($del_package) 
{
    echo "Package Deleted Successfully";
} 
else 
{
    echo "Something went is wrong";
}

?>