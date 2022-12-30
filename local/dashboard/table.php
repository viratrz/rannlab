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
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Main login page.
 *
 * @package    core
 * @subpackage auth
 * @copyright  1999 onwards Martin Dougiamas  http://dougiamas.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('lib.php');
$perpage      = optional_param('perpage', 10, PARAM_INT);
$page         = optional_param('page', 0, PARAM_INT);
require_login();
global $USER, $DB;
if (user_has_role_assignment($USER->id, 11)) {
  $school = $DB->get_records_sql("SELECT * FROM {seller_school} ss INNER JOIN {school} s ON s.id=ss.schoolid WHERE ss.userid=$USER->id ORDER BY s.id desc");
} else {
  $school = $DB->get_records_sql("SELECT * FROM {school} order by id desc");
}

// var_dump($maindomain);
// die;
$totalcount = count($school);

$start = $page * $perpage;
if ($start > count($school)) {
  $page = 0;
  $start = 0;
}
$school = array_slice($school, $start, $perpage, true);


$name = 'University List';
$url = new moodle_url("/local/dashboard/table.php");

$PAGE->set_title($name);
$PAGE->set_heading($name);
$PAGE->set_pagelayout('standard');

if ($_GET['msg']) {
  echo(\core\notification::success($_GET['msg']));
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>University List</title>
  <script src="https://kit.fontawesome.com/5e0bc8cc0d.js" crossorigin="anonymous"></script>
  <!-- <link rel="preconnect" href="https://fonts.googleapis.com"> -->
  <!-- <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  

  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

  <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <style>
    .fade:not(.show) {
      opacity: 1 !important;
    }

    .box-shadow {
      box-shadow: 0 1px 3px rgb(0 0 0 / 12%), 0 1px 2px rgb(0 0 0 / 24%);
      padding: 0px 20px 20px;
      border-radius: 8px;
    }

    body {
      background: #f7f7f7;
    }

    td a {
      color: #fff;
    }

    td a:hover {
      color: #fff;
      text-decoration: none;
    }

    .button {
      background: #000;
      padding: 10px 15px;
      color: #fff;
      text-decoration: none !important;
      border-radius: 4px;
      border: 1px solid #ffe500;
      font-weight: 600;
      box-shadow: 0 1px 3px rgb(0 0 0 / 12%), 0 1px 2px rgb(0 0 0 / 24%);
    }

    .button:hover {
      color: #000;
      background: #ffe500;
    }

    table {
      border-left: 1px solid #f2f2f2;
      border-right: 1px solid #f2f2f2;
    }

    .heading-row {
      background: #000;
      color: #fff;
      border: 2px solid #ffe500;
      padding: 8px 0px;
      border-radius: 8px;
    }


    .modal-select {
      margin: 0px 15px !important;
    }
  </style>
</head>

<body>
  <?php echo $OUTPUT->header(); ?>
  
  <div class="container">
    <div class="row">
      <div class="p-0 col-md-12">
        <div class=" p-0 box-shadow">
          <div class="mb-3 heading-row">
            <div class="col-md-12">
              <h5 class="mb-0 ml-0" style="color: white;">University List</h5>
            </div>
          </div>
          <div class="d-flex px-1">
            <div class="mr-3">
              <select id="filterselect" class="form-control">
                <option value="1" id="schoolfilter">University Name</option>
                <option value="2" id="countryfilter">Country</option>
                <option value="3" id="cityfilter">City/Town</option>
              </select>
            </div>
            <div class="">
              <input id="myInput" class="form-control" onkeyup="filtertable()">
            </div>

            <div style="text-align:end;" class="ml-2">
              <a href="javascript:window.location.reload(true)" class="btn">Clear</a>
            </div>
            <div style="text-align:end;" class="ml-auto">
              <a href="<?php echo $CFG->wwwroot ?>/local/dashboard/index.php" class="btn btn-info"><i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>
                Add New University</a>
            </div>
          </div>

          <table class="table table-hover table-bordered">
            <thead>
              <tr class="bg-secondary">
                <th>University Name</th>
                <th>Domain Name</th>
                <th>Country</th>
                <th>City/Town</th>
                <th>Subcription & Payment Details</th>
                <!-- <th>Current Month Bill</th> -->
                <th style="width:150px;">Action</th>
                
              </tr>
            </thead>
            <tbody class="myTable">
              <?php foreach ($school as $sch) { ?>
                <tr>
                  <td><?php echo $sch->name; ?></td>
                  <td>
                    <a  href="http://<?php echo $sch->domain.".".$maindomain; ?>" target="_blank" class="p-0"><?php echo $sch->domain.'.'.$maindomain; ?></a>
                  </td>
                  <td><?php echo $sch->country; ?></td>
                  <td><?php echo $sch->city; ?></td>
                  <!-- <td>Nov</td> -->
                  <td style="text-align: center;"><a href="<?php echo $CFG->wwwroot.'/local/dashboard/subcription_payment.php?un_id='.$sch->id.'';?>" class="btn btn-info py-0 px-1">View</a></td>
                  <td class="px-0">
                    <a href="#" class="p-2" onclick="editschool(<?php echo $sch->id; ?>);"><i class="fa fa-pencil" aria-hidden="true" title="Edit" style="color:#000;"></i></a>
                    <a href="#" class="p-2" onclick="assigncourse(<?php echo $sch->id; ?>);"><i class="fa fa-book" title="Assign Course" aria-hidden="true" style="color:#000;"></i></a>
                    <a href="<?php echo $CFG->wwwroot.'/local/dashboard/course_report.php?uni_id='.$sch->id; ?>" class="mr-1" style="padding:2px;" title="View RTO UA Summary"><i class="fa-sharp fa-solid fa-eye"></i></a>
                    <a href="<?php echo $CFG->wwwroot.'/local/changelogo/theme.php?uni_id='.$sch->id; ?>" class="mr-1" style="padding:2px;" title="Change Theme"><i class="fa-sharp fa-solid fa-palette"></i></a>
                    <a href="#" onclick="deleteUser(<?php echo $sch->id; ?>)" class="" style="padding:2px; color: red;" ><i class="fa-solid fa-trash"></i></a>
                  </td>                 
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- <div class="pagination mt-3"> -->
    <?php echo $OUTPUT->paging_bar($totalcount, $page, $perpage, $url); ?>
  <!-- </div> -->
  <script>
    function adminlist(schoolid) {
      var admin = 1;
      $.ajax({
        type: "GET",
        url: "<?php echo $CFG->wwwroot ?>" + "/local/dashboard/adminlist.php",
        dataType: "json",
        data: {
          admin: admin,
          schoolid: schoolid
        },
        async: false,
        success: function(json) {
          if (json.sucess) {
            $("#adminlist").html(json.html);
          }
        }
      });
    }

    function deleteUser(id)
  {
      
      if(confirm("Are you sure you want to Delete this university?"))
      {
        window.location.href="<?php echo $CFG->wwwroot?>"+"/local/dashboard/deleteuniversity.php?del_id="+id;   
      }
  }

    function filtertable() {
      var id = $("#filterselect").val();
      var value = $("#myInput").val();
      $.ajax({
        type: "GET",
        url: "<?php echo $CFG->wwwroot ?>" + "/local/dashboard/filtertable.php",
        dataType: "json",
        data: {
          id: id,
          value: value
        },
        async: false,
        success: function(json) {
          if (json.success) {
            $(".myTable").html(json.html);
            $(".pagination").css("display", "none");

          }
        }
      });
    }


    $("#myInput").on("keyup", () => filtertable());


    function editschool(id) {
      window.location.href = "<?php echo $CFG->wwwroot ?>" + "/local/dashboard/editschool.php?edit=" + id;
    }

    function assignschool(id) {
      window.location.href = "<?php echo $CFG->wwwroot ?>" + "/local/dashboard/assignschool.php?id=" + id;
    }

    function assigncourse(id) {
      window.location.href = "<?php echo $CFG->wwwroot ?>" + "/local/dashboard/assigncourses.php?id=" + id;
    }

    function schooladmin() {
      var admin = $("#adminlist").val();
      if (admin == "") {
        alert("Please select a School admin First!");
        return;
      }
      var sess = $("#sessionkey").val();
      var id = $("#adminlist [value='" + admin + "']").attr('data-attr');
      if (confirm("Are you sure you want to log in as a School Admin?")) {
        window.location.href = "<?php echo $CFG->wwwroot ?>" + "/local/dashboard/loginadmin.php/?schoolid=" + id + "&adminid=" + admin + "&sesskey=" + sess;

      }
    }

    function suspend(id, suspend) {
      if (suspend == 0) {
        var sus = "Unsuspend";
      } else {
        var sus = "Suspend";
      }
      if (confirm("Are you sure you want to " + sus + " the School?")) {
        $(".fa-eye").toggleClass("fa-eye-slash");
        window.location.href = "<?php echo $CFG->wwwroot ?>" + "/local/dashboard/loginadmin.php/?suspend=" + id;
      }
    }
  </script>
</body>

</html>
<?php echo $OUTPUT->footer(); ?>