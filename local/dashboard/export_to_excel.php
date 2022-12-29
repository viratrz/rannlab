<?php
    require_once('../../config.php');
    require_once($CFG->dirroot .'/completion/classes/progress.php');
    $uni_id = $_GET['uni_id'];
    $courses = $DB->get_records("assign_course", ['university_id'=>$uni_id]);
    $university = $DB->get_record("school", ['id'=>$uni_id]);
    
    $all_university_users = $DB->get_records_sql("SELECT uu.* FROM {university_user} uu WHERE uu.university_id=$uni_id UNION SELECT ua.* FROM  {universityadmin} ua  WHERE ua.university_id=$uni_id");
    foreach ($all_university_users as $all_university_user) 
    {
      $all_uni_userid[] = $all_university_user->userid;
    }
    $table='
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <div class="mx-2 border-end border-start border-3">
    <table class="table m-0" id="data">
    <thead class="thead-dark">
    <tr style="height: 20px; margin-bottom: 25px;">
        <th scope="col" colspan="6" class="p-0"><h3 class="text-center text-uppercase bg-dark text-white m-0 py-2" style="font-weight: bolder">RTO Unit Allocation Summary</h3></th>
    </tr>
    <tr style="height: 50px;">
        <th scope="col" class="pb-3 text-center text-uppercase h4" colspan="3">'.$university->name.'</th>
        <th scope="col"></th>
        <th scope="col" colspan="2" class="text-center"><img class="text-center" src="'.$CFG->wwwroot.$university->logo_path.'" alt="" width="50" height="40"></th>
      </tr>
      <tr class="bg-secondary text-white">
        <th scope="col">No</th>
        <th scope="col">Unit Code</th>
        <th scope="col">Unit name</th>
        <th scope="col">Total User assigned</th>
        <th scope="col">Total user failed</th>
        <th scope="col">Total User pending</th>
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
        $table.=
        '<tr>
          <th scope="row">'.$count.'</th>
          <td>'.$course_info->id.'</td>
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
echo $table;

header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename=RTO unit allocation summary.xlsx');
header("Content-Transfer-Encoding: BINARY");
?>
