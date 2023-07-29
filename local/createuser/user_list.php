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
if(!$un_id){
    if(is_siteadmin()) {
        $userpage = new moodle_url('/admin/user.php');
        redirect($userpage);
    }
}
$uni_users = $DB->get_records_sql("SELECT u.* FROM {user} u INNER JOIN {university_user} s ON u.id = s.userid WHERE s.university_id=$un_id->university_id ORDER BY u.id DESC");

$totalcount = count($uni_users);

$start = $page * $perpage;
if ($start > count($uni_users)) {
  $page = 0;
  $start = 0;
}
$uni_users = array_slice($uni_users, $start, $perpage, true);


$name = 'User List';
$url = new moodle_url("/local/createuser/table.php");

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
  <title>User List</title>
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
              <h5 class="mb-0" style="color: white;" >User List</h5>
            </div>
          </div>
          <h5 class="mt-2 mr-1" style="color: purple; text-align: end;"><a class="btn btn-info" href="<?php echo $CFG->wwwroot.'/local/createuser/index.php'; ?>"><i class="fa fa-plus-circle" aria-hidden="true"></i>Add New User</a></h5>
          <table class="table table-hover table-bordered">
            <thead>
              <tr class="bg-secondary">
                <th>User Name</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email-Id</th>
                <th>Role</th>
                <th>Action</th>
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
                  <td><?php if($role_name->shortname == 'ua')echo "University Admin";else echo ucfirst($role_name->name); ?></td>
                  <td><a href="#" class="p-2" onclick="editUser(<?php echo $user->id; ?>);"><i class="fa fa-pencil" aria-hidden="true" title="Edit" style="color:#000;"></i></a>
                    <a href="#" onclick="deleteUser(<?php echo $user->id; ?>)" class="" style="padding:8px;" ><i class="fa fa-trash text-danger" title="Delete"  aria-hidden="true" style="color:#000;"></i></a>
                    <input type="hidden" id="sessionkey" value="<?php echo $USER->sesskey; ?>">
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
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
  function deleteUser(id)
  {
    // alert(id);
    if(confirm("Are you sure you want to delete this user?"))
      window.location.href="<?php echo $CFG->wwwroot?>"+"/local/createuser/delete_user.php?del_id="+id;   
  }

  function editUser(id) 
  {
    window.location.href = "<?php echo $CFG->wwwroot ?>" + "/local/createuser/edit_user.php?edit_id=" + id;
  }
  function UserProfile(id) 
  {
    window.location.href = "<?php echo $CFG->wwwroot ?>" + "/user/profile.php?id=" + id;
  }
  
</script>

<?php echo $OUTPUT->footer(); ?>

