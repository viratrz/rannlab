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
require_login();


global $USER, $DB;

// $tt=user_has_role_assignment($USER->id, 11);
// var_dump($tt);

if (!is_siteadmin()) {
   redirect(new moodle_url("/my"));
}
$countries = get_string_manager()->get_list_of_countries(true);
$title = 'Create New University';
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
      #package_info
      {
         display:block;
         width: 50%;
      }
   </style>
</head>

<body>
   <?php echo $OUTPUT->header(); ?>
   <div class="container">
      <div class="row">
         <div class="col-md-12 px-0">
            <div class="heading mb-3 heading-row">
               <h5 style="color: red;" class="px-2 mb-0" ><b style="color: white;">Create New University</b></h5>
            </div>
         </div>
         <div class="col-md-12 m-auto box-shadow bg-white p-4">
            <form action="/action_page.php" id="addnewuniversity" method="get">
               <div class="form-group row">
                  <label for="label" class="col-md-3">Long Name <span class="err"> *</span> </label>
                  <input type="text" class="form-control col-md-9 err" id="longname" placeholder="Enter Long Name" name="longname" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Short Name <span class="err"> *</span></label>
                  <input type="text" class="form-control col-md-9" id="shortname" placeholder="Enter Short Name" name="shortname" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Address <span class="err">*</span></label>
                  <textarea class="form-control col-md-9" rows="5" id="address" name="address" required></textarea>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>

               <div class="form-group row">
                  <label for="label" class="col-md-3">Country <span class="err"> *</span></label>
                  <select class="form-control col-md-3" id="country" name="country" required>
                     <option value="">Select a country...</option>
                     <?php foreach ($countries as $key => $country) { ?>
                        <option value="<?php echo $country; ?>"><?php echo $country; ?></option>
                     <?php } ?>
                  </select>
                  <div class="col-md-3"><span class="error1 col-md-8 pl-0"></span> </div>
                       
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">City/Town <span class="err">*</span> </label>
                  <input type="text" class="form-control col-md-9" id="city" placeholder="Enter City/Town" name="city" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
               <?php 
                  $all_packages = $DB->get_records_sql("SELECT * FROM {package}");
               ?>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Select Package <span class="err">*</span> </label>
                  <select name="package" class="col-md-3" id="package" onchange="selectPackage(this.value)">
                     <option value="">Select package</option>
                     <?php foreach($all_packages as $package){?>
                     <option value="<?php echo $package->id; ?>"><?php echo $package->package_value; ?></option>
                     <?php } ?>
                  </select>
                  <div class="col-md-3"><span class="error1 col-md-8 pl-0"></span></div>
                  
               <span id="package_info"> </span>

               </div>
               

               <div class="heading mb-3">
                  <h5 class="text-primary font-weight-bold">Enter University Super Admin Details</h5>
               </div>
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
                     <span class="error1 col-md-8 pl-0"></span>
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
                  <div class="d-flex">&nbsp;&nbsp;
                     <a href="#" class="button d-block  text-center" onclick="adduniversity();">Create New University </a>&nbsp;
                     <a href="<?php echo $CFG->wwwroot; ?>/local/dashboard/table.php?>" class="button d-block   text-center">Cancel</a>
                  </div>
            </form>
         </div>
      </div>
   </div>

<script>
$(document).ready(function() 
{
   $(".eye").click(function() 
   {
      $(this).toggleClass("fa fa-eye fa fa-eye-slash");
   });
});
// ***************Eye end*******************
function showPassword(obj) 
{
   if (obj == 1) 
   {
      var x = document.getElementById("password");
      if (x.type === "password") 
         x.type = "text";
      else 
         x.type = "password";  
   } 
   else 
   {
      var x = document.getElementById("confirmpassword");
      if (x.type === "password")
         x.type = "text";
      else 
         x.type = "password";
   }
}
// *****************showPassword End****************
function validateEmail(email) 
{
   var re = /\S+@\S+\.\S+/;
   return re.test(email);
}
// *****************validateEmail End******************
function adduniversity() 
{
   
   var schoolname = $("#longname").val();
   var shortname = $("#shortname").val();
   var address = $("#address").val();
   var country = $("#country").val();
   var city = $("#city").val();
   var package = $("#package").val();
   
   var username = $("#username").val();
   var firstname = $("#firstname").val();
   var lastname = $("#lastname").val();
   var email = $("#email").val();
   var confirm_email = $("#confirmemail").val();
   var password = $("#password").val();
   var confirm_password = $("#confirmpassword").val();

   var arr = [schoolname, shortname, address, country, city, package, username, firstname, lastname, email, confirm_email, password, confirm_password];
   var error1 = document.getElementsByClassName('error1');
   var tiktok=true;
   for(let i=0; i<arr.length; i++)
   {
      var wthout_space = arr[i].trim();
      if (wthout_space !='') 
      {
         error1[i].innerHTML = "";
         error1[i].style.color = "none";
      } 
      else
      {
         if(i == 3)
            error1[i].innerHTML = "Please Select Atleat One";
         else if(i == 5)
            error1[i].innerHTML = "Please Select Atleat One";
         else
            error1[i].innerHTML = "This Field Is Required";
         error1[i].style.color = "Red";
         tiktok=false;
      }
   }

   if (tiktok) 
   {
      if(validateEmail(email))
      {
         error1[9].innerHTML="";
         if (email == confirm_email) 
         {
            error1[10].innerHTML="";
            var pass= true;
         }
         else 
            error1[10].innerHTML="Confirm Email Not Match";
      }
      else
      {
         error1[9].innerHTML="Invalid Email Format";
      }
      if (pass) 
      {
         if (password == confirm_password) 
         {
            error1[12].innerHTML="";
            var jadu = true;
         } 
         else 
            error1[12].innerHTML="Confirm Password Not Match";
      } 
   }
   
   if (jadu) 
   {
      $.ajax({
         type: "GET",
         url: "<?php echo $CFG->wwwroot ?>" + "/local/dashboard/customuniversity.php",
         dataType: "json",
         data: $("#addnewuniversity").serialize(),
         async: false,
         success: function(json) 
         {
            if (json.success) {
               alert(json.msg);
               window.location.href = "<?php echo $CFG->wwwroot ?>" + "/local/dashboard/table.php";
            }
            if (json.msg1){
               document.getElementsByClassName("error1")[0].innerHTML = "University already exist";   
                  document.getElementById("longname").style.borderColor = "red";  
            }
            if (json.msg2){
               document.getElementsByClassName("error1")[6].innerHTML = "Username already exist";   
                  document.getElementById("username").style.borderColor = "red";  
            }
            if (json.msg3){
               document.getElementsByClassName("error1")[9].innerHTML = "email already exist";   
                  document.getElementById("email").style.borderColor = "red";  
            }
            if (json.msg4){
               document.getElementsByClassName("error1")[1].innerHTML = "shortname already exist";   
                  document.getElementById("shortname").style.borderColor = "red";  
            }
         }
      });
   }  
}
// *********************************Validation and Ajax End**********************************
function selectPackage(package_id) 
{
   $.post(
      "<?php echo $CFG->wwwroot ?>" + "/local/createpackage/get_package_info.php",
      {id:package_id},
      function(json) 
      {
         $("#package_info").html(json);
      }
   );
}
   </script>
   <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
   <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</body>

</html>
<?php echo $OUTPUT->footer(); ?>