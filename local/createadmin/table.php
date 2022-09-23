<?php

require_once('../../config.php');
require_once('lib.php');
$perpage      = optional_param('perpage', 10, PARAM_INT);
$page         = optional_param('page', 0, PARAM_INT);
require_login();
global $USER, $DB;
$un_id=$DB->get_record('universityadmin', ['userid'=>$USER->id]);

$school = $DB->get_records_sql("SELECT * FROM {user} u INNER JOIN {universityadmin} s ON u.id = s.userid WHERE s.universityid=$un_id->universityid");

$totalcount = count($school);

$start = $page * $perpage;
if ($start > count($school)) {
  $page = 0;
  $start = 0;
}
$school = array_slice($school, $start, $perpage, true);


$name = 'Admin List';
$url = new moodle_url("/local/dashboard/table.php");

$PAGE->set_title($name);
$PAGE->set_heading($name);
$PAGE->set_pagelayout('standard');

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Admin List</title>
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
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header" style="background:#1d1d1b; border:1px solid #ffe500;">
          <h5 class="modal-title" id="exampleModalLabel" style="color:#fff;"><b>Login As</b></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="color:#fff;">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <form id="country" method="post">
            <input type="hidden" name="schoolid" id="schoolid" value="<?php echo $id; ?>">
            <div class="form-group row">
              <label for="label" class="col-md-12"><b> University Admin List </b></label>
              <select name="cars" class="col-md-11 m-auto modal-select" id="adminlist" style="padding:8px; border-radius:5px;">

              </select>
              <div class="col-md-12 mt-3">
                <a href="#" class="button mb-1 text-center" onclick="schooladmin();">Login</a>
                <a href="#" class="button mt-1 text-center" onclick="cleardata();" data-dismiss="modal">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="box-shadow">
          <div class="row mb-4 heading-row">
            <div class="col-md-12">
              <h5 class="mb-0" style="color: white;" >Admin List</h5>
            </div>
          </div>
          

          <table class="table table-striped table-bordered">
            <thead>
              <tr class="bg-grey">
                <th>User Name</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email-Id</th>


                <th style="width:200px;">Action</th>

              </tr>
            </thead>
            <tbody class="myTable">
              <?php foreach ($school as $sch) { ?>
                <tr>
                  <td><?php echo $sch->username; ?></td>
                  <td><?php echo $sch->firstname; ?></td>
                  <td><?php echo $sch->lastname; ?></td>
                  <td><?php echo $sch->email; ?></td>
                  <td><a href="#" class="p-2" onclick="editschool(<?php echo $sch->id; ?>);"><i class="fa fa-pencil" aria-hidden="true" title="Edit School" style="color:#000;"></i></a>
                    <a href="#" onclick="deleteUser(<?php echo $sch->id; ?>)" class="" style="padding:8px;" ><i class="fa fa-trash" title="Delete Seller"  aria-hidden="true" style="color:#000;"></i></a>

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



    function editschool(id) {
      window.location.href = "<?php echo $CFG->wwwroot ?>" + "/local/dashboard/editschool.php/?edit=" + id;
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