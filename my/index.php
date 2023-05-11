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
 

require_once('../config.php');
global $DB,$USER,$OUTPUT;
$title = 'Dashboard';

$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('standard');



function get_course_image($courseid)
{
   global $COURSE;
   $url = '';
   
   $context = context_course::instance($courseid);
   $fs = get_file_storage();
   $files = $fs->get_area_files( $context->id, 'course', 'overviewfiles', 0 );

   foreach ( $files as $f )
   {
   if ( $f->is_valid_image() )
   {
      $url = moodle_url::make_pluginfile_url( $f->get_contextid(), $f->get_component(), $f->get_filearea(), null, $f->get_filepath(), $f->get_filename(), false );
   }
   }

   return $url;
}



$header = '';
$header.='<!DOCTYPE html>
<html lang="en">
   <head>
      <title>Course-Catalogue</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
     
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
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
</style>
</head>';
echo $header;

echo $OUTPUT->header();

if(!is_siteadmin())
{
    $roleid = $DB->get_record("role_assignments",['userid'=>$USER->id]);
    $role_shortname = $DB->get_record("role",['id'=>$roleid->roleid]);
    $stud_name = $DB->get_record("user",['id'=>$roleid->username]);
    //$coursecount = $DB->count_records("course", ['idnumber'=>'resourcecat']);
    $stud_courses = $DB->get_records_sql("SELECT * FROM {role_assignments} WHERE userid ='$USER->id'");

    $results =  $DB->get_records_sql("SELECT COUNT(enrolid) FROM mdl_user_enrolments");
    $enrol = $rows['COUNT(enrolid)'] .'<br>';
    $coursecount = $DB->count_records("user_enrolments", ['userid'=>$USER->id]);
    $complete_course = $DB->count_records("course_completions", ['userid'=>$USER->id]);
    $overdue_courses = $coursecount - $complete_course;
    
    
    
    //var_dump($results);
    
    
    
    
    
    if ($role_shortname->shortname === "student") 
    {
        echo "<h3>Hi " . "$USER->firstname" . ",<h3> <br>";
        $complete_course = $DB->count_records("course_completions", ['userid'=>$USER->id]);
        $overdue_courses = $coursecount - $complete_course;
            $module_course_completed = $DB->count_records("course_modules_completion",  array( 'userid'=> "$USER->id", 'completionstate' => '1' ));
            $module_course_incompleted = $DB->count_records("course_modules_completion",  array( 'userid'=> "$USER->id", 'completionstate' => '0' ));
            
            // echo $module_course_completed . '<br>';
            // echo $module_course_incompleted;
            

      
      ?>
      <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 style = "color: white;"><?php echo $USER->firstname . " " . $USER->lastname; ?></h3>

                <p style ="font-weight:900;">Student Name</p>
              </div>
              <div class="faicon" style = "text-align: right; font-size: 32px; margin-top: -65px; padding: 20px 5px 0 0;">
              <i class="fa fa-user"></i>
              </div>
              <!--<hr>
              <a href="#" class="small-box-footer" style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 style = "color: white;"><?php echo $coursecount; ?></h3>

                <p style ="font-weight:900;">Enrolled Units</p>
              </div>
              <div class="faicon" style = "text-align: right; font-size: 32px; margin-top: -65px;  padding: 20px 5px 0 0;">
                  <i class='fas fa-chalkboard-teacher'></i>
              </div>
              <!--<hr>
              <a href="#" class="small-box-footer"  style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 style = "color: white;"><?php echo $complete_course; ?></h3>

                <p style ="font-weight:900;">Units Completed</p>
              </div>
              <div class="faicon" style = "text-align: right; font-size: 32px; margin-top: -65px; padding: 20px 5px 0 0;">
              <i class='fas fa-award'></i>
              </div>
              <!--<hr>
              <a href="#" class="small-box-footer" style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>  -->
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 style = "color: white;"><?php echo $overdue_courses; ?></h3>

                <p style ="font-weight:900;">Units Pending </p>
              </div>
              <div class="faicon" style = "text-align: right; font-size: 32px; margin-top: -65px; padding: 20px 5px 0 0;">
                  <i class='fas fa-laptop-code'></i>
              </div>
              <!--<hr>
              <a href="#" class="small-box-footer" style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
        </div>
    <br><br>


        
    <div class="row">
     <div class="col-lg-12 col-12">
      <div class='container'> 
        <!-- <div id="piechart" ></div>   style="width: 100%; height: 800px;" > -->
        <div id="piechart1" class="pie-chart" style="width: 100%; height: 320px; "></div>
      </div>
    </div>
    </div>
    
    <div class="row">
     <div class="col-lg-12 col-12">
      <div class='container'> 
        <!-- <div id="piechart" ></div>   style="width: 100%; height: 800px;" > -->
        <div id="piechart2" class="pie-chart" style="width: 100%; height: 320px; "></div>
      </div>
    </div>
    </div>
    
    
    
    <?php
    }
    else if ($role_shortname->shortname === "trainer") 
    {
      echo "<h3> Hi ".$USER->firstname . " " . $USER->lastname . ",</h3><br>";
      $coursecount = $DB->count_records("user_enrolments", ['userid'=>$USER->id]);
      $getuniversityid = $DB->get_record("university_user", ['userid'=>$USER->id]);
      $studentsontechercourse = $DB->get_records_sql("SELECT {enrol}.courseid, {user_enrolments}.userid, {enrol}.roleid FROM {user_enrolments} inner join {enrol} on {user_enrolments}.enrolid = {enrol}.id inner join {university_user} on {university_user}.userid = {user_enrolments}.userid where {university_user}.university_id = '$getuniversityid->university_id' and {university_user}.userid = '$USER->id' and {enrol}.roleid = '5' ");
      $last24week = $DB->get_records_sql("SELECT {user_enrolments}.userid FROM {user_enrolments} inner join {enrol} on {user_enrolments}.enrolid = {enrol}.id inner join {university_user} on {university_user}.userid = {user_enrolments}.userid inner join {user} on {user}.id = {university_user}.userid where {university_user}.university_id = '$getuniversityid->university_id' and {university_user}.userid = '$USER->id' and {enrol}.roleid = '5' and week(FROM_UNIXTIME({user}.lastlogin)) >= WEEK( current_date ) - 4 and week(FROM_UNIXTIME({user}.lastlogin)) <= WEEK( current_date ) - 2");
      $last48week = $DB->get_records_sql("SELECT {user_enrolments}.userid FROM {user_enrolments} inner join {enrol} on {user_enrolments}.enrolid = {enrol}.id inner join {university_user} on {university_user}.userid = {user_enrolments}.userid inner join {user} on {user}.id = {university_user}.userid where {university_user}.university_id = '$getuniversityid->university_id' and {university_user}.userid = '$USER->id' and {enrol}.roleid = '5' and week(FROM_UNIXTIME({user}.lastlogin)) >= WEEK( current_date ) - 8 and week(FROM_UNIXTIME({user}.lastlogin)) <= WEEK( current_date ) - 4");
      $more8week = $DB->get_records_sql("SELECT {user_enrolments}.userid FROM {user_enrolments} inner join {enrol} on {user_enrolments}.enrolid = {enrol}.id inner join {university_user} on {university_user}.userid = {user_enrolments}.userid inner join {user} on {user}.id = {university_user}.userid where {university_user}.university_id = '$getuniversityid->university_id' and {university_user}.userid = '$USER->id' and {enrol}.roleid = '5' and week(FROM_UNIXTIME({user}.lastlogin)) <= WEEK( current_date ) - 8");
      $groups = $DB->get_records_sql("SELECT {groups}.id FROM {user_enrolments} inner join {enrol} on {user_enrolments}.enrolid = {enrol}.id inner join {university_user} on {university_user}.userid = {user_enrolments}.userid inner join {user} on {user}.id = {university_user}.userid inner join {groups} on {groups}.courseid = {enrol}.courseid where {university_user}.university_id = '$getuniversityid->university_id' and {university_user}.userid = '$USER->id'");
      $totalactivities = $DB->get_records_sql("SELECT {course_modules}.id FROM {user_enrolments} inner join {enrol} on {user_enrolments}.enrolid = {enrol}.id inner join {university_user} on {university_user}.userid = {user_enrolments}.userid inner join {user} on {user}.id = {university_user}.userid inner join {course_modules} on {course_modules}.course  = {enrol}.courseid where {university_user}.university_id = '$getuniversityid->university_id' and {university_user}.userid = '$USER->id'");
      $module_course_completed_count = $DB->count_records("course_modules_completion",  array( 'userid'=> "$USER->id", 'completionstate' => '1' ));
      $module_course_completed = ($module_course_completed_count/$coursecount)*100;
      $module_course_incompleted = 100-$module_course_completed;
      
      $present = $DB->count_records("autoattend_students", ['status'=>'P']);
      $absent = $DB->count_records("autoattend_students", ['status'=>'X']);
      //var_dump($module_course_completed);
      //die;
      
      ?>

      <div class="row">
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
          <div class="inner">
            <h3 style = "color: white;"><?php echo $USER->username; ?></h3>

            <p style ="font-weight:900;">Trainer Name</p>
          </div>
          <div class="faicon" style = "text-align: right; font-size: 45px; margin-top: -50px; padding: 6px 5px 0 0;">
          <i class="fa fa-user"></i>
          </div>
          <!--<hr>
          <a href="#" class="small-box-footer" style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a> -->
        </div> 
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3 style = "color: white;"><?php echo count($studentsontechercourse); ?></h3>

            <p style ="font-weight:900;">Learners Assigned</p>
          </div>
          <div class="faicon" style = "text-align: right; font-size: 45px; margin-top: -50px; padding: 6px 5px 0 0;">
              <i class='fas fa-user'></i>
          </div>
          <!--<hr>
          <a href="#" class="small-box-footer"  style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3 style = "color: white;"><?php echo count($groups); ?></h3>

            <p style ="font-weight:900;">Total Groups</p>
          </div>
          <div class="faicon" style = "text-align: right; font-size: 45px; margin-top: -50px; padding: 6px 5px 0 0;">
              <i class='fas fa-user'></i>
          </div>
          <!--<hr>
          <a href="#" class="small-box-footer"  style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3 style = "color: white;"><?php echo count($totalactivities); ?></h3>

            <p style ="font-weight:900;">Total Activities</p>
          </div>
          <div class="faicon" style = "text-align: right; font-size: 45px; margin-top: -50px; padding: 6px 5px 0 0;">
              <i class='fas fa-user'></i>
          </div>
          <!--<hr>
          <a href="#" class="small-box-footer"  style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
      </div>
      <!--./col-->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3 style = "color: white;"><?php echo $coursecount; ?></h3>

            <p style ="font-weight:900;">Enrolled Units</p>
          </div>
          <div class="faicon" style = "text-align: right; font-size: 45px; margin-top: -50px; padding: 6px 5px 0 0;">
              <i class='fas fa-chalkboard-teacher'></i>
          </div>
          <!--<hr>
          <a href="#" class="small-box-footer"  style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
          <div class="inner">
            <h3 style = "color: white;"><?php echo "34" .$uni_user_count; ?></h3>

            <p style ="font-weight:900;">Attendance</p>
          </div>
          <div class="faicon" style = "text-align: right; font-size: 45px; margin-top: -50px; padding: 6px 5px 0 0;">
          <i class='fas fa-award'></i>
          </div>
          <!--<hr>
          <a href="#" class="small-box-footer" style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
      </div>
    </div>
    <br><br>
    <div class="row">
     <div class="col-lg-12 col-12">
      <div class='container'> 
        <!-- <div id="piechart" ></div>   style="width: 100%; height: 800px;" > -->
        <div id="piechart" class="pie-chart" style="width: 100%; height: 320px; "></div>
      </div>
    </div>
    <div class="col-lg-12 col-12">
      <div class='container'> 
        <!-- <div id="piechart" ></div>   style="width: 100%; height: 800px;" > -->
        <div id="piechart2" class="pie-chart" style="width: 100%; height: 320px; "></div>
      </div>
    </div>
    </div>
    <br><br>
    <div class="row">
        <h3>Total Inactive Users</h3><br><br>
        <div class="col-lg-2 col-4">
        <!-- small box -->
        <div class="small-box bg-warning">
          <div class="inner">
            <h3 style = "color: white;"><?php echo count($last24week) ?></h3>

            <p style ="font-weight:900;">2-4 Week</p>
          </div>
          <!--<hr>
          <a href="#" class="small-box-footer" style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
      </div>
      <div class="col-lg-2 col-4">
        <!-- small box -->
        <div class="small-box bg-warning">
          <div class="inner">
            <h3 style = "color: white;"><?php echo count($last48week) ?></h3>

            <p style ="font-weight:900;">4-8 Week</p>
          </div>
          <!--<hr>
          <a href="#" class="small-box-footer" style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
      </div>
      <div class="col-lg-2 col-4">
        <!-- small box -->
        <div class="small-box bg-warning">
          <div class="inner">
            <h3 style = "color: white;"><?php echo count($more8week) ?></h3>

            <p style ="font-weight:900;">8 or more</p>
          </div>
          <!--<hr>
          <a href="#" class="small-box-footer" style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
      </div>
        </div>
    
    
 
    
    
    
      <?php
    }
    else if($role_shortname->shortname === "rtoadmin")
    { 
         echo "<h3> Hai " . $USER->firstname . ",</h3><br>";
       /* echo $USER->id;
        echo $id;  */
        $rto_count = $DB->count_records("school", ['id'=>$USER->id]);
        $university_id = $_SESSION['university_id'];
        /*echo '<br>' . $university_id; */
        $uni_user_count = $DB->count_records("university_user", ['university_id'=>$university_id]);
        
        $present = $DB->count_records("autoattend_students", ['status'=>'P']);
        //echo $present;
        $absent = $DB->count_records("autoattend_students", ['status'=>'X']);
        //echo $absent;
        $coursemodulecount = $DB->count_records("course_modules", ['tenent_id'=>$university_id]);
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
        $uni_user = $DB->get_record("university_user", ['university_id'=>$university_id]);
        $module_course_completed_count = $DB->count_records("course_modules_completion",  array( 'userid'=> "$uni_user->userid", 'completionstate' => '1' ));
        $module_course_completed = ($module_course_completed_count/$coursecount)*100;
        $module_course_incompleted = (100-$module_course_completed)*-1;
        
        //var_dump($module_course_completed_count);
        //die;


        ?>

      <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 style = "color: white;"><?php echo $USER->username; ?></h3>

                <p style ="font-weight:900;">RTO Admin Name</p>
              </div>
              <div class="faicon" style = "text-align: right; font-size: 32px; margin-top: -65px; padding: 20px 5px 0 0;">
              <i class="fa fa-user"></i>
              </div>
              <hr>
              <a href="<?php echo $CFG->wwwroot .'/user/profile.php';?>" class="small-box-footer" style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 style = "color: white;"><?php echo $coursecount; ?></h3>

                <p style ="font-weight:900;">Enrolled Units</p>
              </div>
              <div class="faicon" style = "text-align: right; font-size: 32px; margin-top: -65px;  padding: 20px 5px 0 0;">
                  <i class='fas fa-chalkboard-teacher'></i>
              </div>
              <hr>
              <a href="<?php echo $CFG->wwwroot .'/my/courses.php';?>" class="small-box-footer"  style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 style = "color: white;"><?php echo $uni_user_count; ?></h3>

                <p style ="font-weight:900;">RTO User Count</p>
              </div>
              <div class="faicon" style = "text-align: right; font-size: 32px; margin-top: -65px; padding: 20px 5px 0 0;">
              <i class='fas fa-award'></i>
              </div>
              <hr>
              <a href="<?php echo $CFG->wwwroot .'/local/createuser/user_list.php';?>" class="small-box-footer" style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 style = "color: white;"><?php echo "7"; ?></h3>

                <p style ="font-weight:900;"> Current Package</p>
              </div>
              <div class="faicon" style = "text-align: right; font-size: 32px; margin-top: -65px; padding: 20px 5px 0 0;">
                  <i class='fas fa-laptop-code'></i>
              </div>
              <hr>
              <a href="<?php echo $CFG->wwwroot .'/local/mypackage/my_package.php';?>" class="small-box-footer" style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
    <br><br>
    <!-- 2nd Row-->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 style = "color: white;"><?php echo count($groups); ?></h3>

                <p style ="font-weight:900;">Total Groups</p>
              </div>
              <div class="faicon" style = "text-align: right; font-size: 32px; margin-top: -65px; padding: 20px 5px 0 0;">
              <i class="fa fa-user"></i>
              </div>
              <!--<hr>
              <a href="<?php echo $CFG->wwwroot .'/user/profile.php';?>" class="small-box-footer" style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>-->
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 style = "color: white;"><?php echo count($totalactivities); ?></h3>

                <p style ="font-weight:900;">Total Activities</p>
              </div>
              <div class="faicon" style = "text-align: right; font-size: 32px; margin-top: -65px; padding: 20px 5px 0 0;">
              <i class="fa fa-user"></i>
              </div>
              <!--<hr>
              <a href="<?php echo $CFG->wwwroot .'/user/profile.php';?>" class="small-box-footer" style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>-->
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 style = "color: white;"><?php echo count($totalstudents); ?></h3>

                <p style ="font-weight:900;">Total Students</p>
              </div>
              <div class="faicon" style = "text-align: right; font-size: 32px; margin-top: -65px; padding: 20px 5px 0 0;">
              <i class="fa fa-user"></i>
              </div>
              <!--<hr>
              <a href="<?php echo $CFG->wwwroot .'/user/profile.php';?>" class="small-box-footer" style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>-->
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 style = "color: white;"><?php echo count($totaltrainer); ?></h3>

                <p style ="font-weight:900;">Total Trainer</p>
              </div>
              <div class="faicon" style = "text-align: right; font-size: 32px; margin-top: -65px; padding: 20px 5px 0 0;">
              <i class="fa fa-user"></i>
              </div>
              <!--<hr>
              <a href="<?php echo $CFG->wwwroot .'/user/profile.php';?>" class="small-box-footer" style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>-->
            </div>
          </div>
        </div>
        <br><br>
        <!--Row3-->
        <div class="row">
            <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 style = "color: white;"><?php echo count($totaladmin); ?></h3>

                <p style ="font-weight:900;">Total Admin</p>
              </div>
              <div class="faicon" style = "text-align: right; font-size: 32px; margin-top: -65px; padding: 20px 5px 0 0;">
              <i class="fa fa-user"></i>
              </div>
              <!--<hr>
              <a href="<?php echo $CFG->wwwroot .'/user/profile.php';?>" class="small-box-footer" style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>-->
            </div>
          </div>
            </div>
            <br><br>
            <div class="col-lg-12 col-12">
      <div class='container'> 
        <!-- <div id="piechart" ></div>   style="width: 100%; height: 800px;" > -->
        <div id="piechart2" class="pie-chart" style="width: 100%; height: 320px; "></div>
      </div>
    </div><br><br>
            <div class="row">
                <h3>Total Inactive Users</h3><br><br>
                <div class="col-lg-2 col-4">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 style = "color: white;"><?php echo count($last24week); ?></h3>
                <p style ="font-weight:900;">2-4 Weeks</p>
              </div>
            </div>
          </div>
          <div class="col-lg-2 col-4">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 style = "color: white;"><?php echo count($last48week); ?></h3>
                <p style ="font-weight:900;">4-8 Weeks</p>
              </div>
            </div>
          </div>
          <div class="col-lg-2 col-4">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 style = "color: white;"><?php echo count($more8week); ?></h3>
                <p style ="font-weight:900;">8 or More</p>
              </div>
            </div>
          </div>
                </div>
            

    
    
<?php
    }
}
else {
    
        $all_packages = $DB->get_records_sql("SELECT * FROM {package}");
        //$all_university= $DB->get_records_sql("SELECT * FROM {school}");
        $resource_course_id = $DB->get_record("course_categories",['idnumber'=>'resourcecat']);
        $all_courses = $DB->get_records_sql("SELECT * FROM {course} WHERE category !=0  AND category !=$resource_course_id->id");
        $coursecount = $DB->count_records("course",['cb_userid'=>2]);
        $user_count = $DB->count_records("user");
        $rto_count = $DB->count_records("school");
        $package_count = $DB->count_records("package");
        
        $all_university= $DB->get_records_sql("SELECT * FROM {school} ORDER BY name DESC LIMIT 10 ");
       
    ?>
    
    <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 style = "color: white;"><?php echo $coursecount; ?></h3>

                <p style ="font-weight:900;">Total Units</p>
              </div>
              <div class="faicon" style = "text-align: right; font-size: 55px; margin-top: -89px; padding: 20px 5px 0 0;">
               <i class='fas fa-book-open'></i>
              </div>
              <hr>
              <a href="<?php echo $CFG->wwwroot .'/course/management.php';?>" class="small-box-footer" style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 style = "color: white;"><?php echo $user_count; ?></h3>

                <p style ="font-weight:900;">Total Users</p>
              </div>
              <div class="faicon" style = "text-align: right; font-size: 55px; margin-top: -89px; padding: 20px 5px 0 0;">
              <i class="fa fa-user"></i>
              </div>
              <hr>
              <a href="<?php echo $CFG->wwwroot .'/admin/user.php';?>" class="small-box-footer"  style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 style = "color: white;"><?php echo $rto_count; ?></h3>

                <p style ="font-weight:900;">Total RTO</p>
              </div>
              <div class="faicon" style = "text-align: right; font-size: 55px; margin-top: -89px; padding: 20px 5px 0 0;">
              <i class="fa fa-university"></i>
              </div>
              <hr>
              <a href="<?php echo $CFG->wwwroot .'/local/dashboard/table.php';?>" class="small-box-footer" style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 style = "color: white;"><?php echo $package_count; ?></h3>

                <p style ="font-weight:900;">Package Count</p>
              </div>
              <div class="faicon" style = "text-align: right; font-size: 55px; margin-top: -89px; padding: 20px 5px 0 0;">
              <i class="fa fa-dollar"></i>
              </div>
              <hr>
              <a href="<?php echo $CFG->wwwroot .'/local/createpackage/package_list.php';?>" class="small-box-footer" style = "color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
    <br><br>
    <div class="row">

        <div class="col-lg-6 col-12">
        <div class='container'> 
          <table id="tblUser"  class="table table-striped table-bordered" style="width:100%; font-size:12px;">
            <tr>
                <th>S. No</th>
                <th>Package Value (In Rupees)</th>
                <th>Number of Users </th>
                <th>Number of units</th>
            </tr>
                  <?php 
                  $i = 1;
                  foreach($all_packages as $package){?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $package->package_value; ?></td>
                <td><?php echo $package->num_of_user; ?></td>
                <td><?php echo $package->num_of_course; ?></td>
            </tr>
                  <?php $i++; } ?>
          </table>
        </div>
        </div>
        
        
    
    <div class="col-lg-6 col-12">
        <!-- <div style="width:50%;hieght:20%;text-align:center"> -->
        <h2 class="page-header" style= "height: auto; width:100%;">Analytics Reports </h2>
        <div> </div>
        <canvas  id="chartjs_bar"></canvas> 
        <!-- </div>  Product -->
       
       <!--<canvas  id="chartjs_bar"></canvas> -->
    </div>
    </div>
    
        
    <?php 
    $db_host='localhost';
    $db_user='elearngroup_vetmoodle';
    $db_pass='=m2$jfM%mGrz';
    $db_name='elearngroup_moodle';
    
    $con  = mysqli_connect("$db_host","$db_user","$db_pass","$db_name");
     if (!$con) {
         # code...
        echo "Problem in database connection! Contact administrator!" . mysqli_error();
     }else{
             $sql ="SELECT * FROM mdl_package";
             $result = mysqli_query($con,$sql);
            
             
             $chart_data="";
             while ($row = mysqli_fetch_array($result)) { 
     
                $productname[]  = $row['package_value']  ;
                $sales[] = $row['num_of_user'];
            }
     
     } ?>
    <script src="//code.jquery.com/jquery-1.9.1.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script type="text/javascript">
      var ctx = document.getElementById("chartjs_bar").getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels:<?php echo json_encode($productname); ?>,
                        datasets: [{
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
                            data:<?php echo json_encode($sales); ?>,
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

$html='';

     $html .= '<body> <div class="container-fluid">
         <div class="row mt-4">';
         $resourse_category_id = $DB->get_record("course_categories",['idnumber'=>'resourcecat']);
         if(is_siteadmin()){
            $data=$DB->get_records_sql("SELECT mc.* FROM {course} mc where mc.visible=1 AND id != 1 AND mc.category != $resourse_category_id->id");
         }
         else{
            $id = $USER->id;
            $check = $DB->get_records_sql("SELECT * from {universityadmin} where userid = '$id'");
            $check1 = count($check);
            if($check1 == 1){
               // $data=$DB->get_records_sql("SELECT {course}.* from {course} left join {enrol} on {course}.id = {enrol}.courseid left join {user_enrolments} on {enrol}.id = {user_enrolments}.enrolid  where {user_enrolments}.userid=$id and {enrol}.enrol ='manual'");
               $data1 =$DB->get_records_sql("SELECT mc.* FROM {course} mc inner join {assign_course} assc on mc.id = assc.course_id inner join {school} ms on ms.id = assc.university_id inner join {universityadmin} ua on ua.university_id = ms.id WHERE ua.university_id= $_SESSION[university_id]  AND mc.category != $resourse_category_id->id");
            }
            else{
               
               $data = $DB->get_records_sql("SELECT {course}.* from {course} left join {enrol} on {course}.id = {enrol}.courseid left join {user_enrolments} on {enrol}.id = {user_enrolments}.enrolid  where {user_enrolments}.userid=$id and {enrol}.enrol ='manual' AND {course}.category != $resourse_category_id->id");
            }
         }

            
         $course_exsit=array();   
            foreach($data as $datavalue)
            {
               $courseid = $datavalue->id;
               $course_exsit[]= $datavalue->id;
			      $url = get_course_image($courseid);

            // var_dump($url);
               if(!$url){ $url ='image/courses.jpg'; }
                $html.='<div class="col-md-4">
                <div class="card2">
                   <div class="img">
                     <img class="card2-img-top" src="'.$url.'" alt="card2 image">
                      <div class="overlay">
                         <p class=""> <a class="btn btn-border-5 text-white" href="'.$CFG->wwwroot.'/course/view.php?id='.$datavalue->id.'" style="margin:70px;">View Unit</a></p>
                      </div>
                   </div>
                  
                   <div class="card2-body">
                      <h4 class="card2-title">'.$datavalue->fullname.'</h4>
                   </div>
                </div>
             </div>';
            }
            if (isset($data1) )
            {
               foreach($data1 as $datavalue)
            {
               $courseid = $datavalue->id;
			   if(in_array($courseid,$course_exsit)) continue;
               $url = get_course_image($courseid);
               // var_dump($url);
               if(!$url){ $url ='image/courses.jpg'; }
                $html.='<div class="col-md-4">
                <div class="card2">
                   <div class="img">
                     <img class="card2-img-top" src="'.$url.'" alt="card2 image">
                      <div class="overlay">
                         <p class=""> <a class="btn btn-border-5" href="'.$CFG->wwwroot.'/course/view.php?id='.$datavalue->id.'" style="margin:70px;">View Unit</a></p>
                      </div>
                   </div>
                  
                   <div class="card2-body">
                      <h4 class="card2-title">'.$datavalue->fullname.'</h4>
                   </div>
                </div>
             </div>';
            }
            }
            

         $html.='</div>
      </div>

      
      
   </body>
</html>';
echo $html;


       if($role_shortname->shortname === "rtoadmin")
    { 
        ?>        
        <div class="row">
     <div class="col-lg-12 col-12">
      <div class='container'> 
        <!-- <div id="piechart" ></div>   style="width: 100%; height: 800px;" > -->
        <div id="piechart" class="pie-chart" style="width: 100%; height: 320px; "></div>
      </div>
    </div>
    </div>
    
    
    
    <?php
    }


// Create a course_in_list object to use the get_course_overviewfiles() method.
// var_dump($CFG->libdir . '/coursecatlib.php');


echo $OUTPUT->footer();
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
.faicon
{
text-align: end;
font-size: 71px;
margin-top: -54px;
}

.inner_page_breadcrumb:before {
background-image: none !important
}


</style>


<!-- student chart -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!--Load the AJAX API-->

<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Order', 'Amount'],
                ['Completed Units ', parseInt('<?php echo $complete_course; ?>')],
                ['Pending Units',       parseInt('<?php echo $overdue_courses;  ?>')]
                
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
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Order', 'Amount'],
                ['Competency ', parseInt('<?php echo $module_course_completed; ?>')],
                ['In-Competency',       parseInt('<?php echo $module_course_incompleted;  ?>')]
                
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
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Order', 'Amount'],
                ['Present ', parseInt('<?php echo $present; ?>')],
                ['Absent ', parseInt('<?php echo $absent;  ?>')]
                
        ]); 
        var options = {
            title: 'ATTENDANCE'
        };
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }
</script>





























