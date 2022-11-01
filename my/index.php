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
          border-radius: 0 10px 0 10px;
          box-shadow: 0 3px 6px rgb(0 0 0 / 16%), 0 3px 6px rgb(0 0 0 / 23%);
          transition: .5s all;
          margin:0px 0px 30px 0px;
        }
        .card2:hover{
          margin-top: -10px;
        }
        .card2 img{
          border-radius: 0 10px 0 7px !important;
        }
        .card2-body{
          text-align: center;
          background: #f9f9f9;
          border-radius: 0 10px 0 10px;
          border-bottom: 5px solid #1d1d1d;
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
  border-radius: 0 10px 0 10px;
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
    color: #000 !important;
    background: #ffffff;
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
  background: #ffffff;
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
    color: #222222;
}

.btn.btn-border-5:hover::after,
.btn.btn-border-5:hover::before {
    width: 100%;
    height: 100%;
    border-color: #222222;
}
.button{

  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}
</style>
</head>';
echo $header;

echo $OUTPUT->header();
$html='';

     $html .= '<body> <div class="container-fluid">
         <div class="row mt-4">';
         if(is_siteadmin()){
            $data=$DB->get_records_sql("SELECT mc.* FROM {course} mc where mc.visible=1");
         }
         else{
            $id = $USER->id;
            $check = $DB->get_records_sql("select * from {universityadmin} where userid = '$id'");
            $check1 = count($check);
            if($check1 == 1){
               // $data=$DB->get_records_sql("SELECT {course}.* from {course} left join {enrol} on {course}.id = {enrol}.courseid left join {user_enrolments} on {enrol}.id = {user_enrolments}.enrolid  where {user_enrolments}.userid=$id and {enrol}.enrol ='manual'");
               $data1 =$DB->get_records_sql("SELECT mc.* FROM {course} mc inner join {assign_course} assc on mc.id = assc.course_id inner join {school} ms on ms.id = assc.university_id inner join {universityadmin} ua on ua.university_id = ms.id WHERE ua.university_id= $_SESSION[university_id]");
            }
            else{
               $data = $DB->get_records_sql("SELECT {course}.* from {course} left join {enrol} on {course}.id = {enrol}.courseid left join {user_enrolments} on {enrol}.id = {user_enrolments}.enrolid  where {user_enrolments}.userid=$id and {enrol}.enrol ='manual'");
            }
         }

            
         $course_exsit=array();   
            foreach($data as $datavalue)
            {
               $courseid = $datavalue->id;
               $course_exsit[]= $datavalue->id;
			   $url = get_course_image($courseid);
               if(!$url){ $url ='image/courses.jpg'; }
                $html.='<div class="col-md-4">
                <div class="card2">
                   <div class="img">
                     <img class="card2-img-top" src="'.$url.'" alt="card2 image">
                      <div class="overlay">
                         <p class="button"> <a class="btn btn-border-5" href="'.$CFG->wwwroot.'/course/view.php?id='.$datavalue->id.'">View Course</a></p>
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
               if(!$url){ $url ='image/courses.jpg'; }
                $html.='<div class="col-md-4">
                <div class="card2">
                   <div class="img">
                     <img class="card2-img-top" src="'.$url.'" alt="card2 image">
                      <div class="overlay">
                         <p class="button"> <a class="btn btn-border-5" href="'.$CFG->wwwroot.'/course/view.php?id='.$datavalue->id.'">View Course</a></p>
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

echo $OUTPUT->footer();
?>
