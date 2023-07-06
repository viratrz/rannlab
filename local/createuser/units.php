<?php
require_once('../../config.php');
require_once('lib.php');
require_login();

global $USER, $DB, $SESSION;

$title = 'Unit List';
$pagetitle = $title;
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('standard');


function get_course_image($courseid)
{
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

?>

<?php echo $OUTPUT->header(); 

?>

<!DOCTYPE html>
<html>
<head>
   <style>
   /* Custom CSS for course cards */
   .container-fluid {
      padding-top: 20px;
   }

   .card2 {
      width: 100%;
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 20px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
   }

   .card2 .img {
      position: relative;
      width: 100%;
      height: 200px;
      overflow: hidden;
   }

   .card2 .img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
   }

   .card2 .overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: flex;
      justify-content: center;
      align-items: center;
      opacity: 0;
      transition: opacity 0.3s ease;
   }

   .card2:hover .overlay {
      opacity: 1;
   }

   .card2 .overlay a {
      padding: 10px 20px;
      border: 2px solid #fff;
      border-radius: 5px;
      color: #fff;
      font-weight: bold;
      text-decoration: none;
   }

   .card2-body {
      padding: 20px;
      text-align: center;
   }

   .card2-title {
      font-size: 20px;
      font-weight: bold;
      margin-bottom: 10px;
   }
   </style>
</head>
<body>
   <div class="course-cards">
      <?php
      $html = '';

      $html .= '<div class="container-fluid">
         <div class="row mt-4">';
      $resourse_category_id = $DB->get_record("course_categories", ['idnumber' => 'resourcecat']);
      if (is_siteadmin()) {
         $data = $DB->get_records_sql("SELECT mc.* FROM {course} mc where mc.visible=1 AND id != 1 AND mc.category != $resourse_category_id->id");
      } else {
         $id = $USER->id;
         $check = $DB->get_records_sql("SELECT * from {universityadmin} where userid = '$id'");
         $check1 = count($check);
         if ($check1 == 1) {
             $universityid = $SESSION->university_id;;
            $data1 = $DB->get_records_sql("SELECT mc.* FROM {course} mc inner join {assign_course} assc on mc.id = assc.course_id inner join {school} ms on ms.id = assc.university_id inner join {universityadmin} ua on ua.university_id = ms.id WHERE ua.university_id= $universityid");
         } else {
            $data = $DB->get_records_sql("SELECT {course}.* from {course} left join {enrol} on {course}.id = {enrol}.courseid left join {user_enrolments} on {enrol}.id = {user_enrolments}.enrolid  where {user_enrolments}.userid=$id and {enrol}.enrol ='manual' AND {course}.category != $resourse_category_id->id");
         }
      }

      $course_exsit = array();
      foreach ($data as $datavalue) {
         $courseid = $datavalue->id;
         $course_exsit[] = $datavalue->id;
         $url = get_course_image($courseid);

         if (!$url) {
            $url = 'image/courses.jpg';
         }
         $html .= '<div class="col-md-4">
                <div class="card2">
                   <div class="img">
                     <img class="card2-img-top" src="' . $url . '" alt="card2 image">
                      <div class="overlay">
                         <p class=""> <a class="btn btn-border-5 text-white" href="' . $CFG->wwwroot . '/course/view.php?id=' . $datavalue->id . '" style="margin:70px;">View Course</a></p>
                      </div>
                   </div>
                  
                   <div class="card2-body">
                      <h4 class="card2-title">' . $datavalue->fullname . '</h4>
                   </div>
                </div>
             </div>';
      }
      if (isset($data1)) {
         foreach ($data1 as $datavalue) {
            $courseid = $datavalue->id;
            if (in_array($courseid, $course_exsit)) continue;
            $url = get_course_image($courseid);

            if (!$url) {
               $url = 'image/courses.jpg';
            }
            $html .= '<div class="col-md-4">
                <div class="card2">
                   <div class="img">
                     <img class="card2-img-top" src="' . $url . '" alt="card2 image">
                      <div class="overlay">
                         <p class=""> <a class="btn btn-border-5" href="' . $CFG->wwwroot . '/course/view.php?id=' . $datavalue->id . '" style="margin:70px;">View Course</a></p>
                      </div>
                   </div>
                  
                   <div class="card2-body">
                      <h4 class="card2-title">' . $datavalue->fullname . '</h4>
                   </div>
                </div>
             </div>';
         }
      }

      $html .= '</div>
      </div>';

      echo $html;
      ?>
   </div>
</body>
</html>

<!--<?php echo $OUTPUT->footer(); ?>-->

