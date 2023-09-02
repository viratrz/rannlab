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
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * My Moodle -- a user's personal dashboard
 *
 * - each user can currently have their own page (cloned from system and then customised)
 * - only the user can see their own dashboard
 * - users can add any blocks they want
 * - the administrators can define a default site dashboard for users who have
 *   not created their own dashboard
 *
 * This script implements the user's view of the dashboard, and allows editing
 * of the dashboard.
 *
 * @package    moodlecore
 * @subpackage my
 * @copyright  2010 Remote-Learner.net
 * @author     Hubert Chathi <hubert@remote-learner.net>
 * @author     Olav Jordan <olav.jordan@remote-learner.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once '../config.php';
define('NO_OUTPUT_BUFFERING', true);
require_once __DIR__ . '/../config.php';
require_once $CFG->dirroot . '/my/lib.php';
require_once $CFG->libdir . '/adminlib.php';

$resetall = optional_param('resetall', false, PARAM_BOOL);

$pagetitle = get_string('mypage', 'admin');
global $DB, $USER, $OUTPUT;
$title = 'Dashboard';

$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('standard');
$PAGE->set_secondary_active_tab('appearance');
$PAGE->set_blocks_editing_capability('moodle/my:configsyspages');
$PAGE->set_url(new moodle_url('/my/index.php'));
$roleid = $DB->get_record("role_assignments", ['userid' => $USER->id]);
$role_shortname = $DB->get_record("role", ['id' => $roleid->roleid]);
admin_externalpage_setup('mypage', '', null, '', array('pagelayout' => 'mydashboard'));

$PAGE->add_body_class('limitedwidth');
$PAGE->set_pagetype('my-index');
$PAGE->blocks->add_region('content');
$PAGE->set_title($pagetitle);
$PAGE->set_heading($pagetitle);
$PAGE->set_secondary_navigation(false);
$PAGE->set_primary_active_tab('myhome');

function get_course_image($courseid) {
	global $COURSE;
	$url = '';

	$context = context_course::instance($courseid);
	$fs = get_file_storage();
	$files = $fs->get_area_files($context->id, 'course', 'overviewfiles', 0);

	foreach ($files as $f) {
		if ($f->is_valid_image()) {
			$url = moodle_url::make_pluginfile_url($f->get_contextid(), $f->get_component(), $f->get_filearea(), null, $f->get_filepath(), $f->get_filename(), false);
		}
	}

	return $url;
}

$header = '';
$header .= '<!DOCTYPE html>
<html lang="en">
<head>
<title>Course-Catalogue</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://www.gstatic.com/charts/loader.js"></script>
<style type="text/css">
body{
  font-family: "Poppins", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol" !important;
}
.card2{
  box-shadow: 0 3px 6px rgb(0 0 0 / 16%), 0 3px 6px rgb(0 0 0 / 23%);
  transition: .5s all;
  margin:0px 0px 30px 0px;
}
.card2:hover{
  margin-top: -10px;
}
.card2 img{
          *border-radius: 0 10px 0 7px !important;
}
.card2-body{
  text-align: center;
          *background: #f9f9f9;
          *border-bottom: 5px solid #1d1d1d;
  border-top: 5px solid #fbe700;
  padding:1.25rem;
}
.card2-title{
  font-size: 18px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.img{
   position: relative;
   overflow: hidden;
}
.card2 img{
    object-fit: cover;
    height:200px;
    width:100%;
}
.overlay{
   position: absolute;
   height: 100%;
   top: 0;
   left: -100%;
   transition: .3s all;
   width: 100%;
   background: rgba(0,0,0,0.4);
}
.card2:hover .overlay{
   left: 0;
}
        //custom colour code for all text area
.custom-bg-alltextarea
{
    background-color:#ACA9A9;
}

.btn {
    position: relative;
    display: inline-block;
    margin: 15px;
    padding: 12px 27px;
    text-align: center;
    font-size: 16px;
    letter-spacing: 1px;
    text-decoration: none;
    *color: #000 !important;
    *background: #ffffff;
    border: 2px solid #fbe700;
    box-shadow: 0 0 5px rgb(0 0 0 / 70%);
    cursor: pointer;
    transition: ease-out 0.5s;
    -webkit-transition: ease-out 0.5s;
    -moz-transition: ease-out 0.5s;
}
.search-icon{
    display: inline-block;
    margin: 0px !important;
    padding: 0px 0.5rem !important;
    text-align: center;
    font-size: 16px;
    letter-spacing: 1px;
    text-decoration: none;
    color: #fff !important;
  *background: #ffffff;
    border: 2px solid #fbe700;
    box-shadow: 0 0 5px rgb(0 0 0 / 70%);
    cursor: pointer;
    transition: ease-out 0.5s;
    -webkit-transition: ease-out 0.5s;
    -moz-transition: ease-out 0.5s;
}

.btn.btn-border-5::after,
.btn.btn-border-5::before {
    position: absolute;
    content: "";
    width: 0;
    height: 0;
    transition: .5s;
}

.btn.btn-border-5::after {
    top: 0;
    left: 0;
    border-top: 3px solid transparent;
}

.btn.btn-border-5::before {
    bottom: 0;
    right: 0;
    border-bottom: 3px solid transparent;
}

.btn.btn-border-5:hover {
 color: white;
}

.btn.btn-border-5:hover::after,
.btn.btn-border-5:hover::before
{
 width: 100%;
 height: 100%;
 color: white;
 border-color: white;
}
.button{

  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}
.button{
 background: inherit !important;
}


.maincalendar .calendarmonth td, .maincalendar .calendarmonth th {

    line-height: 35px !important;
}
.maincalendar .calendarmonth ul li .calendar-circle {
    width: 3px !important;
    height: 3px !important;
}
.maincalendar .calendarmonth th {
    padding-left: 3px !important;
}
.dashbord .ccn_breadcrumb_widgets.container {
    display: none !important;
}

.ccn-calendar-header{
    display: none !important;
}
.dashboard_sidebar:before{
 background-color: #17263C !important;
}
  #inst192{
background-color: #17263C;
}
  #instance-192-header{
color: #fff;
}

nav.breadcrumb_widgets.ccn-clip-l {
    display: none;
}

.custom-type4-dashboard{
    background: #17263C;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    border-radius: 20px;
    width:32%;
    padding:5px;
}

.custom-type4-50-wid
{
  width:49% !important;
}
.custom-type4-100-wid
{
  width :98% !important;
}
.cutom-heading-box{
    text-align: center;
}
.custom-heading-p-box{
    padding-top: 30px;
    font-size: 20px;
    font-weight: 500;
    color: white;

}
.custom-faicon{
    text-align: center;
    font-size: 40px;
    margin-top: -35px;
    background-color: #8E30FF;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    height: 80px;
    width: 80px;
    border-radius: 49%;
    display: inline-block;
    margin-left: calc(25% + 40px);
    padding-top: 10px;
    color: white;
}
.custom-numbers-dashboard-cards{
    float: right;
    font-size: 17px;
    background-color: #C6FAD6;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    height: 30px;
    width: 30px;
    border-radius: 49%;
    display: inline-block;
    text-align: center;
    padding-top: 2px;
}
    #inst200{
background: #17263C;
color: white;
}
    #inst205{
background: #17263C;
color: white;
}
h4#inst205{
    color: white;
}
h3#inst205{
    color: white;
}
.custom-heading-p-box-type2{
    padding:15px;
    font-size: 20px;
    font-weight: 500;
    color: white;

}

.fa-fw
{

   color:#B578FF;
}
.ccn_dashnav_item
{
  color:#fff;
}
.ccn_dashbord_nav_list li a
{
  padding: 0px 0px 15px 15px !important;
}

.dashbord #block-region-side-pre .block
{
  border-radius: 20px;

}

.ccnDashBlHd
{
  font-size: 20px;
  font-weight: 500;
  color: white;
  text-align:center;
}


.maincalendar .calendarmonth td .day-number-circle .day-number
{
  color:#fff;
}


.maincalendar .calendarmonth .clickable:hover
{
  background-color: #B578FF !important;
}


.maincalendar .calendarmonth td, .maincalendar .calendarmonth th
{
  color:#fff !important;
}

.footer-link a
{
  color:#fff !important;
}

.calendar-controls .current
{
  color:#fff !important;
}
.arrow_link
{
  color:#fff !important;
}


</style>
</head>';
echo $header;

echo $OUTPUT->header();

