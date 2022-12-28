<?php

require_once('../../config.php');
require_once('lib.php');
require_login();

global $USER, $DB;

$title = 'CUE Student List';
$pagetitle = $title;
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('standard');

$uni_id = $_SESSION['university_id'];
$all_university_users = $DB->get_records_sql("SELECT uu.* FROM {university_user} uu WHERE uu.university_id=$uni_id UNION SELECT ua.* FROM  {universityadmin} ua  WHERE ua.university_id=$uni_id");
// foreach ($all_university_users as $all_university_user) 
// {
//     // $all_uni_userid[] = $all_university_user->userid;
//     $role = $DB->get_record("role_assignments", ["userid"=>$all_university_user->userid]);
//     $role_name = $DB->get_record("role", ["id"=>$role->roleid]);
//     if ($role_name->shortname === "student") {
//         var_dump($role_name->shortname);
//     }   
// }
?>
<?php echo $OUTPUT->header(); ?>
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

   </style>  
</head>

<body>
   <div class="">
      <div>
         <h4 class="text-center bg-dark text-white text-uppercase mb-0 py-2">All Student list</h4>
         <div id="search_input" class="my-2">
            <input id='student_name' type='text' placeholder="Search Student" 
               style="min-height: 35px !important;
               min-width: 30% !important;
               border-radius: 16px;
               padding: 4px 14px 0px !important;" 
               onkeyup='searchStudent()'>
         </div>
      </div>
      <table class="table mb-0" id="student_table">
      <!-- <table class="table mb-0 table-responsive" id="student_table"> -->
         <!-- <thead>
            <tr>
               <th scope="col" colspan="6" class="bg-dark py-2"><h4 class="text-center text-white text-uppercase mb-0">All Student list</h4></th>
            </tr>
            <tr>
               <th colspan="6" class="px-0 py-2 font-weight-normal">
                  <div id="search_input">
                     <input id='student_name' type='text' placeholder="Search Student" 
                        style="min-height: 35px !important;
                        min-width: 30% !important;
                        border-radius: 16px;
                        padding: 4px 14px 0px !important;" 
                        onkeyup='searchStudent()'>
                  </div>
               </th>
            </tr>
         </thead> -->
         <tbody>
            
            <?php
                $row = "<tr></tr>";
                $count_td =1;
                foreach ($all_university_users as $all_university_user) 
                {
                    $user_info = $DB->get_record("user", ["id"=>$all_university_user->userid]);
                    $role = $DB->get_record("role_assignments", ["userid"=>$all_university_user->userid]);
                    $role_name = $DB->get_record("role", ["id"=>$role->roleid]);
                    if ($role_name->shortname === "student") 
                    {
                        if ($count_td <= 6 ) 
                        {
                            $row .= "<td class='pl-0'>
                            <a href='$CFG->wwwroot/local/createuser/cue_summary.php?stu_id=$all_university_user->userid' 
                            style='display: inline-block;
                            background-color: cadetblue;
                            padding: 4px 12px;
                            text-transform: capitalize;
                            color: white;'>$user_info->firstname $user_info->lastname</a>
                            </td>";
                            $count_td++;
                        }
                        else
                        {
                            $row .= "<tr><td class='pl-0'>
                            <a href='$CFG->wwwroot/local/createuser/cue_summary.php?stu_id=$all_university_user->userid' 
                            style='display: inline-block;
                            background-color: cadetblue;
                            padding: 4px 12px;
                            color: white;'>$user_info->firstname $user_info->lastname</a>
                            </td></tr>";
                            $count_td=1;
                        }
                    }
                }
                echo "$row";
            ?>
         </tbody>
      </table>
   </div>
</body>
</html>
<script>
   function searchStudent() 
   {
      
      var input, filter, found, table, tr, td, i, j;
      input = document.getElementById("student_name");
      filter = input.value.toUpperCase();
      table = document.getElementById("student_table");
      tr = table.getElementsByTagName("tr");
      for (i = 0; i < tr.length; i++) 
      {
         td = tr[i].getElementsByTagName("a");
         for (j = 0; j < td.length; j++) {
            if (td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
                  found = true;
            }
         }
         if (found) {
            tr[i].style.display = "";
            found = false;
         } else {
            tr[i].style.display = "none";
         }
      }

   // for (i = 0; i < tr.length; i++) {
   //    td = tr[i].getElementsByTagName("td")[0];
   //    if (td) {
   //       txtValue = td.textContent || td.innerText;
   //       if (txtValue.toUpperCase().indexOf(filter) > -1) {
   //       tr[i].style.display = "";
   //       } else {
   //       tr[i].style.display = "none";
   //       }
   //    }       
   // }
   }
</script>
<?php echo $OUTPUT->footer(); ?>