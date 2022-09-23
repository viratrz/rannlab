<?php
require_once('../../config.php');
require_once('lib.php');
require_login();

global $USER, $DB;

$title = 'Create New Admin';
$pagetitle = $title;
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('standard');
?>
<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <title>Create New Admin </title>
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
         font-size: 150%;
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
         color: #000;
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
   </style>
</head>

<body>
   <?php echo $OUTPUT->header(); ?>
   <div class="container">
      <div class="row">
         <div class="col-md-12 px-0">
            <div class="heading mb-3 heading-row">
               <h5 style="color: red;" class="px-2 mb-0" ><b style="color: white;">Create New Admin</b></h5>
            </div>
         </div>
         <div class="col-md-12 m-auto box-shadow bg-white p-4">
            <form action="/action_page.php" id="addnewAdmin" method="get">
                     
               <div class="form-group row">
                  <label for="label" class="col-md-3">User name <span class="err">*</span></label>
                  <input type="text" class="form-control col-md-9" id="username" placeholder="Enter User Name" name="username" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">First Name <span class="err">*</span></label>
                  <input type="text" class="form-control col-md-9" id="firstname" placeholder="Enter First Name" name="firstname" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Last Name <span class="err">*</span></label>
                  <input type="text" class="form-control col-md-9" id="lastname" placeholder="Enter Last Name" name="lastname" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Email-Id <span class="err">*</span></label>
                  <input type="email" class="form-control col-md-9" id="email" placeholder="Enter Email-Id" name="email" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0" id="emailer" ></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Confirm Email-Id <span class="err">*</span></label>
                  <input type="email" class="form-control col-md-9" id="confirmemail" placeholder="Enter Email-Id" name="email_id" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Password <span class="err">*</span></label>
                  <div class="calendar col-md-8 p-0">
                     <input type="password" style="width: 112%;" class="form-control" placeholder="Enter Password" id="password" name="password" value="">
                     <i class="fa fa-eye-slash eye" aria-hidden="true" onclick="showPassword(1)"></i>
                     <span class="errormsg2" id="pasward"></span>
                     <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0"></span>
                  </div>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Confirm Password <span class="err">*</span></label>
                  <div class="calendar col-md-8 p-0">
                     <input type="password" class="form-control" style="width: 112%;" placeholder="Confirm Password" id="confirmpassword" name="confirmpassword" value="">
                     <i class="fa fa-eye-slash eye" aria-hidden="true" onclick="showPassword()"></i>
                     <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0"></span>
               </div>
                  <div class="d-flex">
                     <a href="#" class="button d-block  text-center" onclick="addAdmin();">Create New Admin</a> &nbsp;
                     <a href="<?php echo $CFG->wwwroot; ?>/local/dashboard/table.php?>" class="button d-block   text-center">Cancel</a>
                  </div>
            </form>
         </div>
      </div>
   </div>

<script>
$(document).ready(function() 
{
   $(".eye").click(function() {
      $(this).toggleClass("fa fa-eye fa fa-eye-slash");
   });
});

function showPassword(obj) 
{  
   if (obj == 1) 
   {
      var x = document.getElementById("password");
      if (x.type === "password") {
         x.type = "text";
      } else {
         x.type = "password";
      }
   }
   else
   {
      var x = document.getElementById("confirmpassword");
      if (x.type === "password") {
         x.type = "text";
      } else {
         x.type = "password";
      }
   }
}

function validateEmail(email) 
{
   var re = /\S+@\S+\.\S+/;
   return re.test(email);
}

function addAdmin() 
{
   var username = $("#username").val();
   var firstname = $("#firstname").val();
   var lastname = $("#lastname").val();
   var email = $("#email").val();
   var confirm_email = $("#confirmemail").val();
   var password = $("#password").val();
   var confirm_password = $("#confirmpassword").val();

   var arr_val=[username, firstname, lastname, email, confirm_email, password, confirm_password];
   var err =document.getElementsByClassName("error1");
   for (let i = 0; i < 7; i++) 
   {
      var ws_val = arr_val[i];
      ws_val = arr_val[i].trim();
      if ( ws_val !='') 
      {
         err[i].innerHTML="";
      } 
      else 
      {
         err[i].innerHTML="* this field is required";
      }
   }
   var pass_val=false;
   var eml_val=false;
   if (confirm_email.trim()) 
   {
      if (email == confirm_email) 
      {
         eml_val=true;
      } 
      else
      {
         err[4].innerHTML="Email-Id Not Match";      
      }
   }
   
   if (confirm_password.trim()) 
   {
      if (password == confirm_password) 
      {
         pass_val=true;
      } 
      else
      {
         err[6].innerHTML="Password Not Match";      
      }
   }

   if (eml_val && pass_val && username.trim() && firstname.trim() && lastname.trim()) 
   {
      if (email.indexOf('@') < 0 || email.indexOf('.') < 0) {
         $("#emailer").text('email id not valid');
      }
      else {
      $.ajax({
      type: "GET",
      url: "<?php echo $CFG->wwwroot ?>" + "/local/createadmin/customAdmin.php",
      dataType: "json",
      data: $("#addnewAdmin").serialize(),
      async: false,
      success: function(json) {
         if (json.success) 
         {
            alert(json.msg);
            window.location.href = "<?php echo $CFG->wwwroot ?>" + "/local/createadmin/table.php";
         } 
         if (json.msg2) {
            alert(json.msg2);
         }
         if (json.msg3) {
            alert(json.msg3);
         }            
      }
   });
}
   }
}
</script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</body>
</html>

<?php echo $OUTPUT->footer(); ?>