if (!is_siteadmin()) {
	$roleid = $DB->get_record("role_assignments", ['userid' => $USER->id]);
	$role_shortname = $DB->get_record("role", ['id' => $roleid->roleid]);
	$stud_name = $DB->get_record("user", ['id' => $roleid->username]);
	//$coursecount = $DB->count_records("course", ['idnumber'=>'resourcecat']);
	$stud_courses = $DB->get_records_sql("SELECT * FROM {role_assignments} WHERE userid ='$USER->id'");

	$results = $DB->get_records_sql("SELECT COUNT(enrolid) FROM mdl_user_enrolments");
	$enrol = $rows['COUNT(enrolid)'] . '<br>';
	$coursecount = $DB->count_records("user_enrolments", ['userid' => $USER->id]);
	$complete_course = $DB->count_records("course_completions", ['userid' => $USER->id]);
	$overdue_courses = $coursecount - $complete_course;

	//var_dump($results);

	if ($role_shortname->shortname === "student") {
		$complete_course = $DB->count_records("course_completions", ['userid' => $USER->id]);
		$overdue_courses = $coursecount - $complete_course;
		$module_course_completed = $DB->count_records("course_modules_completion", array('userid' => "$USER->id", 'completionstate' => '1'));
		$module_course_incompleted = $DB->count_records("course_modules_completion", array('userid' => "$USER->id", 'completionstate' => '0'));

		// echo $module_course_completed . '<br>';
		// echo $module_course_incompleted;

		?>




        <div class="row">
          <!-- ./col -->
          <div class="col-sm-4">
            <a href="<?php echo $CFG->wwwroot . '/my/courses.php'; ?>" class="ff_one">
              <div class="detais">
                <p>Units Enrolled</p>
                <?php echo $coursecount; ?>
            </div>
            <div class="ff_icon"><span class="fa fa-book"></span></div>
        </a>
    </div>

    <!-- ./col -->
    <div class="col-sm-4">
        <a href="<?php echo $CFG->wwwroot . '/my/courses.php'; ?>" class="ff_one style2">
          <div class="detais">
            <p>Units Completed</p>
            <?php echo $complete_course; ?>
        </div>
        <div class="ff_icon"><span class="fa fa-book"></span></div>
    </a>
</div>
<!-- ./col -->
<div class="col-sm-4">
    <a href="<?php echo $CFG->wwwroot . '/my/courses.php'; ?>" class="ff_one style3">
      <div class="detais">
        <p>Units Pending</p>
        <?php echo $overdue_courses; ?>
    </div>
    <div class="ff_icon"><span class="fa fa-book"></span></div>
</a>
</div>
<!-- ./col -->
</div>

<!--<div class="row">
     <div class="col-lg-12 col-12">
      <div class='container'>
        <div id="piechart1" class="pie-chart" style="width: 100%; height: 320px; "></div>
      </div>
    </div>
</div>-->

<!--<div class="row">
     <div class="col-lg-12 col-12">
      <div class='container'>
        <div id="piechart2" class="pie-chart" style="width: 100%; height: 320px; "></div>
      </div>
    </div>
</div>-->



<?php
} else if ($role_shortname->shortname === "trainer") {
		$coursecount = $DB->count_records("user_enrolments", ['userid' => $USER->id]);
		$getuniversityid = $DB->get_record("university_user", ['userid' => $USER->id]);
		$studentsontechercourse = $DB->get_records_sql("SELECT {enrol}.courseid, {user_enrolments}.userid, {enrol}.roleid FROM {user_enrolments} inner join {enrol} on {user_enrolments}.enrolid = {enrol}.id inner join {university_user} on {university_user}.userid = {user_enrolments}.userid where {university_user}.university_id = '$getuniversityid->university_id' and {university_user}.userid = '$USER->id' and {enrol}.roleid = '5' ");
		$last24week = $DB->get_records_sql("SELECT {user_enrolments}.userid FROM {user_enrolments} inner join {enrol} on {user_enrolments}.enrolid = {enrol}.id inner join {university_user} on {university_user}.userid = {user_enrolments}.userid inner join {user} on {user}.id = {university_user}.userid where {university_user}.university_id = '$getuniversityid->university_id' and {university_user}.userid = '$USER->id' and {enrol}.roleid = '5' and week(FROM_UNIXTIME({user}.lastlogin)) >= WEEK( current_date ) - 4 and week(FROM_UNIXTIME({user}.lastlogin)) <= WEEK( current_date ) - 2");
		$last48week = $DB->get_records_sql("SELECT {user_enrolments}.userid FROM {user_enrolments} inner join {enrol} on {user_enrolments}.enrolid = {enrol}.id inner join {university_user} on {university_user}.userid = {user_enrolments}.userid inner join {user} on {user}.id = {university_user}.userid where {university_user}.university_id = '$getuniversityid->university_id' and {university_user}.userid = '$USER->id' and {enrol}.roleid = '5' and week(FROM_UNIXTIME({user}.lastlogin)) >= WEEK( current_date ) - 8 and week(FROM_UNIXTIME({user}.lastlogin)) <= WEEK( current_date ) - 4");
		$more8week = $DB->get_records_sql("SELECT {user_enrolments}.userid FROM {user_enrolments} inner join {enrol} on {user_enrolments}.enrolid = {enrol}.id inner join {university_user} on {university_user}.userid = {user_enrolments}.userid inner join {user} on {user}.id = {university_user}.userid where {university_user}.university_id = '$getuniversityid->university_id' and {university_user}.userid = '$USER->id' and {enrol}.roleid = '5' and week(FROM_UNIXTIME({user}.lastlogin)) <= WEEK( current_date ) - 8");
		$groups = $DB->get_records_sql("SELECT {groups}.id FROM {user_enrolments} inner join {enrol} on {user_enrolments}.enrolid = {enrol}.id inner join {university_user} on {university_user}.userid = {user_enrolments}.userid inner join {user} on {user}.id = {university_user}.userid inner join {groups} on {groups}.courseid = {enrol}.courseid where {university_user}.university_id = '$getuniversityid->university_id' and {university_user}.userid = '$USER->id'");
		$totalactivities = $DB->get_records_sql("SELECT {course_modules}.id FROM {user_enrolments} inner join {enrol} on {user_enrolments}.enrolid = {enrol}.id inner join {university_user} on {university_user}.userid = {user_enrolments}.userid inner join {user} on {user}.id = {university_user}.userid inner join {course_modules} on {course_modules}.course  = {enrol}.courseid where {university_user}.university_id = '$getuniversityid->university_id' and {university_user}.userid = '$USER->id'");
		$module_course_completed_count = $DB->count_records("course_modules_completion", array('userid' => "$USER->id", 'completionstate' => '1'));
		$module_course_completed = ($module_course_completed_count / $coursecount) * 100;
		$module_course_incompleted = 100 - $module_course_completed;

		$present = $DB->count_records("autoattend_students", ['status' => 'P']);
		$absent = $DB->count_records("autoattend_students", ['status' => 'X']);
		//var_dump($module_course_completed);
		//die;

		?>

  <div class="row" style="justify-content: space-between;">
      <!-- ./col -->
  <!--<div class="col-sm-4">
                          <a href="<?php echo $CFG->wwwroot . '/local/createuser/user_list.php'; ?>" class="ff_one">
                            <div class="detais">
                              <p>Learners Assigned</p>
                              <?php echo count($studentsontechercourse); ?>
                            </div>
                            <div class="ff_icon"><span class="fa fa-user"></span></div>
                          </a>
                      </div>-->

                      <div class="custom-type4-dashboard">
                        <div class="custom-faicon" style="text-align: center;">
                          <i class='fas fa-user'></i>
                      </div>
                      <!-- small box -->
                      <div class="cutom-heading-box">
                          <p class="custom-heading-p-box">Learners Assigned</p>
                      </div>


                      <div style="width:100%; padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '/local/createuser/user_list.php'; ?>" class="small-box-footer"
                              style="color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                          <div style="width:50%; float: left;">
                            <p class="custom-numbers-dashboard-cards" style="float: right;"> <?php echo count($studentsontechercourse); ?>
                        </p>
                    </div>
                </div>

            </div>

            <!-- ./col -->
  <!--<div class="col-sm-4">
                          <a href="<?php echo $CFG->wwwroot . '/my/courses.php'; ?>" class="ff_one style2">
                            <div class="detais">
                              <p>Total Groups</p>
                              <?php echo count($groups); ?>
                            </div>
                            <div class="ff_icon"><span class="fa fa-users"></span></div>
                          </a>
                      </div>-->
                      <div class="custom-type4-dashboard">
                        <div class="custom-faicon" style="text-align: center;">
                          <i class='fas fa-users'></i>
                      </div>





                      <!-- small box -->
                      <div class="cutom-heading-box">
                          <p class="custom-heading-p-box">Total Groups</p>
                      </div>


                      <div style="width:100%; padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '/my/courses.php'; ?>" class="small-box-footer" style="color: white;">More
                              info <i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                          <div style="width:50%; float: left;">
                            <p class="custom-numbers-dashboard-cards" style="float: right;"> <?php echo count($groups); ?></p>
                        </div>
                    </div>

                </div>
                <!-- ./col -->
  <!--<div class="col-sm-4">
                          <a href="<?php echo $CFG->wwwroot . '/my/courses.php'; ?>" class="ff_one style3">
                            <div class="detais">
                              <p>Total Activities</p>
                              <?php echo count($totalactivities); ?>
                            </div>
                            <div class="ff_icon"><span class="fa fa-bolt"></span></div>
                          </a>
                      </div>-->
                      <div class="custom-type4-dashboard">
                        <div class="custom-faicon" style="text-align: center;">
                          <i class='fas fa-bolt'></i>
                      </div>
                      <!-- small box -->
                      <div class="cutom-heading-box">
                          <p class="custom-heading-p-box">Total Activities</p>
                      </div>


                      <div style="width:100%; padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '/my/courses.php'; ?>" class="small-box-footer" style="color: white;">More
                              info <i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                          <div style="width:50%; float: left;">
                            <p class="custom-numbers-dashboard-cards" style="float: right;"> <?php echo count($totalactivities); ?></p>
                        </div>
                    </div>

                </div>
            </div>




            <div class="row" style="justify-content: space-between;">
              <div style="width: 100%;">
                <H3 style="float: left;padding-left: 2%;">ATTENDANCE</H3>
                <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=alltickets'; ?>"
                  style="float: right;padding-right: 2%;" class="small-box-footer" style="color: white;">See All <i
                  class="fas fa-arrow-circle-right"></i></a>
                  <br>
                  <hr>
              </div>
              <!-- ./col -->

              <div class="custom-type4-dashboard custom-type4-100-wid" style="padding: 10px;">

                <div class="row px-5" style="display:flex;justify-content: space-between;">
                    <div style="color:#fff;font-weight: 600;">

                      <div class="mt-4 mb-3 mx-2 px-3 py-2"  style="background-color: #c6fad6;border-radius: 10px;">
                        <h3 class="mb-0">Take Attendance</h3>
                    </div>

                    <div class="mt-3 mb-4 mx-2 px-3 py-2"  style="background-color: #8E30FF;border-radius: 10px;">
                        <h3 class="mb-0" style="color:white;">Today's Absentees</h3>
                    </div>

                </div>
                <div style="color:#fff;font-weight: 600;">Calendar-</div>
                <div style="color:#fff;font-weight: 600;"><div id="ogchart4" style="width: 100%;"></div></div>
            </div>


        </div>




    </div>
    <br><br>


    <div class="row" style="justify-content: space-between;">
      <div style="width: 100%;">
        <H3 style="float: left;padding-left: 2%;">NON-COMPETENCY SUMMARY</H3>
        <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=alltickets'; ?>"
          style="float: right;padding-right: 2%;" class="small-box-footer" style="color: white;">See All <i
          class="fas fa-arrow-circle-right"></i></a>
          <br>
          <hr>
      </div>
      <!-- ./col -->

      <?php

		$now = time();

		$sql_1_ncs = "SELECT
c.id,
c.fullname AS 'coursefullname',
COUNT(DISTINCT(cmc1.userid)) AS ncsztot,
COUNT(DISTINCT(cmc2.userid)) AS ncsttos,
COUNT(DISTINCT(cmc3.userid)) AS ncssplus
FROM mdl_course_modules_completion cmc
LEFT JOIN mdl_course_modules_completion cmc1 ON cmc1.userid = cmc.userid AND cmc1.timemodified < $now -15*24*60*60 AND cmc1.timemodified > $now -30*24*60*60
LEFT JOIN mdl_course_modules_completion cmc2 ON cmc2.userid = cmc.userid AND cmc2.timemodified < $now - 30*24*60*60 -1 AND cmc2.timemodified > $now -60*24*60*60
LEFT JOIN mdl_course_modules_completion cmc3 ON cmc3.userid = cmc.userid AND cmc3.timemodified < $now - 60*24*60*60 -1
JOIN mdl_user u ON u.id = cmc.userid
JOIN mdl_course_modules cm ON cmc.coursemoduleid = cm.id
JOIN mdl_course c ON cm.course = c.id
JOIN mdl_assign_course ac ON ac.course_id = c.id
JOIN mdl_modules m ON cm.module = m.id
WHERE cmc.completionstate <> 2 AND ac.university_id = ?
GROUP BY c.id
LIMIT 10";

		$ncs_records = $DB->get_records_sql($sql_1_ncs, [$SESSION->university_id]);

		?>

      <div class="custom-type4-dashboard custom-type4-50-wid" style="padding: 10px;">
        <div style="width: 100%;display:flex;justify-content: space-between;">
          <p style="color:#fff;font-weight: 600;">Not Competent Students</p>
          <a href="<?php echo $CFG->wwwroot . '/local/dashboard/non-competent-students.php'; ?>"
          class="small-box-footer" style="color: rgb(215, 215, 215);">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>

      <div class="table-responsive" >
          <table class="table table-dark table-striped" style="border-radius: 15px;border:3px solid #8e30ff">
            <thead style="background-color: #8e30ff;">
              <tr>
                <th scope="col">Units</th>
                <th scope="col">15-30 Days</th>
                <th scope="col">31-60 Days</th>
                <th scope="col">61+ Days</th>
            </tr>
        </thead>
        <tbody>
          <?php
if ($ncs_records) {
			foreach ($ncs_records as $key => $value) {
				?>
                            <tr>
                        <th scope="row"><?php echo $value->fullname; ?> </th>
                        <td><?php echo $value->ncsztot; ?></td>
                        <td><?php echo $value->ncsttos; ?></td>
                        <td><?php echo $value->ncssplus; ?></td>
                    </tr>

                            <?php
}
		} else {
			$nodata = "No data found";
			?>
             <tr> <th scope="row">No data found</th> </tr>

             <?php
}

		?>
    </tbody>
</table>
</div>

</div>

    <?php

		$not_competent_sub_sql = "SELECT
c.id,
c.fullname AS 'coursefullname',
COUNT(DISTINCT(cmc.userid)) AS non_competent_students_count
FROM mdl_course_modules_completion cmc
JOIN mdl_user u ON u.id = cmc.userid
JOIN mdl_course_modules cm ON cmc.coursemoduleid = cm.id
JOIN mdl_course c ON cm.course = c.id
JOIN mdl_assign_course ac ON ac.course_id = c.id
JOIN mdl_modules m ON cm.module = m.id
WHERE cmc.completionstate <> 2 AND ac.university_id = ?
GROUP BY c.id
ORDER BY non_competent_students_count
LIMIT 10";

		$ncsub_records = $DB->get_records_sql($not_competent_sub_sql, [$SESSION->university_id]);
		if ($ncsub_records) {
			// $series = 'series';
			$barchart = new core\chart_bar();
			$CFG->chart_colorset = ['#001f3f', '#01ff70', '#F012BE', '#85144b', '#B10DC9'];
			$barchart->get_xaxis(0, true)->set_label("Courses");
			$barchart->get_yaxis(0, true)->set_label("No of students");
			$barchart->get_yaxis(0, true)->set_stepsize(10);
			$labels = [];
			$barchart->set_legend_options(['display' => false]);
			$barseries = [];

			foreach ($ncsub_records as $key => $value) {
				$barseries[] = $value->non_competent_students_count;
				$labels[] = $value->coursefullname;

			}

			$series = new core\chart_series('Not competent students', $barseries);
			$barchart->add_series($series);
			$barchart->set_labels($labels);

		}
		?>

<div class="custom-type4-dashboard custom-type4-50-wid" style="padding: 10px;">
    <div style="width: 100%;display:flex;justify-content: space-between;">
      <p style="color:#fff;font-weight: 600;">Not Competent Subjects</p>
      <a href="<?php echo $CFG->wwwroot . '/local/dashboard/non-competent-subjects.php'; ?>"
      class="small-box-footer" style="color: rgb(215, 215, 215);">More info <i class="fas fa-arrow-circle-right"></i></a>
  </div>

  <div>
      <div id="ogchart3">
          <?php
if ($ncsub_records) {
			echo $OUTPUT->render_chart($barchart, false);

		} else {
			echo "No data found";
		}
		?>
      </div>
  </div>

</div>


</div>


<!-- ############################################################### -->
<!-- ############################################################### -->


<br><br>
<div class="row" style="justify-content: space-between;">
  <div style="width: 100%;">
    <H3 style="float: left;padding-left: 2%;">PENDING ASSESSMENTS</H3>
    <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=alltickets'; ?>"
      style="float: right;padding-right: 2%;" class="small-box-footer" style="color: white;">See All <i
      class="fas fa-arrow-circle-right"></i></a>
      <br>
      <hr>
  </div>
  <!-- ./col -->

  <div class="custom-type4-dashboard custom-type4-50-wid" style="padding: 10px;">
    <div style="width: 100%;display:flex;justify-content: space-between;">
      <p style="color:#fff;font-weight: 600;">Assessments Due (Students)</p>
      <a href="<?= $CFG->wwwroot.'/local/dashboard/assessment_due.php'?>"
      class="small-box-footer" style="color: rgb(215, 215, 215);">More info <i class="fas fa-arrow-circle-right"></i></a>
  </div>

  <div class="row px-5" style="display:flex;justify-content: space-between;">
    <?php [$dueover, $due1week, $due2week] = \local_dashboard\helper\pending_assessment::assessment_due_trainer();?>
    <p style="color:#fff;font-weight: 600;">Overdue</p>
    <p class="custom-numbers-dashboard-cards bg-danger color-white"> <?=$dueover?></p>
</div>
<div class="row px-5" style="display:flex;justify-content: space-between;">
  <p style="color:#fff;font-weight: 600;">This Week</p>
  <p class="custom-numbers-dashboard-cards"> <?=$due1week?></p>
</div>
<div class="row px-5" style="display:flex;justify-content: space-between;">
    <p style="color:#fff;font-weight: 600;">Next Week</p>
    <p class="custom-numbers-dashboard-cards"> <?=$due2week?></p>
</div>

</div>

<div class="custom-type4-dashboard custom-type4-50-wid" style="padding: 10px;">
    <div style="width: 100%;display:flex;justify-content: space-between;">
      <?php [$dueover, $due1week, $due2week] = \local_dashboard\helper\pending_assessment::assessment_markingdue_trainer();?>
      <p style="color:#fff;font-weight: 600;">Marking Due (Trainers)</p>
      <a href="<?= $CFG->wwwroot.'/local/dashboard/assessment_markingdue.php'?>"
      class="small-box-footer" style="color: rgb(215, 215, 215);">More info <i class="fas fa-arrow-circle-right"></i></a>
  </div>

  <div class="row px-5" style="display:flex;justify-content: space-between;">
      <p style="color:#fff;font-weight: 600;">Overdue</p>
      <p class="custom-numbers-dashboard-cards bg-danger color-white"> <?=$dueover?></p>
  </div>
  <div class="row px-5" style="display:flex;justify-content: space-between;">
    <p style="color:#fff;font-weight: 600;">This Week</p>
    <p class="custom-numbers-dashboard-cards"> <?=$due1week?></p>
</div>
<div class="row px-5" style="display:flex;justify-content: space-between;">
  <p style="color:#fff;font-weight: 600;">Next Week</p>
  <p class="custom-numbers-dashboard-cards"> <?=$due2week?></p>
</div>



</div>


</div>

<!-- ############################################################### -->
<!-- ############################################################### -->




<!--<div class="row">
     <div class="col-lg-12 col-12">
      <div class='container'>
        <div id="piechart" class="pie-chart" style="width: 100%; height: 320px; "></div>
      </div>
    </div>
    <div class="col-lg-12 col-12">
      <div class='container'>
         <div id="piechart" ></div>   style="width: 100%; height: 800px;" >
        <div id="piechart2" class="pie-chart" style="width: 100%; height: 320px; "></div>
      </div>
    </div>
</div>-->
<!--<div class="row">
        <div class="col-sm-4">
                          <a href="<?php echo $CFG->wwwroot . '/local/createuser/user_list.php'; ?>" class="ff_one">
                            <div class="inner text-center detais">
                              <p>Total Inactive Users</p>
                              <?php echo count($last24week) + count($last48week) + count($more8week); ?>
                              <p>2-4 Week : <?php echo count($last24week); ?></p>
                              <p>4-8 Week : <?php echo count($last48week); ?></p>
                              <p>8 or more : <?php echo count($more8week); ?></p>
                            </div>
                          </a>
                        </div>
                    </div>-->


                    <?php
} else if ($role_shortname->shortname === "rtoadmin" || $role_shortname->shortname === "subrtoadmin") {
		/* echo $USER->id;
       echo $id;  */
		$rto_count = $DB->count_records("school", ['id' => $USER->id]);
		$university_id = $_SESSION['university_id'];
		/*echo '<br>' . $university_id; */
		$uni_user_count = $DB->count_records("university_user", ['university_id' => $university_id]);
		$coursecount = $DB->count_records("assign_course", ['university_id' => $university_id]);
		$present = $DB->count_records("autoattend_students", ['status' => 'P']);
		//echo $present;
		$absent = $DB->count_records("autoattend_students", ['status' => 'X']);
		//echo $absent;
		$coursemodulecount = $DB->count_records("course_modules", ['tenent_id' => $university_id]);
		/*$attend = "SELECT COUNT(*) FROM mdl_autoattend_students
        WHERE studentid IN (SELECT userid FROM mdl_university_user WHERE university_id = $university_id)"; */

		$groups = $DB->get_records_sql("SELECT {groups}.id FROM {user_enrolments} inner join {enrol} on {user_enrolments}.enrolid = {enrol}.id inner join {university_user} on {university_user}.userid = {user_enrolments}.userid inner join {user} on {user}.id = {university_user}.userid inner join {groups} on {groups}.courseid = {enrol}.courseid where {university_user}.university_id = '$university_id'");
		$totalactivities = $DB->get_records_sql("SELECT {course_modules}.id FROM {user_enrolments} inner join {enrol} on {user_enrolments}.enrolid = {enrol}.id inner join {university_user} on {university_user}.userid = {user_enrolments}.userid inner join {user} on {user}.id = {university_user}.userid inner join {course_modules} on {course_modules}.course  = {enrol}.courseid where {university_user}.university_id = '$university_id'");
		$totalstudents = $DB->get_records_sql("SELECT {university_user}.userid  FROM {role_assignments} inner join {university_user} on {university_user}.userid = {role_assignments}.userid  where {university_user}.university_id = '$university_id' and {role_assignments}.roleid = 5");
		$totaltrainer = $DB->get_records_sql("SELECT {university_user}.userid FROM {role_assignments} inner join {university_user} on {university_user}.userid = {role_assignments}.userid  where {university_user}.university_id = '$university_id' and ({role_assignments}.roleid = 3 or {role_assignments}.roleid = 4)");
		$totaladmin = $DB->get_records_sql("SELECT {university_user}.userid FROM {role_assignments} inner join {university_user} on {university_user}.userid = {role_assignments}.userid  where {university_user}.university_id = '$university_id' and ({role_assignments}.roleid = 9 or {role_assignments}.roleid = 10) ");

		$last24week = $DB->get_records_sql("SELECT {user}.id FROM {user} inner join {university_user} on {university_user}.userid = {user}.id where {university_user}.university_id = '$university_id' AND week(FROM_UNIXTIME({user}.lastlogin)) >= WEEK( current_date ) - 4 and week(FROM_UNIXTIME({user}.lastlogin)) <= WEEK( current_date ) - 2;");
		$last48week = $DB->get_records_sql("SELECT {user}.id FROM {user} inner join {university_user} on {university_user}.userid = {user}.id where {university_user}.university_id = '$university_id' AND week(FROM_UNIXTIME({user}.lastlogin)) >= WEEK( current_date ) - 8 and week(FROM_UNIXTIME({user}.lastlogin)) <= WEEK( current_date ) - 4;");
		$more8week = $DB->get_records_sql("SELECT {user}.id FROM {user} inner join {university_user} on {university_user}.userid = {user}.id where {university_user}.university_id = '$university_id' AND week(FROM_UNIXTIME({user}.lastlogin)) <= WEEK( current_date ) - 8 ;");
		$uni_user = $DB->get_record("university_user", ['university_id' => $university_id]);
		$module_course_completed_count = $DB->count_records("course_modules_completion", array('userid' => "$uni_user->userid", 'completionstate' => '1'));
		$module_course_completed = ($module_course_completed_count / $coursecount) * 100;
		$module_course_incompleted = (100 - $module_course_completed) * -1;

		$unassignedticket = $DB->count_records("block_helpdesk_ticket", ['assigned_refs' => '0']);
		$openticket = $DB->get_records_sql("SELECT * FROM {block_helpdesk_ticket} where status = '1' OR status = '6'");
		$unresolvedticket = $DB->count_records("block_helpdesk_ticket", ['status' => '2']);

		//var_dump($module_course_completed_count);
		//die;

		?>

        <div style="width:100%;background:#B578FF;border-radius:10px;padding:5px 10px;margin-bottom:50px;">
          <p style="color:#fff;padding-top:10px">Learners Eligible for Billing ( Period Ending June 14, 2023 )</p>
      </div>


      <div class="row" style="justify-content: space-between;">
          <!-- ./col --->
  <!--<div class="col-sm-4">
                          <a href="<?php echo $CFG->wwwroot . '/my/courses.php'; ?>" class="ff_one">
                            <div class="detais">
                              <p>Total Units</p>
                              <?php echo $coursecount; ?>
                            </div>
                            <div class="ff_icon"><span class="fa fa-book"></span></div>
                          </a>
                      </div>-->
                      <div class="custom-type4-dashboard">
                        <div class="custom-faicon" style="text-align: center;">
                          <i class='fas fa-book'></i>
                      </div>
                      <!-- small box -->
                      <div class="cutom-heading-box">
                          <p class="custom-heading-p-box">Total Units</p>
                      </div>


                      <div style="width:100%; padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '/my/courses.php'; ?>" class="small-box-footer" style="color: white;">More
                              info <i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                          <div style="width:50%; float: left;">
                            <p class="custom-numbers-dashboard-cards" style="float: right;"> <?php echo $coursecount; ?></p>
                        </div>
                    </div>

                </div>
                <!-- ./col -->
  <!--<div class="col-sm-4">
                          <a href="<?php echo $CFG->wwwroot . '/local/createuser/user_list.php'; ?>" class="ff_one style2">
                            <div class="detais">
                              <p>Total Groups</p>
                              <?php echo count($groups); ?>
                            </div>
                            <div class="ff_icon"><span class="fa fa-users"></span></div>
                          </a>
                      </div>-->
                      <div class="custom-type4-dashboard">
                        <div class="custom-faicon" style="text-align: center;">
                          <i class='fas fa-users'></i>
                      </div>
                      <!-- small box -->
                      <div class="cutom-heading-box">
                          <p class="custom-heading-p-box">Total Groups</p>
                      </div>


                      <div style="width:100%; padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '/local/createuser/user_list.php'; ?>" class="small-box-footer"
                              style="color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                          <div style="width:50%; float: left;">
                            <p class="custom-numbers-dashboard-cards" style="float: right;"> <?php echo count($groups); ?></p>
                        </div>
                    </div>

                </div>
                <!-- ./col -->
  <!--<div class="col-sm-4">
                          <a href="<?php echo $CFG->wwwroot . '/my/courses.php'; ?>" class="ff_one style3">
                            <div class="detais">
                              <p>Total Activities</p>
                              <?php echo count($totalactivities); ?>
                            </div>
                            <div class="ff_icon"><span class="fa fa-tasks"></span></div>
                          </a>
                      </div>-->
                      <div class="custom-type4-dashboard">
                        <div class="custom-faicon" style="text-align: center;">
                          <i class='fas fa-tasks'></i>
                      </div>
                      <!-- small box -->
                      <div class="cutom-heading-box">
                          <p class="custom-heading-p-box">Total Activities</p>
                      </div>


                      <div style="width:100%; padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '/my/courses.php'; ?>" class="small-box-footer" style="color: white;">More
                              info <i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                          <div style="width:50%; float: left;">
                            <p class="custom-numbers-dashboard-cards" style="float: right;"> <?php echo count($totalactivities); ?></p>
                        </div>
                    </div>

                </div>
            </div>
            <br><br>
            <!-- 2nd Row-->
            <div class="row" style="justify-content: space-between;">
  <!--<div class="col-sm-4">
                          <a href="<?php echo $CFG->wwwroot . '/local/createuser/user_list.php'; ?>" class="ff_one">
                            <div class="inner text-center detais">
                              <p>Total Users</p>
                              <?php echo count($totalstudents) + count($totaltrainer) + count($totaladmin); ?>
                              <p>Students : <?php echo count($totalstudents); ?></p>
                              <p>Trainers : <?php echo count($totaltrainer); ?></p>
                              <p>Admin : <?php echo count($totaladmin); ?></p>
                            </div>
                          </a>
                      </div>-->

                      <div class="custom-type4-dashboard">
                        <!-- small box -->
                        <div class="cutom-heading-box">
                          <p class="custom-heading-p-box-type2">Total Users</p>
                      </div>
                      <div style="text-align:center;">
                          <p class="custom-numbers-dashboard-cards" style="float: unset;">
                            <?php echo count($totalstudents) + count($totaltrainer) + count($totaladmin); ?></p>
                        </div>
                        <div style="text-align:center; color: white;">
                          <p>Students : <?php echo count($totalstudents); ?></p>
                          <p>Trainers : <?php echo count($totaltrainer); ?></p>
                          <p>Admin : <?php echo count($totaladmin); ?></p>
                      </div>


                      <div style="width:100%; padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '/local/createuser/user_list.php'; ?>" class="small-box-footer"
                              style="color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                      </div>
                  </div>


                  <!--./col-->
  <!--<div class="col-sm-4">
                          <a href="<?php echo $CFG->wwwroot . '/local/createuser/user_list.php'; ?>" class="ff_one style2">
                            <div class="inner text-center detais">
                              <p>Inactive Users</p>
                              <?php echo count($last24week) + count($last48week) + count($more8week); ?>
                              <p>2-4 Weeks : <?php echo count($last24week); ?></p>
                              <p>4-8 Weeks : <?php echo count($last48week); ?></p>
                              <p>8 Weeks or More : <?php echo count($more8week); ?></p>
                            </div>
                          </a>
                      </div>-->

                      <div class="custom-type4-dashboard">
                        <!-- small box -->
                        <div class="cutom-heading-box">
                          <p class="custom-heading-p-box-type2">Inactive Users</p>
                      </div>
                      <div style="text-align:center;">
                          <p class="custom-numbers-dashboard-cards" style="float: unset;">
                            <?php echo count($last24week) + count($last48week) + count($more8week); ?></p>
                        </div>
                        <div style="text-align:center; color: white;">
                          <p>2-4 Weeks : <?php echo count($last24week); ?></p>
                          <p>4-8 Weeks : <?php echo count($last48week); ?></p>
                          <p>8 Weeks or More : <?php echo count($more8week); ?></p>
                      </div>


                      <div style="width:100%; padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '/local/createuser/user_list.php'; ?>" class="small-box-footer"
                              style="color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                      </div>
                  </div>

                  <!--./col-->
  <!--<div class="col-sm-4">
                          <a href="<?php echo $CFG->wwwroot . '/local/dashboard/resendinvite_user.php'; ?>" class="ff_one style3">
                            <div class="inner text-center detais">
                              <p>Invite Pending</p>
                              <?php echo count($last24week) + count($last48week); ?>
                              <p>Expired : <?php echo count($last24week); ?></p>
                              <p>Not Signed In : <?php echo count($last48week); ?></p>
                            </div>
                          </a>
                      </div>-->
                      <div class="custom-type4-dashboard">
                        <!-- small box -->
                        <div class="cutom-heading-box">
                          <p class="custom-heading-p-box-type2">Invite Pending</p>
                      </div>
                      <div style="text-align:center;">
                          <p class="custom-numbers-dashboard-cards" style="float: unset;">
                            <?php echo count($last24week) + count($last48week); ?></p>
                        </div>
                        <div style="text-align:center; color: white;">
                          <p>Expired : <?php echo count($last24week); ?></p>
                          <p>Not Signed In : <?php echo count($last48week); ?></p>
                      </div>


                      <div style="width:100%; padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '/local/dashboard/resendinvite_user.php'; ?>" class="small-box-footer"
                              style="color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                      </div>
                  </div>
              </div>
              <br><br>


              <!-- ############################################################### -->
              <!-- ############################################################### -->
              <!-- ############################################################### -->
              <!-- ############################################################### -->
              <!-- ############################################################### -->
              <!-- ############################################################### -->
              <!-- ############################################################### -->
              <!-- ############################################################### -->

              <?php

		$now = time();

		$sql_1_ncs = "SELECT
c.id,
c.fullname AS 'coursefullname',
COUNT(DISTINCT(cmc1.userid)) AS ncsztot,
COUNT(DISTINCT(cmc2.userid)) AS ncsttos,
COUNT(DISTINCT(cmc3.userid)) AS ncssplus
FROM mdl_course_modules_completion cmc
LEFT JOIN mdl_course_modules_completion cmc1 ON cmc1.userid = cmc.userid AND cmc1.timemodified < $now -15*24*60*60 AND cmc1.timemodified > $now -30*24*60*60
LEFT JOIN mdl_course_modules_completion cmc2 ON cmc2.userid = cmc.userid AND cmc2.timemodified < $now - 30*24*60*60 -1 AND cmc2.timemodified > $now -60*24*60*60
LEFT JOIN mdl_course_modules_completion cmc3 ON cmc3.userid = cmc.userid AND cmc3.timemodified < $now - 60*24*60*60 -1
JOIN mdl_user u ON u.id = cmc.userid
JOIN mdl_course_modules cm ON cmc.coursemoduleid = cm.id
JOIN mdl_course c ON cm.course = c.id
JOIN mdl_assign_course ac ON ac.course_id = c.id
JOIN mdl_modules m ON cm.module = m.id
WHERE cmc.completionstate <> 2 AND ac.university_id = ?
GROUP BY c.id
LIMIT 10";

		$ncs_records = $DB->get_records_sql($sql_1_ncs, [$SESSION->university_id]);
		?>

              <div class="row" style="justify-content: space-between;">
                  <div style="width: 100%;">
                    <H3 style="float: left;padding-left: 2%;">NOT YET COMPETENT SUMMARY</H3>
                    <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=alltickets'; ?>"
                      style="float: right;padding-right: 2%;" class="small-box-footer" style="color: white;">See All <i
                      class="fas fa-arrow-circle-right"></i></a>
                      <br>
                      <hr>
                  </div>
                  <!-- ./col -->

                  <div class="custom-type4-dashboard custom-type4-50-wid" style="padding: 10px;">
                    <div style="width: 100%;display:flex;justify-content: space-between;">
                      <p style="color:#fff;font-weight: 600;">Not Yet Competent Students</p>
                      <a href="<?php echo $CFG->wwwroot . '/local/dashboard/non-competent-students.php'; ?>"
                      class="small-box-footer" style="color: rgb(215, 215, 215);">More info <i class="fas fa-arrow-circle-right"></i></a>
                  </div>

                  <div class="table-responsive" >
                      <table class="table table-dark table-striped" style="border-radius: 15px;border:3px solid #8e30ff">
                        <thead style="background-color: #8e30ff;">
                          <tr>
                            <th scope="col">Units</th>
                            <th scope="col">15-30 Days</th>
                            <th scope="col">31-60 Days</th>
                            <th scope="col">61+ Days</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
if ($ncs_records) {
			foreach ($ncs_records as $key => $value) {
				?>
                            <tr>
                        <th scope="row"><?php echo $value->fullname; ?> </th>
                        <td><?php echo $value->ncsztot; ?></td>
                        <td><?php echo $value->ncsttos; ?></td>
                        <td><?php echo $value->ncssplus; ?></td>
                    </tr>

                            <?php
}
		} else {
			$nodata = "No data found";
			?>
			 <tr> <th scope="row">No data found</th> </tr>

             <?php
}

		?>


                </tbody>
            </table>
        </div>

    </div>


    <?php

		$not_competent_sub_sql = "SELECT
c.id,
c.fullname AS 'coursefullname',
COUNT(DISTINCT(cmc.userid)) AS non_competent_students_count
FROM mdl_course_modules_completion cmc
JOIN mdl_user u ON u.id = cmc.userid
JOIN mdl_course_modules cm ON cmc.coursemoduleid = cm.id
JOIN mdl_course c ON cm.course = c.id
JOIN mdl_assign_course ac ON ac.course_id = c.id
JOIN mdl_modules m ON cm.module = m.id
WHERE cmc.completionstate <> 2 AND ac.university_id = ?
GROUP BY c.id
ORDER BY non_competent_students_count
LIMIT 10";

		$ncsub_records = $DB->get_records_sql($not_competent_sub_sql, [$SESSION->university_id]);
		if ($ncsub_records) {
			// $series = 'series';
			$barchart = new core\chart_bar();
			$CFG->chart_colorset = ['#001f3f', '#01ff70', '#F012BE', '#85144b', '#B10DC9'];
			$barchart->get_xaxis(0, true)->set_label("Courses");
			$barchart->get_yaxis(0, true)->set_label("No of students");
			$barchart->get_yaxis(0, true)->set_stepsize(10);
			$labels = [];
			$barchart->set_legend_options(['display' => false]);
			$barseries = [];

			foreach ($ncsub_records as $key => $value) {
				$barseries[] = $value->non_competent_students_count;
				$labels[] = $value->coursefullname;

			}

			$series = new core\chart_series('Not competent students', $barseries);
			$barchart->add_series($series);
			$barchart->set_labels($labels);

		}
		?>

    <div class="custom-type4-dashboard custom-type4-50-wid" style="padding: 10px;">
        <div style="width: 100%;display:flex;justify-content: space-between;">
          <p style="color:#fff;font-weight: 600;">Not Yet Competent Subjects</p>
          <a href="<?php echo $CFG->wwwroot . '/local/dashboard/non-competent-subjects.php'; ?>"
          class="small-box-footer" style="color: rgb(215, 215, 215);">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>

      <div>
          <div id="ogchart">
            <?php
if ($ncsub_records) {
			echo $OUTPUT->render_chart($barchart, false);

		} else {
			echo "No data found";
		}
		?>
          </div>
      </div>

  </div>


</div>


<!-- ############################################################### -->
<!-- ############################################################### -->


<br><br>
<div class="row" style="justify-content: space-between;">
  <div style="width: 100%;">
    <H3 style="float: left;padding-left: 2%;">PENDING ASSESSMENTS</H3>
    <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=alltickets'; ?>"
      style="float: right;padding-right: 2%;" class="small-box-footer" style="color: white;">See All <i
      class="fas fa-arrow-circle-right"></i></a>
      <br>
      <hr>
  </div>
  <!-- ./col -->

  <div class="custom-type4-dashboard custom-type4-50-wid" style="padding: 10px;">
    <div style="width: 100%;display:flex;justify-content: space-between;">
      <?php [$dueover, $due1week, $due2week] = \local_dashboard\helper\pending_assessment::assessment_due_rtoadmin();?>
      <p style="color:#fff;font-weight: 600;">Assessments Due (Students)</p>
      <a href="<?= $CFG->wwwroot.'/local/dashboard/assessment_due.php'?>"
      class="small-box-footer" style="color: rgb(215, 215, 215);">More info <i class="fas fa-arrow-circle-right"></i></a>
  </div>

  <div class="row px-5" style="display:flex;justify-content: space-between;">
    <p style="color:#fff;font-weight: 600;">Overdue</p>
    <p class="custom-numbers-dashboard-cards bg-danger color-white"> <?=$dueover?></p>
</div>
<div class="row px-5" style="display:flex;justify-content: space-between;">
  <p style="color:#fff;font-weight: 600;">This Week</p>
  <p class="custom-numbers-dashboard-cards"> <?=$due1week?></p>
</div>
<div class="row px-5" style="display:flex;justify-content: space-between;">
    <p style="color:#fff;font-weight: 600;">Next Week</p>
    <p class="custom-numbers-dashboard-cards"> <?=$due2week?></p>
</div>

</div>

<div class="custom-type4-dashboard custom-type4-50-wid" style="padding: 10px;">
    <div style="width: 100%;display:flex;justify-content: space-between;">
      <?php [$dueover, $due1week, $due2week] = \local_dashboard\helper\pending_assessment::assessment_markingdue_rtoadmin();?>
      <p style="color:#fff;font-weight: 600;">Marking Due (Trainers)</p>
      <a href="<?= $CFG->wwwroot.'/local/dashboard/assessment_markingdue.php'?>"
      class="small-box-footer" style="color: rgb(215, 215, 215);">More info <i class="fas fa-arrow-circle-right"></i></a>
  </div>

  <div class="row px-5" style="display:flex;justify-content: space-between;">
      <p style="color:#fff;font-weight: 600;">Overdue</p>
      <p class="custom-numbers-dashboard-cards bg-danger color-white"> <?=$dueover?></p>
  </div>
  <div class="row px-5" style="display:flex;justify-content: space-between;">
    <p style="color:#fff;font-weight: 600;">This Week</p>
    <p class="custom-numbers-dashboard-cards"> <?=$due1week?></p>
</div>
<div class="row px-5" style="display:flex;justify-content: space-between;">
  <p style="color:#fff;font-weight: 600;">Next Week</p>
  <p class="custom-numbers-dashboard-cards"> <?=$due2week?></p>
</div>



</div>


</div>

<!-- ############################################################### -->
<!-- ############################################################### -->

<br><br>
<div class="row" style="justify-content: space-between;">
  <div style="width: 100%;">
    <H3 style="float: left;padding-left: 2%;">ATTENDANCE</H3>
    <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=alltickets'; ?>"
      style="float: right;padding-right: 2%;" class="small-box-footer" style="color: white;">See All <i
      class="fas fa-arrow-circle-right"></i></a>
      <br>
      <hr>
  </div>
  <!-- ./col -->

  <div class="custom-type4-dashboard custom-type4-100-wid" style="padding: 10px;">

    <div class="row px-5" style="display:flex;justify-content: space-between;">
        <div style="color:#fff;font-weight: 600;">

          <div class="mt-4 mb-3 mx-2 px-3 py-2"  style="background-color: #c6fad6;border-radius: 10px;">
            <h3 class="mb-0">Take Attendance</h3>
        </div>

        <div class="mt-3 mb-4 mx-2 px-3 py-2"  style="background-color: #8E30FF;border-radius: 10px;">
            <h3 class="mb-0" style="color:white;">Today's Absentees</h3>
        </div>

    </div>
    <div style="color:#fff;font-weight: 600;">Calendar-</div>
    <div style="color:#fff;font-weight: 600;"><div id="ogchart2" style="width: 100%;"></div></div>
</div>


</div>




</div>

<!-- ############################################################### -->
<!-- ############################################################### -->
<!-- ############################################################### -->
<!-- ############################################################### -->
<!-- ############################################################### -->
<!-- ############################################################### -->
<!-- ############################################################### -->
<!-- ############################################################### -->
<!-- ############################################################### -->
<!-- ############################################################### -->
<!-- ############################################################### -->
<!-- ############################################################### -->




<br><br>
<div class="row" style="justify-content: space-between;">
  <div style="width: 100%;">
    <H3 style="float: left;padding-left: 2%;">Tickets</H3>
    <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=alltickets'; ?>"
      style="float: right;padding-right: 2%;" class="small-box-footer" style="color: white;">See All <i
      class="fas fa-arrow-circle-right"></i></a>
      <br>
      <hr>
  </div>
  <!-- ./col -->
  <!--<div class="col-sm-4">
                          <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=unassignedtickets'; ?>" class="ff_one">
                            <div class="detais">
                              <p>Unassigned</p>
                              <?php echo $unassignedticket; ?>
                            </div>
                            <div class="ff_icon"><span class="fa fa-exclamation"></span></div>
                          </a>
                      </div>-->
                      <div class="custom-type4-dashboard" style="padding: 10px;">
                        <div style="width:50%; float: left;">
                          <p class="custom-numbers-dashboard-cards" style="float: left;"> <?php echo $unassignedticket; ?></p>
                      </div>
                      <!-- small box -->
                      <div class="cutom-heading-box">
                          <p class="custom-heading-p-box" style="font-weight: unset;">Unassigned</p>
                      </div>


                      <div style="width:100%; padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=unassignedtickets'; ?>"
                              class="small-box-footer" style="color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
                          </div>

                      </div>

                  </div>
                  <!-- ./col -->
  <!--<div class="col-sm-4">
                          <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=newtickets'; ?>" class="ff_one style2">
                            <div class="detais">
                              <p>Open</p>
                              <?php echo count($openticket); ?>
                            </div>
                            <div class="ff_icon"><span class="fa fa-exclamation-triangle"></span></div>
                          </a>
                      </div>-->
                      <div class="custom-type4-dashboard" style="padding: 10px;">
                        <div style="width:50%; float: left;">
                          <p class="custom-numbers-dashboard-cards" style="float: left;"> <?php echo count($openticket); ?></p>
                      </div>
                      <!-- small box -->
                      <div class="cutom-heading-box">
                          <p class="custom-heading-p-box" style="font-weight: unset;">Open</p>
                      </div>


                      <div style="width:100%; padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=newtickets'; ?>" class="small-box-footer"
                              style="color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
                          </div>

                      </div>

                  </div>
                  <!-- ./col -->
  <!--<div class="col-sm-4">
                          <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=alltickets'; ?>" class="ff_one style3">
                            <div class="detais">
                              <p>Unresolved</p>
                              <?php echo $unresolvedticket; ?>
                            </div>
                            <div class="ff_icon"><span class="fa fa-exclamation-triangle"></span></div>
                          </a>
                      </div>-->
                      <div class="custom-type4-dashboard" style="padding: 10px;">
                        <div style="width:50%; float: left;">
                          <p class="custom-numbers-dashboard-cards" style="float: left;"> <?php echo $unresolvedticket; ?></p>
                      </div>
                      <!-- small box -->
                      <div class="cutom-heading-box">
                          <p class="custom-heading-p-box" style="font-weight: unset;">Unresolved</p>
                      </div>


                      <div style="width:100%; padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=alltickets'; ?>" class="small-box-footer"
                              style="color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
                          </div>

                      </div>

                  </div>
              </div>




<!--<div class="row">
     <div class="col-lg-12 col-12">
      <div class='container'>
        <div id="piechart" class="pie-chart" style="width: 100%; height: 320px; "></div>
      </div>
    </div>
    </div>

            <div class="col-lg-12 col-12">
      <div class='container'>
        <div id="piechart2" class="pie-chart" style="width: 100%; height: 320px; "></div>
      </div>
  </div><br>-->




  <?php
}
} else {

	$all_packages = $DB->get_records_sql("SELECT * FROM {package}");
	//$all_university= $DB->get_records_sql("SELECT * FROM {school}");
	$resource_course_id = $DB->get_record("course_categories", ['idnumber' => 'resourcecat']);
	$all_courses = $DB->get_records_sql("SELECT * FROM {course} WHERE category !=0  AND category !=$resource_course_id->id");
	$coursecount = $DB->count_records("course", ['cb_userid' => 2]);
	$user_count = $DB->count_records("user");
	$rto_count = $DB->count_records("school");
	$package_count = $DB->count_records("package");

	$all_university = $DB->get_records_sql("SELECT * FROM {school} ORDER BY name DESC LIMIT 10 ");
	$unassignedticket = $DB->count_records("block_helpdesk_ticket", ['assigned_refs' => '0']);
	$openticket = $DB->get_records_sql("SELECT * FROM {block_helpdesk_ticket} where status = '1' OR status = '6'");
	$unresolvedticket = $DB->count_records("block_helpdesk_ticket", ['status' => '2']);
	//var_dump($unresolvedticket); die;

	?>

    <br>

    <div class="row" style="justify-content: space-between;">
      <!-- ./col -->

  <!--<div class="col-sm-4">
                          <a href="<?php echo $CFG->wwwroot . '/local/dashboard/table.php'; ?>" class="ff_one">
                            <div class="detais">
                              <p>Total Client</p>
                              <?php echo $rto_count; ?>
                            </div>
                            <div class="ff_icon"><span class="fa fa-university"></span></div>
                          </a>
                      </div>-->


                      <div class="custom-type4-dashboard">
                        <div class="custom-faicon" style="text-align: center;">
                          <i class='fas fa-university'></i>
                      </div>
                      <!-- small box -->
                      <div class="cutom-heading-box">
                          <p class="custom-heading-p-box">Total Client</p>
                      </div>


                      <div style="width:100%; padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '/local/dashboard/table.php'; ?>" class="small-box-footer"
                              style="color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                          <div style="width:50%; float: left;">
                            <p class="custom-numbers-dashboard-cards" style="float: right;"> <?php echo $rto_count; ?></p>
                        </div>
                    </div>

                </div>

                <!-- ./col -->
  <!--<div class="col-sm-4">
                          <a href="<?php echo $CFG->wwwroot . '/admin/user.php'; ?>" class="ff_one style2">
                            <div class="detais">
                              <p>Total Users</p>
                              <?php echo $user_count; ?>
                            </div>
                            <div class="ff_icon"><span class="fa fa-users"></span></div>
                          </a>
                      </div>-->

                      <div class="custom-type4-dashboard">
                        <div class="custom-faicon" style="text-align: center;">
                          <i class='fas fa-users'></i>
                      </div>
                      <!-- small box -->
                      <div class="cutom-heading-box">
                          <p class="custom-heading-p-box">Total Users</p>
                      </div>


                      <div style="width:100%;padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '/admin/user.php'; ?>" class="small-box-footer" style="color: white;">More
                              info <i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                          <div style="width:50%; float: left;">
                            <p class="custom-numbers-dashboard-cards" style="float: right;"> <?php echo $user_count; ?></p>
                        </div>
                    </div>

                </div>

                <!-- ./col -->
  <!--<div class="col-sm-4">
                          <a href="<?php echo $CFG->wwwroot . '/course/management.php'; ?>" class="ff_one style3">
                            <div class="detais">
                              <p>Total Units</p>
                              <?php echo $coursecount; ?>
                            </div>
                            <div class="ff_icon"><span class="fa fa-book"></span></div>
                          </a>
                      </div>--->
                      <div class="custom-type4-dashboard">
                        <div class="custom-faicon" style="text-align: center;">
                          <i class='fas fa-book'></i>
                      </div>
                      <!-- small box -->
                      <div class="cutom-heading-box">
                          <p class="custom-heading-p-box">Total Units</p>
                      </div>


                      <div style="width:100%;padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '/course/management.php'; ?>" class="small-box-footer"
                              style="color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                          <div style="width:50%; float: left;">
                            <p class="custom-numbers-dashboard-cards" style="float: right;"> <?php echo $coursecount; ?></p>
                        </div>
                    </div>

                </div>

            </div><br>
            <div class="row" style="justify-content: space-between;">
              <div style="width: 100%;">
                <H3 class="title float-left" style="float: left;padding-left: 2%;">Tickets</H3>
                <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=alltickets'; ?>"
                  style="float: right;padding-right: 2%;" class="small-box-footer" style="color: white;">See All <i
                  class="fas fa-arrow-circle-right"></i></a>
                  <br>
                  <hr>
              </div>
              <br>

              <!--./col-->
  <!--<div class="col-sm-4">
                          <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=unassignedtickets'; ?>" class="ff_one">
                            <div class="detais">
                              <p>Unassigned</p>
                              <?php echo $unassignedticket; ?>
                            </div>
                            <div class="ff_icon"><span class="fa fa-exclamation"></span></div>
                          </a>
                      </div>-->
                      <div class="custom-type4-dashboard">
                        <div class="custom-faicon" style="text-align: center;">
                          <i class='fas fa-exclamation'></i>
                      </div>
                      <!-- small box -->
                      <div class="cutom-heading-box">
                          <p class="custom-heading-p-box">Unassigned</p>
                      </div>


                      <div style="width:100%;padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=unassignedtickets'; ?>"
                              class="small-box-footer" style="color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                          <div style="width:50%; float: left;">
                            <p class="custom-numbers-dashboard-cards" style="float: right;"> <?php echo $unassignedticket; ?></p>
                        </div>
                    </div>

                </div>

                <!--./col-->
  <!--<div class="col-sm-4">
                          <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=newtickets'; ?>" class="ff_one style2">
                            <div class="detais">
                              <p>Open</p>
                              <?php echo count($openticket); ?>
                            </div>
                            <div class="ff_icon"><span class="fa fa-pencil-square-o"></span></div>
                          </a>
                      </div>-->
                      <div class="custom-type4-dashboard">
                        <div class="custom-faicon" style="text-align: center;">
                          <i class='fas fa-pencil-square-o'></i>
                      </div>
                      <!-- small box -->
                      <div class="cutom-heading-box">
                          <p class="custom-heading-p-box">Open</p>
                      </div>


                      <div style="width:100%;padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=newtickets'; ?>" class="small-box-footer"
                              style="color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                          <div style="width:50%; float: left;">
                            <p class="custom-numbers-dashboard-cards" style="float: right;"> <?php echo count($openticket); ?></p>
                        </div>
                    </div>

                </div>
                <!--./col-->
  <!--<div class="col-sm-4">
                          <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=alltickets'; ?>" class="ff_one style3">
                            <div class="detais">
                              <p>Unresolved</p>
                              <?php echo $unresolvedticket; ?>
                            </div>
                            <div class="ff_icon"><span class="fa fa-exclamation-triangle"></span></div>
                          </a>
                      </div>-->
                      <div class="custom-type4-dashboard">
                        <div class="custom-faicon" style="text-align: center;">
                          <i class='fas fa-exclamation-triangle'></i>
                      </div>
                      <!-- small box -->
                      <div class="cutom-heading-box">
                          <p class="custom-heading-p-box">Unresolved</p>
                      </div>


                      <div style="width:100%;padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '/blocks/helpdesk/search.php?rel=alltickets'; ?>" class="small-box-footer"
                              style="color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                          <div style="width:50%; float: left;">
                            <p class="custom-numbers-dashboard-cards" style="float: right;"> <?php echo $unresolvedticket; ?></p>
                        </div>
                    </div>

                </div>


            </div>
            <br><br>
            <div class="row">

              <div class="col-lg-12 col-12">
                <div class='container'>
                  <table id="tblUser" class="table table-striped table-bordered"
                  style="width:100%; font-size:12px;background: #17263C;    color: white;">
                  <tr>
                      <th colspan="4" align="center" class="text-center">
                        <h3 style="color: white;">Current Package Information</h3>
                    </th>
                </tr>
                <tr>
                  <th>#</th>
                  <th>Users</th>
                  <th>Units </th>
                  <th>Package Value ($)</th>
              </tr>
              <?php
$i = 1;
	foreach ($all_packages as $package) {?>
                <tr>
                  <td>Package <?php echo $i; ?></td>
                  <td><?php echo $package->num_of_user; ?></td>
                  <td><?php echo $package->num_of_course; ?></td>
                  <td>$<?php echo $package->package_value; ?> Monthly</td>
              </tr>
              <?php $i++;}?>
              <tr>
                  <th colspan="4" align="center" class="text-center"><a
                      href="<?php echo $CFG->wwwroot . '/local/createpackage/index.php'; ?>" class="small-box-footer">Customise <i
                      class="fas fa-arrow-circle-right"></i></a></th>
                  </tr>
                  <tr>
                      <th colspan="4" align="center" class="text-center"><a
                          href="<?php echo $CFG->wwwroot . '/local/createpackage/package_list.php'; ?>" class="small-box-footer">See
                          All <i class="fas fa-arrow-circle-right"></i></a></th>
                      </tr>
                  </table>
              </div>
          </div>
      </div>
      <br>
      <div class="row" style="justify-content: space-between;">
          <div style="width: 100%;">
            <H3 class="title float-left" style="float: left;padding-left: 2%;">Finances</H3>
            <a href="<?php echo $CFG->wwwroot . '#'; ?>" style="float: right;padding-right: 2%;" class="small-box-footer"
              style="color: white;">See All <i class="fas fa-arrow-circle-right"></i></a>
              <br>
              <hr>
          </div>

          <!--./col-->
  <!--<div class="col-sm-6">
                          <a href="<?php echo $CFG->wwwroot . '#'; ?>" class="ff_one">
                            <div class="detais">
                              <p>Unpaid Invoices</p>
                              <?php echo $unassignedticket; ?>
                            </div>
                            <div class="ff_icon"><span class="fa fa-file-text"></span></div>
                          </a>
                      </div>-->
                      <div class="custom-type4-dashboard" style="width:48%">
                        <div class="custom-faicon" style="text-align: center;margin-left: 33%;">
                          <i class='fas fa-file-text'></i>
                      </div>
                      <!-- small box -->
                      <div class="cutom-heading-box">
                          <p class="custom-heading-p-box">Unpaid Invoices</p>
                      </div>


                      <div style="width:100%;padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '#'; ?>" class="small-box-footer" style="color: white;">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                            </div>
                            <div style="width:50%; float: left;">
                                <p class="custom-numbers-dashboard-cards" style="float: right;"> <?php echo $unassignedticket; ?></p>
                            </div>
                        </div>

                    </div>
                    <!--./col-->
  <!--<div class="col-sm-6">
                          <a href="<?php echo $CFG->wwwroot . '#'; ?>" class="ff_one style2">
                            <div class="detais">
                              <p>Invoice Due Soon</p>
                              <?php echo count($openticket); ?>
                            </div>
                            <div class="ff_icon"><span class="fa fa-usd"></span></div>
                          </a>
                      </div>-->
                      <div class="custom-type4-dashboard" style="width:48%">
                        <div class="custom-faicon" style="text-align: center;margin-left: 33%;">
                          <i class='fas fa-usd'></i>
                      </div>
                      <!-- small box -->
                      <div class="cutom-heading-box">
                          <p class="custom-heading-p-box">Invoice Due Soon</p>
                      </div>


                      <div style="width:100%;padding-left: 15px;padding-right: 15px;">
                          <div style="width:50%; float: left;">
                            <a href="<?php echo $CFG->wwwroot . '#'; ?>" class="small-box-footer" style="color: white;">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                            </div>
                            <div style="width:50%; float: left;">
                                <p class="custom-numbers-dashboard-cards" style="float: right;"> <?php echo count($openticket); ?></p>
                            </div>
                        </div>

                    </div>
                </div>
                <br><br>
                <div class="row" style="justify-content: space-between;">
                  <div style="width: 100%;">
                    <H3 class="title float-left" style="float: left;padding-left: 2%;">Users</H3>
                    <a href="#" style="float: right;padding-right: 2%;" class="small-box-footer"
                    style="color: white;">See All <i class="fas fa-arrow-circle-right"></i></a>
                    <br>
                    <hr>
                </div>

                <div class="col-12">
                  <div class="mb-2 row d-flex" style="border-radius:15px;color:#fff; background-color: #8E30FF;justify-content: space-between;padding: 10px 15px;">
                    <div>
                      <h4 class="mb-1 color-white">Kangaroo Institute</h4>
                      <p><a href="#" class="color-white">Plan #1</a></p>
                  </div>
                  <div style="width: 32%;">
                      <p class="text-right">10/100</p>
                      <div class="d-flex">
                        <div style="background-color: #C6FAD6;width: 70%;height: 3px;"></div>
                        <div style="background-color: #3b3c3b;width: 30%;height: 3px;"></div>
                    </div>
                </div>
                <a href="" class="color-white mt-2" style="font-size: 20px;font-weight: bolder;"><i class="fas fa-chevron-right"></i></a>
            </div>
            <div class="mb-2 row d-flex" style="border-radius:15px;color:#fff; background-color: #8E30FF;justify-content: space-between;padding: 10px 15px;">
                <div>
                  <h4 class="mb-1 color-white">Kangaroo Institute</h4>
                  <p><a href="#" class="color-white">Plan #1</a></p>
              </div>
              <div style="width: 32%;">
                  <p class="text-right">10/100</p>
                  <div class="d-flex">
                    <div style="background-color: #C6FAD6;width: 70%;height: 3px;"></div>
                    <div style="background-color: #3b3c3b;width: 30%;height: 3px;"></div>
                </div>
            </div>
            <a href="" class="color-white mt-2" style="font-size: 20px;font-weight: bolder;"><i class="fas fa-chevron-right"></i></a>
        </div>
        <div class="mb-2 row d-flex" style="border-radius:15px;color:#fff; background-color: #8E30FF;justify-content: space-between;padding: 10px 15px;">
            <div>
              <h4 class="mb-1 color-white">Kangaroo Institute</h4>
              <p><a href="#" class="color-white">Plan #1</a></p>
          </div>
          <div style="width: 32%;">
              <p class="text-right">10/100</p>
              <div class="d-flex">
                <div style="background-color: #C6FAD6;width: 70%;height: 3px;"></div>
                <div style="background-color: #3b3c3b;width: 30%;height: 3px;"></div>
            </div>
        </div>
        <a href="" class="color-white mt-2" style="font-size: 20px;font-weight: bolder;"><i class="fas fa-chevron-right"></i></a>
    </div>
    <div class="mb-2 row d-flex" style="border-radius:15px;color:#fff; background-color: #8E30FF;justify-content: space-between;padding: 10px 15px;">
        <div>
          <h4 class="mb-1 color-white">Kangaroo Institute</h4>
          <p><a href="#" class="color-white">Plan #1</a></p>
      </div>
      <div style="width: 32%;">
          <p class="text-right">10/100</p>
          <div class="d-flex">
            <div style="background-color: #C6FAD6;width: 70%;height: 3px;"></div>
            <div style="background-color: #3b3c3b;width: 30%;height: 3px;"></div>
        </div>
    </div>
    <a href="" class="color-white mt-2" style="font-size: 20px;font-weight: bolder;"><i class="fas fa-chevron-right"></i></a>
</div>
<div class="mb-2 row d-flex" style="border-radius:15px;color:#fff; background-color: #8E30FF;justify-content: space-between;padding: 10px 15px;">
    <div>
      <h4 class="mb-1 color-white">Kangaroo Institute</h4>
      <p><a href="#" class="color-white">Plan #1</a></p>
  </div>
  <div style="width: 32%;">
      <p class="text-right">10/100</p>
      <div class="d-flex">
        <div style="background-color: #C6FAD6;width: 70%;height: 3px;"></div>
        <div style="background-color: #3b3c3b;width: 30%;height: 3px;"></div>
    </div>
</div>
<a href="" class="color-white mt-2" style="font-size: 20px;font-weight: bolder;"><i class="fas fa-chevron-right"></i></a>
</div>
</div>

</div>


<?php
$db_host = 'localhost';
	$db_user = 'elearngroup_vetmoodle';
	$db_pass = '=m2$jfM%mGrz';
	$db_name = 'elearngroup_moodle';

	$con = mysqli_connect("$db_host", "$db_user", "$db_pass", "$db_name");
	if (!$con) {
		# code...
		echo "Problem in database connection! Contact administrator!" . mysqli_error();
	} else {
		$sql = "SELECT * FROM mdl_package";
		$result = mysqli_query($con, $sql);

		$chart_data = "";
		while ($row = mysqli_fetch_array($result)) {

			$productname[] = $row['package_value'];
			$sales[] = $row['num_of_user'];
		}

	}?>
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script type="text/javascript">
  var ctx = document.getElementById("chartjs_bar").getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: < ? php echo json_encode($productname); ? > ,
      datasets : [{
        backgroundColor: [
          "#5969ff",
          "#ff407b",
          "#25d5f2",
          "#ffc750",
          "#2ec551",
          "#7040fa",
          "#ff504e",
          "#ff064e",
          "#ff444e"
          ],
        data: < ? php echo json_encode($sales); ? > ,
    }]
  },
  options: {
      legend: {
        display: true,
        position: 'bottom',

        labels: {
          fontColor: '#71748d',
          fontFamily: 'Circular Std Book',
          fontSize: 25,
      }
  },


}
});
</script>

