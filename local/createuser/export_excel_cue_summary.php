<?php

require_once('../../config.php');
require_once('lib.php');
require_login();

global $USER, $DB;
$stu_id = $_GET['stu_id'];
$user_info = $DB->get_record("user", ["id"=>$stu_id]);

$all_enrolled_courses = enrol_get_all_users_courses($stu_id);
// var_dump($all_enrolled_courses);
// die;
$title = 'CUE Summary';
$pagetitle = $title;
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('standard');

$uni_id =$_SESSION['university_id'];

?>
<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <title>CUE Summary </title>
   <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">     -->
   <style>
      .theme-table-wrap
      {
         margin-top: 0px !important;
      }
      .borderless td 
      {
         border: none;
         padding: 4px 0px 4px 4px !important;
      }
      /* .td-font td
      {
         font-weight: 600 !important;
      } */
   </style>  
</head>
<!-- D:\xampp\htdocs\MoodleLMS6\local\createuser\export_excel_cue_summary.php -->
<body>
   <div class="border border-primary">
      <table class="table mb-0 table-bordered">
         <thead>
            <tr>
               <th scope="col" colspan="10" class="bg-dark py-2"><h4 class="text-center text-white mb-0">Client Unit Enrolment Summary</h4></th>
            </tr>
         </thead>
         <thead>
            <tr class="borderless">
               <td class="remove_border" scope="col" colspan="" style="font-weight: 600;">Client Name:</td>
               <td class="remove_border" colspan="7" style=""><?php echo "$user_info->firstname $user_info->lastname"; ?></td>
               <td class="remove_border" scope="col" colspan="1" style="font-weight: 600;">Student ID:</td>
               <td class="remove_border" scope="col">MIT01004U0</td>
            </tr>
         </thead>
         <thead>
            <tr style="background-color: #f4f4f4!important;" class="td-font">
               <th class="py-2 px-1 h6" scope="col">Unit Code</th>
               <th class="py-2 px-1 h6" scope="col">Unit Description</th>
               <th class="py-2 px-1 h6" scope="col">Start Date</th>
               <th class="py-2 px-1 h6" scope="col">End Date</th>
               <th class="py-2 px-1 h6" scope="col">Trainer</th>
               <th class="py-2 px-1 h6" scope="col">Final</th>
               <th class="py-2 px-1 h6" scope="col">Theory</th>
               <th class="py-2 px-1 h6" scope="col">Practical</th>
               <th class="py-2 px-1 h6" scope="col">Result Code</th>
               <th class="py-2 px-1 h6" scope="col">Comments</th>
            </tr>
         </thead>
         <tbody>
            <?php 
               foreach ($all_enrolled_courses as $enrolled_course) 
               {
                  if (strpos($enrolled_course->fullname, "General_Block") === false) 
                  {
                     $start_date = date('d/m/Y',$enrolled_course->startdate);
                     $end_date = date('d/m/Y',$enrolled_course->enddate);

                     $context = context_course::instance($enrolled_course->id);
                     $all_enrolled_users = get_enrolled_users($context);
                     // var_dump($all_enrolled_users);
                     // echo"<br>";
                     
                     $table_row ="<tr>
                        <td class='px-1'>$enrolled_course->idnumber</td>
                        <td class='px-1'>$enrolled_course->fullname</td>
                        <td class='px-1'>$start_date</td>
                        <td class='px-1'>$end_date</td>
                        <td class='px-1'>";
                        $teacher_count = 1;
                           foreach ($all_enrolled_users as $all_enrolled_user) 
                           {
                              $role = $DB->get_record("role_assignments", ["userid"=>$all_enrolled_user->id]);
                              $role_name = $DB->get_record("role", ["id"=>$role->roleid]);
                              $get_teacher_university_id = $DB->get_record_sql("SELECT uu.* FROM {university_user} uu WHERE uu.university_id=$uni_id AND uu.userid=$all_enrolled_user->id UNION SELECT ua.* FROM  {universityadmin} ua  WHERE ua.university_id=$uni_id AND ua.userid=$all_enrolled_user->id");
                              // var_dump($get_teacher_university_id );
                              // echo "<br>";
                              if ($role_name->shortname === "teacher" && $uni_id == $get_teacher_university_id->university_id) 
                              {
                                 $table_row .="<span style='display: block;'>$teacher_count. $all_enrolled_user->firstname $all_enrolled_user->lastname</span>";
                                 $teacher_count++;
                              }
                           }
                        $table_row .="
                        </td>
                        <td class='px-1'></td>
                        <td class='px-1'></td>
                        <td class='px-1'></td>
                        <td class='px-1'></td>
                        <td class='px-1'>No Comment</td>
                     </tr>";
                     echo $table_row;
                  }
               }
            ?>
         </tbody>
      </table>
   </div>
</body>
</html>

<?php 
    header('Content-Type: application/force-download');
    header('Content-Disposition: attachment; filename=CUE summary.xlsx');
    header("Content-Transfer-Encoding: BINARY");
?>