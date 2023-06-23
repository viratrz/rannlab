<?php

require_once('../../config.php');
require_once('lib.php');
$perpage      = optional_param('perpage', 10, PARAM_INT);
$page         = optional_param('page', 0, PARAM_INT);
require_login();



$sub = "Welcome";
$msg = "Hi";
$to_user = new stdClass();
$to_user->email= "titumoney2401@gmail.com";
$to_user->id =2;

$from_user = new stdClass();
$from_user->email= 'clientsmtp@dcc.rannlab.com';
$from_user->maildisplay= true;

email_to_user($to_user,$from_user,$sub,$msg);



global $USER, $DB;
$un_id=$DB->get_record('universityadmin', ['userid'=>$USER->id]);

$uni_users = $DB->get_records_sql("SELECT u.* FROM {user} u INNER JOIN {university_user} s ON u.id = s.userid WHERE s.university_id=$un_id->university_id ORDER BY u.id DESC");
// $uni_user = $DB->get_record("university_user", ['university_id'=>$university_id]);
$last24week = $DB->get_records_sql("SELECT {user}.id FROM {user} inner join {university_user} on {university_user}.userid = {user}.id where {university_user}.university_id = '$university_id' AND week(FROM_UNIXTIME({user}.lastlogin)) >= WEEK( current_date ) - 4 and week(FROM_UNIXTIME({user}.lastlogin)) <= WEEK( current_date ) - 2;");
$last48week = $DB->get_records_sql("SELECT {user}.id FROM {user} inner join {university_user} on {university_user}.userid = {user}.id where {university_user}.university_id = '$university_id' AND week(FROM_UNIXTIME({user}.lastlogin)) >= WEEK( current_date ) - 8 and week(FROM_UNIXTIME({user}.lastlogin)) <= WEEK( current_date ) - 4;");
$more8week = $DB->get_records_sql("SELECT {user}.id FROM {user} inner join {university_user} on {university_user}.userid = {user}.id where {university_user}.university_id = '$university_id' AND week(FROM_UNIXTIME({user}.lastlogin)) <= WEEK( current_date ) - 8 ;");

$totalcount = count($uni_users);

$start = $page * $perpage;
if ($start > count($uni_users)) {
  $page = 0;
  $start = 0;
}
$uni_users = array_slice($uni_users, $start, $perpage, true);


$name = 'User List';
// $url = new moodle_url("/local/createuser/table.php");
$url = new moodle_url("/local/dashboard/resendinvite.php");
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
  <title>Invite Pending</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
      padding: 6px 32px;
      color: #fff;
      border-radius: 8px;
      white-space: nowrap;
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
        <div class="p-0 box-shadow">
          <div class="heading-row">
            <div class="col-md-12">
              <h5 class="mb-0" style="color: white;" >Invite Pending</h5>
            </div>
          </div>
          <!--<h5 class="mt-2 mr-1" style="color: purple; text-align: end;"><a class="btn btn-info" href="<?php echo $CFG->wwwroot.'/local/createuser/index.php'; ?>"><i class="fa fa-plus-circle" aria-hidden="true"></i>Add New User</a></h5>-->
          <table class="table table-hover table-bordered">
            <thead>
              <tr class="bg-secondary">
                <th>User Name</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email-Id</th>
                <!--<th>Role</th>-->
                <!--<th>Action</th>-->
                 <th>
                  <input type="checkbox" id="select-all-checkbox">
                </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($uni_users as $user) {
                $role_id = $DB->get_record('role_assignments', ['userid'=>$user->id]);
                $role_name = $DB->get_record('role', ['id'=>$role_id->roleid]);
                ?>
                <tr>
                  
                  <td><a style="color: black;" href="#" class="p-2" onclick="UserProfile(<?php echo $user->id; ?>);"><?php echo $user->username; ?></a></td>
                  <td><?php echo $user->firstname; ?></td>
                  <td><?php echo $user->lastname; ?></td>
                  <td><?php echo $user->email; ?></td>
                  
        
                  
                  <td>
                    <input type="checkbox" name="user[]" value="<?php echo $user->id; ?>">
                  </td>
                  
                  
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
          <button id="send-message-btn" class="button">Send Message</button>
        </div>
        </div>
      </div>
    </div>
  </div>

<div class="pagination mt-3">
  <?php echo $OUTPUT->paging_bar($totalcount, $page, $perpage, $url); ?>
</div>

</body>
</html>


<script>

  
  // Function to handle the "select all" checkbox
  var selectAllCheckbox = document.getElementById('select-all-checkbox');
  selectAllCheckbox.addEventListener('change', function() {
    var checkboxes = document.getElementsByName('user[]');
    for (var i = 0; i < checkboxes.length; i++) {
      checkboxes[i].checked = this.checked;
    }
  });
  
  
  
// 





// Function to send the customized message to selected students
var sendMessageBtn = document.getElementById('send-message-btn');
sendMessageBtn.addEventListener('click', function() {
  var checkboxes = document.getElementsByName('user[]');
  var selectedStudents = [];
  for (var i = 0; i < checkboxes.length; i++) {
    if (checkboxes[i].checked) {
      selectedStudents.push(checkboxes[i].value);
    }
  }

  if (selectedStudents.length > 0) {
    // Prompt the user to enter a customized message
    var customMessage = prompt("Enter your message:");

    if (customMessage) {
      // Send the message to selected students
      for (var i = 0; i < selectedStudents.length; i++) {
        var userId = selectedStudents[i];
        // Code to send the message to the user with userId
        // You can use AJAX or any server-side method to send the message
        // Example AJAX code:
        
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "send_message.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
            // Handle the response if needed
          }
        };
        xhr.send("userId=" + userId + "&message=" + encodeURIComponent(customMessage));
      }
      alert("Message sent to selected students");
    } else {
      alert("No message entered");
    }
  } else {
    alert("No students selected");
  }
});






  
  

</script>
<?php echo $OUTPUT->footer(); ?>
 