<?php
}
// Create a course_in_list object to use the get_course_overviewfiles() method.
// var_dump($CFG->libdir . '/coursecatlib.php');

echo $OUTPUT->footer();
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
  .small-box {
    color: white !important;
    border-radius: 10px !important;
    padding: 6px;

    .inner {
      padding: 6px;
      color: white !important;
  }

  .small-box-footer {
      color: white !important;
  }

  .faicon {
      text-align: end;
      font-size: 71px;
      margin-top: -54px;
  }

  .inner_page_breadcrumb:before {
      background-image: none !important
  }
</style>


<script type="text/javascript">


  google.charts.load('current', {packages: ['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
      // Define the chart to be drawn.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Element');
      data.addColumn('number', 'Languages');
      data.addRows([
        ['Java', 66000],
        ['Python', 41000],
        ['JavaScript', 40000],
        ['C++', 33000],
        ['C#', 28000],
        ['PHP', 13000],
        ['Perl', 17000]
        ]);

      // Instantiate and draw the chart.
      // var chart = new google.visualization.ColumnChart(document.getElementById('ogchart'));
      // chart.draw(data, null);
  }
</script>
<script type="text/javascript">


  google.charts.load('current', {packages: ['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
      // Define the chart to be drawn.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Element');
      data.addColumn('number', 'Languages');
      data.addRows([
        ['Java', 66000],
        ['Python', 41000],
        ['JavaScript', 40000],
        ['C++', 33000],
        ['C#', 28000],
        ['PHP', 13000],
        ['Perl', 17000]
        ]);

      // Instantiate and draw the chart.
      // var chart = new google.visualization.ColumnChart(document.getElementById('ogchart3'));
      // chart.draw(data, null);
  }
</script>




<script type="text/javascript">

// Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
    function drawChart() {

  // Create the data table.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Present-Absent');
      data.addColumn('number', 'Attendance');
      data.addRows([
        ['Present', 80],
        ['Absent', 20],


        ]);

  // Set chart options
      var options = {

       slices: {
        0: { color: '#c6fad6' },
        1: { color: '#8E30FF' }
    }
};

  // Instantiate and draw our chart, passing in some options.
var chart = new google.visualization.PieChart(document.getElementById('ogchart2'));
chart.draw(data, options);
}
</script>



<script type="text/javascript">

// Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
    function drawChart() {

  // Create the data table.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Present-Absent');
      data.addColumn('number', 'Attendance');
      data.addRows([
        ['Present', 80],
        ['Absent', 20],


        ]);

  // Set chart options
      var options = {

       slices: {
        0: { color: '#c6fad6' },
        1: { color: '#8E30FF' }
    }
};

  // Instantiate and draw our chart, passing in some options.
var chart = new google.visualization.PieChart(document.getElementById('ogchart4'));
chart.draw(data, options);
}
</script>


<!-- student chart -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!--Load the AJAX API-->

<script type="text/javascript">
  google.charts.load('current', {
    'packages': ['corechart']
});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Order', 'Amount'],
      ['Completed Units ', parseInt('<?php echo $complete_course; ?>')],
      ['Pending Units', parseInt('<?php echo $overdue_courses; ?>')]

      ]);
    var options = {
      title: 'Units Summary'
  };
  var chart = new google.visualization.PieChart(document.getElementById('piechart1'));
  chart.draw(data, options);
}
</script>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!--Load the AJAX API-->

<script type="text/javascript">
  google.charts.load('current', {
    'packages': ['corechart']
});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Order', 'Amount'],
      ['Competency ', parseInt('<?php echo $module_course_completed; ?>')],
      ['In-Competency', parseInt('<?php echo $module_course_incompleted; ?>')]

      ]);
    var options = {
      title: 'Competency Summary'
  };
  var chart = new google.visualization.PieChart(document.getElementById('piechart2'));
  chart.draw(data, options);
}
</script>



<!-- RTO chart -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!--Load the AJAX API-->

<script type="text/javascript">
  google.charts.load('current', {
    'packages': ['corechart']
});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Order', 'Amount'],
      ['Present ', parseInt('<?php echo $present; ?>')],
      ['Absent ', parseInt('<?php echo $absent; ?>')]

      ]);
    var options = {
      title: 'ATTENDANCE'
  };
  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);
}
</script>
