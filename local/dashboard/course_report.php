<style>
 *body {
  font-family: "Nunito",sans-serif !important;
 }
</style>
<?php

    require_once('../../config.php');
    require_once($CFG->dirroot .'/completion/classes/progress.php');
    //require_login();

    $title = 'Course Report';
    $pagetitle = $title;
    $PAGE->set_title($title);
    $PAGE->set_heading($title);
    $PAGE->set_pagelayout('standard');
    
   $uni_id = $_GET['uni_id'];
   //echo $uni_id;
    //$uni_id = $_GET['118'];
    $courses = $DB->get_records("assign_course", ['university_id'=>$uni_id]);
    
    $university = $DB->get_record("school", ['id'=>$uni_id]);

   $all_university_users = $DB->get_records_sql("SELECT uu.* FROM {university_user} uu 
   WHERE uu.university_id=$uni_id UNION SELECT ua.* FROM  {universityadmin} ua  WHERE ua.university_id=$uni_id");
   //$all_university_users = $DB->get_records_sql("SELECT uu.* FROM {university_user} uu WHERE uu.university_id=$uni_id UNION SELECT ua.* FROM  {universityadmin} ua  WHERE ua.university_id=118");
    foreach ($all_university_users as $all_university_user) 
    {
      $all_uni_userid[] = $all_university_user->userid;
    }
    if (!is_siteadmin()) 
    {
      $margin = "-40%";
    }
    else
    {
      $margin = "-40%";
    }
    $table='
    <!-- CSS only -->
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js" integrity="sha512-r22gChDnGvBylk90+2e/ycr3RVrDi8DIOkIGNhJlKfuyQM4tIRAI062MaV8sfjQKYVGjOBaZBOA87z+IhZE9DA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" >
    <style>
    *body {
      font-family: "Nunito",sans-serif !important;
     }
    .kcb-sample {
      margin:40px 100px;
  }
  a:hover, a:focus {
    color: #2a6496;
    text-decoration: auto !important;
}

.form-group{
  font-family: "Nunito",sans-serif;
  }
    </style>
    
    <div class="border-end border-start border-dark" style="font-family: Nunito,sans-serif;">
    <table class="table m-0" id="data" style="font-family: Nunito,sans-serif;">
    <thead class="" >
    <tr style="height: 20px;">
        <th scope="col" colspan="8" class="p-0"><h4 class="text-center text-uppercase text-white m-0 py-2" style="background-color: #1f35d3!important; border: 2px solid #0a1c98;">RTO Unit Allocation Summary</h4></th>
    </tr>
    <tr style="height: 50px;" class="university_name_logo">
        <td scope="col" class="text-center h5 pt-3 text-uppercase" colspan="4">'.$university->name.'</td>
        <td scope="col"></td>
        <td scope="col" colspan="1" class="text-center mb-1"><img class="text-center" src="'.$CFG->wwwroot.$university->logo_path.'" alt="" 
        style="max-width: 100%;
        min-width: 50px;
        max-height: 30px;">
        </td>
        <td scope="col" colspan="1" class="text-center mb-1"><a href="'.$CFG->wwwroot.'/local/dashboard/export_to_excel.php?uni_id='.$uni_id.'" 
        style="background: #a4edc5;
            border: 1px solid green;
            padding: 6px 16px;
            font-weight: 500;
            color: cornflowerblue;
            text-decoration: none;
            margin-right: '.$margin.';">Export to XLS</a></td>
      </tr>
      <div class="kcb-sample">
      <tr class="bg-secondary text-white" >
        <th class="py-2 h6"scope="col" style="font-size: 15px!important; color: #0a0a0a !important;">No</th>
        <th class="py-2 h6"scope="col" style="font-size: 15px!important; color: #0a0a0a !important;">Unit Code</th>
        <th class="py-2 h6"scope="col" style="font-size: 15px!important; color: #0a0a0a !important;">Unit name</th>
        <th class="py-2 h6"scope="col" style="font-size: 15px!important;"> <a id="linkA" href="#" data-toggle="tooltip" title="Total users assigned - Pass" style="padding: 8px;">Total User assigned</a></th>
        <th class="py-2 h6"scope="col" style="font-size: 15px!important;"> <a id="linkA" href="#" data-toggle="tooltip" title="Not yet completed - Fail" style="padding: 8px;">Not yet completed</a></th>
        <th class="py-2 h6"scope="col" style="font-size: 15px!important;"> <a id="linkA" href="#" data-toggle="tooltip" title="Total user pending - Pending" style="padding: 8px;">Total User pending</a></th>
        <th class="py-2 h6"scope="col" style="font-size: 15px!important;"> <a id="linkA" href="#" data-toggle="tooltip" title="Total user pending - Pending" style="padding: 8px;">Total User Passed</a></th>
      </tr>
      </div>
    </thead>
    <tbody>';
    $count=1;
    foreach ($courses as $course) {
      $check_course = $DB->record_exists("course", ['id'=>$course->course_id]);
      if ($check_course) 
      {  
        $course_info = $DB->get_record("course", ['id'=>$course->course_id]);
        $context = context_course::instance($course->course_id);
        $all_enrolled_users = get_enrolled_users($context);
        $course_modules = $DB->get_record("course_modules", ['course'=>$course->course_id]);

        #Count enrolled users for particular university 
        $count_enrolled =0;
        $count_failed=0;
        $count_pending =0;
        $count_passed=0;
        foreach ($all_enrolled_users as $all_enrolled_user) 
        {
          if (in_array($all_enrolled_user->id, $all_uni_userid)) 
          {
            $course_obj = $DB->get_record("course", ['id'=>$course->course_id]);
            $report = core_completion\progress::get_course_progress_percentage($course_obj, $all_enrolled_user->id);
            if ($report > 0.00 &&  $report<100.00) 
            {
              $count_failed++;
            }
            else if($report == 100.00 && $report!= NULL){
                $count_passed++;
            }
            else
            {
              $count_pending++;
            }
            $count_enrolled++;
            
            
          }
        }
        
        #End
        //var_dump($course_info);
        //die;
        $table.=
        '<tr>
          <th scope="row">'.$count.'</th>
          <td>'.$course_info->idnumber.'</td>
          <td>'.$course_info->fullname.'</td>
          <td><a href="./assignedusers.php?uni_id='.$uni_id.'&course_id='.$course_info->id.'" target="_blank">'.$count_enrolled.'</a></td>
          <td><a href="./notcompletedyet.php?uni_id='.$uni_id.'&course_id='.$course_info->id.'" target="_blank">'.$count_failed.'</a></td>
          <td><a href="./totaluserpending.php?uni_id='.$uni_id.'&course_id='.$course_info->id.'" target="_blank">'.$count_pending.'</a></td>
          <td><a href="./totaluserpassed.php?uni_id='.$uni_id.'&course_id='.$course_info->id.'" target="_blank">'.$count_passed.'</a></td>
        </tr>';
        $count++;
      }
    }
      
$table.=
' </tbody>
</table>
</div>';
// $table.='
// <button class="exportToExcel" onclick="exportData()" style="position: fixed; right: 2%; top: 7%; background: #a4edc5; border: 1px solid green; padding: 4px 8px;
// font-weight: 500; color: cornflowerblue;">Export to XLS</button>
// <script>
//     function exportData() {
//         let table_data = document.getElementById("data");
//         var fp = XLSX.utils.table_to_book(table_data, {sheet: "summary"});
//         XLSX.write(fp,{
//             bookType: "xlsx",
//             type: "base64"
//         });
//         XLSX.writeFile(fp,"rto_summary.xlsx");
//     }
// </script>';
echo $OUTPUT->header();
echo $table;

// header('Content-Type: application/force-download');
// header('Content-Disposition: attachment; filename=RTO unit allocation summary.xlsx');
// header("Content-Transfer-Encoding: BINARY");
echo $OUTPUT->footer();
?>
<style>
 .form-group{
   font-family: "Nunito",sans-serif;
   }
  </style>
  <script>
$('#btnL').tooltip();
$('#btnT').tooltip({animation: false});
$('#btnB').tooltip({delay: { show:500, hide:100}});
$('#btnR').tooltip({html:true});
$('#linkA').tooltip();
$('#btnClick').tooltip({trigger:'click'});
    </script>