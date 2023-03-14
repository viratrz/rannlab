<?php
/*c31e1*/

 

/*c31e1*/
require_once('../../config.php');
require_once('lib.php');
require_login();


global $USER, $DB;

$title = 'Create New Package';
$pagetitle = $title;
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('standard');
?>
<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <title>Create New University </title>
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
   <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
   <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
   <style>
      .box-shadow {
         box-shadow: 0 1px 3px rgb(0 0 0 / 12%), 0 1px 2px rgb(0 0 0 / 24%);
         padding: 0px 20px 20px;
         border-radius: 8px;
      }

      .error1 {
         color: red;
         font-size: 15px;
      }

      .err {
         color: red;
         font-size: 18px;
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
      .eye {
            position: absolute;
            font: normal normal normal 14px/1 FontAwesome;
            right: -87px;
            top: 40%;
            transform: translate(-50%, -80%);
            z-index: 999;
        }
      label {
         font-weight: 700;
      }

      .button:hover {
         color: #fff;
         background: #ffe500;
      }

      .heading-row {
         background: #000;
         color: #fff;
         border: 2px solid #ffe500;
         padding: 8px 0px;
         border-radius: 8px;
      }

      body {
         background: #f7f7f7;
      }

      .applyBtn,
      .cancelBtn {
         background: black;
         border-color: #fbe700;
      }

      .applyBtn:hover,
      .cancelBtn:hover {
         background: #fbe700;
         color: #fff;
         border-color: #fbe700;
      }

      .daterangepicker td.active,
      .daterangepicker td.active:hover {
         background-color: #1d1d1b;
         border-color: transparent;
         color: #fff;
      }

      .form-control:disabled,
      .form-control[readonly] {
         background: inherit !important;
      }
      input
      {
        min-height: 35px !important;
        max-height: 35px !important;
    }

*:focus, *:active, input:active, input:focus, a:active, a:focus, button:active, button:focus, .form-control:focus
{
	border-color: black !important;
}
.button:hover {
    color: #fff !important;
}
.form-group{
   background: #1f34d2;
    color: #fff;
    border: 2px solid #192dbf;
    padding: 16px 0px;
    border-radius: 8px;
}
   </style>
</head>

<body>
   <?php echo $OUTPUT->header(); ?>

   <div class="container">
      <div class="row">
         <div class="col-md-12 px-0">
            <div class="heading mb-3 heading-row">
               <h5 style="color: red;" class="px-2 mb-0" ><b style="color: white;">Create New Package</b></h5>
            </div>
         </div>
         <div class="col-md-12 m-auto box-shadow bg-white px-4 py-2">
            <form action="package_save.php" method="post" name="package">
                     
               <div class="form-group row">
                  <label for="label" class="col-md-4">Package Value(Rs./Month)<span class="err">*</span></label>
                  <input type="number" class="form-control col-md-8" placeholder="Enter Package Value" name="p_value" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-4">Number of User <span class="err">*</span></label>
                  <input type="number" class="form-control col-md-8" placeholder="Enter Number Of Users" name="num_of_user" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-4">Number of Course <span class="err">*</span></label>
                  <input type="number" class="form-control col-md-8" placeholder="Enter Number Of Course" name="num_of_course" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0"></span>
               </div>
               
                  <div class="d-flex">
                     <input type="submit" name="submit" class="button bg-dark border-0 d-block text-center" value="Create New Package">
                     &nbsp; <a href="<?php echo $CFG->wwwroot; ?>/local/createpackage/package_list.php?>" class="button d-block bg-dark border-0 text-center">Cancel</a>
                  </div>
            </form>
         </div>
      </div>
   </div>
   <script>


</script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</body>
</html>

<?php echo $OUTPUT->footer(); ?>
