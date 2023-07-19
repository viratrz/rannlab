<?php
include("../../config.php");
require_login();
global $CFG, $DB,$USER;

echo $OUTPUT->header();
// $university = $DB->get_record('universityadmin',array('userid' => $USER->id));
$university_id = (int)$_POST['university_id'];

$last_color = $DB->get_record('school',array('id' =>$university_id));
$footer_color = $last_color->footer_color;
$header_color = $last_color->header_color;

if(isset($_POST['h_color'])) {
    $header_color =$_POST['h_color'];
}
if(isset($_POST['f_color'])) {
    $footer_color =$_POST['f_color'];
}
// echo "<pre>";
// var_dump($_POST,$last_color,$footer_color,$header_color,$university->university_id);
// die;
$set_color = new stdclass();
$set_color->id = $university_id;
$set_color->header_color = $header_color;
$set_color->footer_color = $footer_color;
$status=$DB->update_record('school', $set_color);
unset($SESSION->university_theme_colors);
// echo $status;
if($status)
{
    redirect("theme.php?uni_id=$university_id","Theme Update Successfully");
}
else
{
    redirect("theme.php","Something is Wrong");
}
// redirect("theme.php","Theme Update Successfully");
echo $OUTPUT->footer();
?>