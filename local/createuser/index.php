<?php
/*8adab*/

#Raju__

/*8adab*/

require_once('../../config.php');
require_once('lib.php');
require_login();

global $USER, $DB;

$title = 'Create User';
$pagetitle = $title;
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('standard');

?>
   <?php echo $OUTPUT->header(); ?>
<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <title>Create User </title>
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"> -->
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
         display: none;
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
            right: 0px;
            top: 22px;
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
      input, select
      {
         min-height: 35px !important;
         max-height: 35px !important;
      }
      #loader
      {
         display: none;
         text-align: center;
      }
   </style>
</head>

<body>

   <div class="container">
      <div class="row">
         <div class="col-md-12 px-0">
            <div class="heading mb-3 heading-row">
               <h5 style="color: red;" class="px-2 mb-0" ><b style="color: white;">Create User</b></h5>
            </div>
         </div>
         <div class="col-md-12 m-auto box-shadow bg-white p-4">
            <form action="" id="addnewAdmin" method="get">
                     
               <div class="form-group row">
                  <label for="label" class="col-md-3">User name <span class="err">*</span></label>
                  <input type="text" class="form-control col-md-9 focusError" id="username" placeholder="Enter User Name" name="username" onkeyup="smallOnly(this.value)" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0" id="a_username"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">First Name <span class="err">*</span></label>
                  <input type="text" class="form-control col-md-9 focusError" id="firstname" placeholder="Enter First Name" name="firstname" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Last Name <span class="err">*</span></label>
                  <input type="text" class="form-control col-md-9 focusError" id="lastname" placeholder="Enter Last Name" name="lastname" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Email-Id <span class="err">*</span></label>
                  <input type="email" class="form-control col-md-9 focusError" id="email" placeholder="Enter Email-Id" name="email" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0" id="a_email" ></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Confirm Email-Id <span class="err">*</span></label>
                  <input type="email" class="form-control col-md-9 focusError" id="confirmemail" placeholder="Enter Email-Id" name="email_id" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Password <span class="err">*</span></label>
                  <div class="calendar col-md-9 p-0">
                     <input type="password" style="width: 112%;" class="form-control focusError" placeholder="Enter Password" id="password" name="password" value="">
                     <i class="fa fa-eye-slash eye" aria-hidden="true" onclick="showPassword(1)"></i>
                     <span class="errormsg2" id="pasward"></span>
                     <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0"></span>
                  </div>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Confirm Password <span class="err">*</span></label>
                  <div class="calendar col-md-9 p-0">
                     <input type="password" class="form-control focusError" style="width: 100%;" placeholder="Confirm Password" id="confirmpassword" name="confirmpassword" value="">
                     <i class="fa fa-eye-slash eye" aria-hidden="true" onclick="showPassword()"></i>
                     <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0"></span>
                  </div>
               </div>
               <?php $all_role = $DB->get_records_sql("SELECT * FROM {role}"); 
               ?>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Role Of User<span class="err">*</span></label>
                  <div class="calendar col-md-3 p-0">
                     <select name="role_id" id="role"style="width: 100%;" class="form-control focusError">
                        <option value="">Select Role</option>
                     <?php 
                        foreach($all_role as $role)
                        { 
                           if ($role->shortname === 'teacher' || $role->shortname === 'student'  || $role->shortname === 'subuniversityadmin') 
                           {                          
                     ?>
                        <option value="<?php echo $role->id; ?>"><?php echo ucwords($role->shortname); ?></option>
                     <?php  } } ?>
                     </select>
                     <span class="errormsg2" id="roleid">   </span>                  
                  </div>
                  <div class="col-md-6 pl-2"><span class="error1 pl-0 "></span></div>
                  
               </div>
                  <div class="d-flex">
                     <a href="##" class="button d-block  text-center" onclick="addAdmin();">Create User</a> &nbsp;
                     <a href="<?php echo $CFG->wwwroot; ?>/local/dashboard/table.php?>" class="button d-block   text-center">Cancel</a>

                     <div id="loader" class="col-md-6">
                     <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                     </div>
                     </div>
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

function smallOnly(inputvalue) {
   var smallletter = /^[a-z0-9@_.]+$/;
   var erroeClass = document.getElementsByClassName('error1');
   if (inputvalue.length > 0)
   {
      if(inputvalue.match(smallletter)){
      erroeClass[0].innerHTML = " ";
     }
   else{
      erroeClass[0].innerHTML = "Username contains only [samll letter,Number ,@,_,.]";
      erroeClass[0].style.display= "block";
     }
   }
   else{
      erroeClass[0].innerHTML = " ";
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
   var role = $("#role").val();

   var arr_val=[username, firstname, lastname, email, confirm_email, password, confirm_password, role];
   var err =document.getElementsByClassName("error1");
   var focusError = document.getElementsByClassName('focusError');
   for (let i = 0; i < 8; i++) 
   {
      var ws_val = arr_val[i];
      ws_val = arr_val[i].trim();
      if ( ws_val !='') 
      {
         err[i].innerHTML="";
         err[i].style.display="none";
         focusError[i].style.border = "1px solid rgba(0,0,0,.1)";
      } 
      else 
      {
         if (i == 7)
         err[i].innerHTML="* Please Select Role";
         else 
            err[i].innerHTML="* This field is required";
         err[i].style.display="block";
         focusError[i].style.border = "1px solid red";
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
         err[4].innerHTML="Email Id Does Not Match"; 
         err[4].style.display="block";     
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
         err[6].innerHTML="Passwords Does Not Match";
         err[6].style.display="block";      
      }
   }

   if (eml_val && pass_val && username.trim() && firstname.trim() && lastname.trim()) 
   {
      if (email.indexOf('@') < 0 || email.indexOf('.') < 0) 
      {
         $("#emailer").text('Invalid Email');
      }
      else {
      $.ajax({
      type: "POST",
      url: "<?php echo $CFG->wwwroot ?>" + "/local/createuser/create_user.php",
      dataType: "json",
      data: $("#addnewAdmin").serialize(),
      beforeSend: function(){
      $("#loader").show();
      },
      complete:function(data){
      $("#loader").hide();
      },
      success: function(json) {

         if (json.success) 
         {
            window.location.href = "<?php echo $CFG->wwwroot ?>" + "/local/createuser/user_list.php?msg="+json.ucs;
         } 
        if(json.ule)
         {
            alert(json.ule);
         }
         if (json.msg2) {
            $("#a_username").html(json.msg2);
            $("#a_username").show();
         }
         if (json.msg3) {
            $("#a_email").html(json.msg3);
            $("#a_email").show();
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