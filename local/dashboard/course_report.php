<?php
    require_once('../../config.php');
    require_once($CFG->dirroot .'/completion/classes/progress.php');

    $title = 'Course Report';
    $pagetitle = $title;
    $PAGE->set_title($title);
    $PAGE->set_heading($title);
    $PAGE->set_pagelayout('standard');
    
    $uni_id = $_GET['uni_id'];
    $courses = $DB->get_records("assign_course", ['university_id'=>$uni_id]);
    $university = $DB->get_record("school", ['id'=>$uni_id]);

    $all_university_users = $DB->get_records_sql("SELECT uu.* FROM {university_user} uu WHERE uu.university_id=$uni_id UNION SELECT ua.* FROM  {universityadmin} ua  WHERE ua.university_id=$uni_id");
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
    <div class="border-end border-start border-dark">
    <table class="table m-0" id="data">
    <thead class="">
    <tr style="height: 20px;">
        <th scope="col" colspan="6" class="p-0"><h4 class="text-center text-uppercase bg-dark text-white m-0 py-2">RTO Unit Allocation Summary</h4></th>
    </tr>
    <tr style="height: 50px;" class="university_name_logo">
        <td scope="col" class="text-center h5 pt-3 text-uppercase" colspan="3">'.$university->name.'</td>
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
      <tr class="bg-secondary text-white">
        <th class="py-2 h6"scope="col">No</th>
        <th class="py-2 h6"scope="col">Unit Code</th>
        <th class="py-2 h6"scope="col">Unit name</th>
        <th class="py-2 h6"scope="col">Total User assigned</th>
        <th class="py-2 h6"scope="col">Total user failed</th>
        <th class="py-2 h6"scope="col">Total User pending</th>
      </tr>
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

        #Count enrolled users for particular university 
        $count_enrolled =0;
        $count_failed=0;
        $count_pending =0;
        foreach ($all_enrolled_users as $all_enrolled_user) 
        {
          if (in_array($all_enrolled_user->id, $all_uni_userid)) 
          {
            $course_obj = $DB->get_record("course", ['id'=>$course->course_id]);
            $report = core_completion\progress::get_course_progress_percentage($course_obj, $all_enrolled_user->id);
            if ($report) 
            {
              $count_failed++;
            }
            else
            {
              $count_pending++;
            }
            $count_enrolled++;
          }
        }
        #End
        // var_dump($course_info);
        $table.=
        '<tr>
          <th scope="row">'.$count.'</th>
          <td>'.$course_info->idnumber.'</td>
          <td>'.$course_info->fullname.'</td>
          <td>'.$count_enrolled.'</td>
          <td>'.$count_failed.'</td>
          <td>'.$count_pending.'</td>
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