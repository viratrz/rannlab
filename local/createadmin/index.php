<?php
/*e1e9b*/

 

/*e1e9b*/

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
         /*background: #000;
         padding: 10px 15px;
         color: #fff;
         text-decoration: none !important;
         border-radius: 4px;
         border: 1px solid #ffe500;
         font-weight: 600;
         box-shadow: 0 1px 3px rgb(0 0 0 / 12%), 0 1px 2px rgb(0 0 0 / 24%); */
         
         box-shadow: 0 1px 3px rgb(0 0 0 / 12%), 0 1px 2px rgb(0 0 0 / 24%); 
         background-color: #6c757d;
         border-width: 2px;
         border-color: #6c757d;
         border-radius: 5px;
         padding: 10px 15px;
         color: #fff;
      }
      .eye {
            position: absolute;
            font: normal normal normal 14px/1 FontAwesome;
            right: 5px  !important;
            top: 22px;
    right: 22px;
            transform: translate(-50%, -80%);
            z-index: 999;
        }
      label {
         font-weight: 700;
      }

      .button:hover {
         /*color: #000;
         background: #ffe500; */
         
      background-color: transparent;
      border-color: #6c757d;
      color: #6c757d;
      -webkit-box-shadow: 0 1px 4px 0 rgb(0 0 0 / 0%);
      -moz-box-shadow: 0 1px 4px 0 rgba(0,0,0,0);
      box-shadow: 0 1px 4px 0 rgb(0 0 0 / 0%);
      text-decoration: none;
      BORDER: 2PX SOLID #6c757d;
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

      .
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
      
      *:focus, *:active, input:active, input:focus, a:active, a:focus, button:active, button:focus, .form-control:focus {
      border-color: #6c757d !important;
        }
        .form-group{
       font-family: "Nunito",sans-serif;
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
                  <div class="col-md-9 p-0">
                     <input type="password"  class="form-control focusError" placeholder="Enter Password" id="password" name="password" value=""  onkeyup="checksPassword(this.value)"/>
                     <i class="fa fa-eye-slash eye" aria-hidden="true" onclick="showPassword(1)"></i></div>
                      <span class="error_message spassword_error" style="display: none; color: red;  margin-left:290px;" id="pasward">Enter minimum 8 chars with atleast 1 number, lower, upper &amp; special(@#$%&!-_&amp;) char.</span>
                     <div class="col-md-3"></div>
                     <span class="errormsg2" id="pasward"></span>
                     <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Confirm Password <span class="err">*</span></label>
                  <div class="col-md-9 p-0">
                     <input type="password" class="form-control focusError" placeholder="Confirm Password" id="confirmpassword" name="confirmpassword" value="">
                     <i class="fa fa-eye-slash eye" aria-hidden="true" onclick="showPassword()"></i></div>
                     <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0"></span>
               </div>
                  <div class="d-flex">
                     <a href="##" class="button d-block  text-center" onclick="addAdmin();">Create New Admin</a> &nbsp;
                     <a href="<?php echo $CFG->wwwroot; ?>" class="button d-block   text-center">Cancel</a>
                     
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
function checksPassword(password){
var pattern = /^.*(?=.{8,20})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%&!-_]).*$/;
if(!pattern.test(password)) {
$(".spassword_error").show();
}else
{
$(".spassword_error").hide();
}
}


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
   var smallletter = /^[A-Za-z0-9]+$/;                   // /^[a-z0-9@_.]+$/;
   var erroeClass = document.getElementsByClassName('error1');
   if (inputvalue.length > 0)
   {
      if(inputvalue.match(smallletter)){
      erroeClass[0].innerHTML = " ";
     }
   else{
      erroeClass[0].innerHTML = "Username contains only [Uppercase, Lowercase and numbers]";
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

   var arr_val=[username, firstname, lastname, email, confirm_email, password, confirm_password];
   var err =document.getElementsByClassName("error1");
   var focusError = document.getElementsByClassName('focusError');
   for (let i = 0; i < 7; i++) 
   {
      var ws_val = arr_val[i];
      ws_val = arr_val[i].trim();
      if ( ws_val !='') 
      {
         err[i].innerHTML="";
         focusError[i].style.border = "1px solid rgba(0,0,0,.1)";
      } 
      else 
      {
         err[i].innerHTML="This Field Is Required";
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
      type: "POST",
      url: "<?php echo $CFG->wwwroot ?>" + "/local/createadmin/custom_admin.php",
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
            window.location.href = "<?php echo $CFG->wwwroot ?>" + "/local/createadmin/custom_admin_list.php?msg="+json.msg;
         } 
         else
         {
            alert(json.msg);
         }
         if (json.msg2) {
            $("#a_username").html(json.msg2);
         }
         if (json.msg3) {
            $("#a_email").html(json.msg3);
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
