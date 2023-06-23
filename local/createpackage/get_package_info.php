<?php
require_once('../../config.php');
require_once('lib.php');
require_login();

global $USER, $DB;
$package_id = (int)$_POST['id'];
$package_info = $DB->get_record_sql("SELECT * FROM {package} WHERE id = $package_id");

$html='<table class="table table-sm table-bordered">
            <thead>
            <tr class="table-primary">
                <th scope="col">Name</th>
                <th scope="col">Price($/Month)</th>
                <th scope="col">Max Users</th>
                <th scope="col">Max Assign Course</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td id="name">'.$package_info->package_name.'</td>
                <td id="price">'.$package_info->package_value.'</td>
                <td id="users">'.$package_info->num_of_user.'</td>
                <td id="courses">'.$package_info->num_of_course.'</td>
            </tr>
            </tbody>
        </table>';
echo json_encode(array("html" =>$html, 'course' => $package_info->num_of_course));
exit;
// echo ($html);

?>