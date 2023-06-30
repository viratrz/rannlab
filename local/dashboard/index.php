<?php

require_once('../../config.php');
require_once('lib.php');
require_login();


global $USER, $DB;

if (!is_siteadmin()) {
   redirect(new moodle_url("/my"));
}
$countries = get_string_manager()->get_list_of_countries(true);
$title = 'Create New RTO';
$pagetitle = $title;
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('standard');
?>
<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
   <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
   
   
   <title>Create New University </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.8.1/css/bootstrap-select.css">
   <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.8.1/js/bootstrap-select.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

   

   <style>
        .bootstrap-select .dropdown-toggle .filter-option{
        background-color: white !important;
        border: 1px solid gray !important;
        border-radius: 5px !important;
        height: 34px !important;
        }
    .show>.btn-light.dropdown-toggle {
         background-color: white !important;
      }
   
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
         
         color: #fff;
         text-decoration: none !important;
         border-radius: 4px;
         border: 1px solid #ffe500;
         font-weight: 600;*/
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
            right: 0px;
            top: 35%;
            transform: translate(-50%, -80%);
            z-index: 999;
        }
      label {
         font-weight: 700;
      }

      .button:hover {
        /* color: #000;
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
         background: #1f34d1;
         color: #fff;
         border: 2px solid #04169d;
         padding: 15px 6px;
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
      input, select
      {
        min-height: 35px !important;
        max-height: 35px !important;
    }
    #loader
    {
      display: none;
    }
    #output
    {
      max-height: 50px !important;
    }
    .btn-default
    {
      background-color: white !important;
      color:  black !important;
      border: 1px solid gray !important;
    }
    #courses_msg{
      color: red;
      margin-left: 25%;
    }

    *:focus, *:active, input:active, input:focus, a:active, a:focus, button:active, button:focus, .form-control:focus {
      border-color: #6c757d !important;
    }
    .form-group{
   font-family: "Nunito",sans-serif;
   }
    .iti--separate-dial-code .iti__selected-flag {
    background-color: transparent !important;
    }
    
    
    .iti--allow-dropdown .iti__flag-container:hover .iti__selected-flag {
    background-color: transparent !important;
    }
   
   </style>
</head>

<body>
   <?php echo $OUTPUT->header(); ?>
   
   
   <div class="container">
      <div class="row">
         <div class="col-md-12 px-0">
            <div class="heading mb-3 heading-row">
               <h5 style="color: red;" class="px-2 mb-0" ><b style="color: white;">Create New RTO</b></h5>
            </div>
         </div>
         <div class="col-md-12 m-auto box-shadow bg-white p-4">
            <form action="<?php echo $CFG->wwwroot ?>/local/dashboard/logo_courses.php" id="addnewuniversity" method="post" enctype='multipart/form-data'>
               <!--<div class="form-group row">
                  <label for="label" class="col-md-3">Long Name <span class="err"> *</span> </label>
                  <input type="text" class="form-control col-md-9 err focusError" id="longname" placeholder="Enter Long Name" name="longname" onkeyup="smallOnly(this.value)" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div> -->

              <div class="form-group row">
                  <label for="label" class="col-md-3">Long Name<span class="err"> *</span></label> 
                  <input type="text" class="form-control col-md-9 focusError" id="longname" placeholder="Enter Name" name="longname" onblur="allLetter1(this)" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0" id="longname_msg"></span>
               </div>

               <div class="form-group row">
                  <label for="label" class="col-md-3">Short Name <span class="err"> *</span></label>
                  <input type="text" class="form-control col-md-9 focusError" id="shortname" placeholder="Enter Short Name" name="shortname" onblur="allLetter1(this)" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0" id="shortname_msg"></span>
               </div>
               
               <div class="form-group row">
                  <label for="label" class="col-md-3">Client ID <span class="err"> *</span></label>
                  <input type="text" class="form-control col-md-9 focusError" id="client" placeholder="Enter Client ID" name="client_id" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
              <div class="form-group row">
                  <label for="label" class="col-md-3">RTO Code <span class="err"> *</span></label>
                  <input type="text" class="form-control col-md-9 focusError" maxlength="15" id="rto" placeholder="Enter RTO Code" name="rto_code" onkeyup="numberOnly8(this.value)"  required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Address <span class="err">*</span></label>
                  <textarea class="form-control col-md-9 focusError" rows="5" id="address" name="address" required></textarea>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>

               <div class="form-group row">
                  <label for="label" class="col-md-3">Country <span class="err"> *</span></label>
                  <select class="form-control col-md-4 focusError" id="country" name="country" required>
                     <option value="">Select a country...</option>
                     <?php foreach ($countries as $key => $country) { ?>
                        <option value="<?php echo $country; ?>"><?php echo $country; ?></option>
                     <?php } ?>
                  </select>
                  <div class="col-md-4"><span class="error1 pl-0"></span> </div>
                       
               </div>
                <div class="form-group row">
                  <label for="label" class="col-md-3">City/Town <span class="err">*</span> </label>
                  <input type="text" class="form-control col-md-9 focusError" id="city" placeholder="Enter City/Town" onblur="allLetter(this)" title="Minimum Three letter Require In City/Town" name="city" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0" id="city_msg"></span>
               </div>
               

               <div class="form-group row">
                  <label for="label" class="col-md-3">Enter Domain<span class="err"> *</span> </label>
                  <input type="text" class="form-control col-md-4 err focusError" id="domain" placeholder="Enter domain" name="domain" onkeyup="smallOnly(this.value)" required>  &nbsp;.uat.elearngroup.com.au
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>

               <?php 
                  $all_packages = $DB->get_records_sql("SELECT * FROM {package}");
                  $category11 = $DB->get_records_sql("SELECT * FROM {course_categories}");
                  $resource_course_id = $DB->get_record("course_categories",['idnumber'=>'resourcecat']);
                  $all_courses = $DB->get_records_sql("SELECT * FROM {course} WHERE category !=0 AND category !=$resource_course_id->id LIMIT 5 ");
               


               ?>

               <div class="form-group row">
                  <label for="label" class="col-md-3">Select Package <span class="err">*</span> </label>
                  <select name="package" class="col-md-4 focusError" id="package" onchange="selectPackage(this.value)">
                     <option value="">Select package</option>
                     <?php foreach($all_packages as $package){?>
                     <option value="<?php echo $package->id; ?>"><?php echo $package->package_value; ?></option>
                     <?php } ?>
                  </select>
                  <div class="col-md-4"><span class="error1 pl-0"></span></div>
               <span id="package_info"> </span>

               </div>
                        
               <div class="heading mb-3">
                  <h5 class="text-primary font-weight-bold" style="padding: 10px; color: white !important; background-color: #2441e7; border-radius: 5px; margin: 0 -13px; margin-bottom: 30px;">Enter RTO Super Admin Details</h5>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">User name <span class="err">*</span></label>
                  <input type="text" class="form-control col-md-9 focusError" id="username" placeholder="Enter User Name"  name="username" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">First Name <span class="err">*</span></label>
                  <input type="text" class="form-control col-md-9 focusError" id="firstname" placeholder="Enter First Name" onblur="allLetter(this)" name="firstname" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0" id="firstname_msg"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Last Name <span class="err">*</span></label>
                  <input type="text" class="form-control col-md-9 focusError" id="lastname" placeholder="Enter Last Name" onblur="allLetter(this)" name="lastname" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0" id="lastname_msg"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Phone Number <span class="err">*</span></label>
                  <input type="text" class="form-control col-md-12 focusError" id="phone" placeholder="Enter Phone Number" name="phone_no" onkeyup="numberOnly(this.value)" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Email-Id <span class="err">*</span></label>
                  <input type="email" class="form-control col-md-9 focusError" id="email" placeholder="Enter Email-Id" name="email" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0"></span>
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
                  <input type="text" style="width: 100%;" class="form-control focusError" placeholder="Enter Password" id="password" name="password" value="" onkeyup="checksPassword(this.value)"/>
                     <!-- <span id="msg" style="margin-left: -540px;"></span> -->
                     <i class="fa fa-eye-slash eye" aria-hidden="true" onclick="showPassword(1)"></i>
                     <span class="error_message spassword_error" style="display: none; color: red;" id="pasward">Enter minimum 8 chars with atleast 1 number, lower, upper &amp; special(@#$%&!-_&amp;) char.</span>
                     <span class="error1 col-md-8 pl-0 w-75"></span>
                  </div>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Confirm Password <span class="err">*</span></label>
                  <div class="calendar col-md-9 p-0">
                     <input type="password" class="form-control focusError" style="width: 100%;" placeholder="Confirm Password" id="confirmpassword" name="confirmpassword" value="">
                     <i class="fa fa-eye-slash eye" aria-hidden="true" onclick="showPassword()"></i>
                     <span class="error1 col-md-8 pl-0"></span>
                  </div>
               </div>
               
               <div class="form-group row">
                  <label for="label" class="col-md-3">Select Category <span class="err"></span> </label>
                  <select class=" col-md-4 pl-0 " id="category" name="category"  style="BACKGROUND-COLOR: white !important; border: 1px solid #8f959e !important; border-radius: 5px !important;" onchange="selectedCategory(this.value)">
                     <option value="0">Select Category</option>  
                     <?php foreach($category11 as $category1){?>
                        <option value="<?php echo $category1->id; ?>"><?php echo $category1->name; ?></option>
                     <?php } ?>
                     
                    
                     
                  </select>
                  <span id="category_msg"> </span>
               </div>

               <div class="form-group row">
               <label for="label" class="col-md-3">Select Courses <span class="err"></span> </label>
               <div class="col-md-4">
                   <input type="text" placeholder="Search course.." class="col-md-12" id="coursesearch" onkeyup="filterFunction()">
                   <select class="col-md-12 pl-0 " id="courses" name="courses[]" multiple data-live-search="true" size="30" style="height: 100%;">

                      <?php foreach($all_courses as $course){?>
                         <option value="<?php echo $course->id; ?>"><?php echo $course->fullname; ?></option>
                          <!--<option value="<?php echo $course->id; ?>" class="course-option" data-category="<?php echo $course->category; ?>"><?php echo $course->fullname; ?></option>-->
                      <?php } ?>
                   </select>
                   <span id="courses_msg"> </span>
               </div>


               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Upload RTO Logo <span class="err"></span></label>
                  <div class="calendar col-md-5 p-0"> 
                  <input type="file" name="university_logo"  id="university_logo" accept="image/*" onchange="loadFile(event)">
                  </div>
                  <div class="calendar col-md-4 p-0">
                  <img id="output"/>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="d-flex col-md-6">
                     <a href="##" class="button d-block  text-center" onclick="adduniversity();">Create New RTO</a>&nbsp;
                     <a href="<?php echo $CFG->wwwroot; ?>/local/dashboard/table.php?>" class="button d-block   text-center">Cancel</a>
                  </div>
                  <div class="justify-content-center col-md-1" id="loader">
                  <div class="spinner-border text-primary" role="status">
                     <span class="sr-only">Loading...</span>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>

<script>
   // $(document).ready(function () {
      // $('select').selectpicker();
   // });
   var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };
  
  
function checksPassword(password){
var pattern = /^.*(?=.{8,20})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%&!-_]).*$/;
if(!pattern.test(password)) {
$(".spassword_error").show();
}else
{
$(".spassword_error").hide();
}

}


  
function validatePassword(password) {
                
    // Do not show anything when the length of password is zero.
    if (password.length === 0) {
        document.getElementById("msg").innerHTML = "";
        return;
    }
    // Create an array and push all possible values that you want in password
    var matchedCase = new Array();
    matchedCase.push("[$@$!%*#?&]"); // Special Charector
    matchedCase.push("[A-Z]");      // Uppercase Alpabates
    matchedCase.push("[0-9]");      // Numbers
    matchedCase.push("[a-z]");     // Lowercase Alphabates

    // Check the conditions
    var ctr = 0;
    for (var i = 0; i < matchedCase.length; i++) {
        if (new RegExp(matchedCase[i]).test(password)) {
            ctr++;
        }
    }
    // Display it
    var color = "";
    var strength = "";
    switch (ctr) {
        case 0:
        case 1:
        case 2:
            strength = "Very Weak";
            color = "red";
            break;
        case 3:
            strength = "Medium";
            color = "orange";
            break;
        case 4:
            strength = "Strong";
            color = "green";
            break;
    }
    document.getElementById("msg").innerHTML = strength;
    document.getElementById("msg").style.color = color;
}

  



function allLetter1(inputtxt)
{ 
   var letters = /^[A-Za-z\s]*$/;      //    /^[A-Za-z]+$/;
   var msg_id = inputtxt.name;
   msg_id = msg_id+"_msg";
   if(inputtxt.value.length > 0)
   {
      if(inputtxt.value.match(letters))
      {
         $("#"+msg_id).html("");
         return true;
      }
      else
      {
         $("#"+msg_id).html("Please input alphabet characters only");
         return false;
      }
   }
}



function allLetter(inputtxt)
{ 
   var letters = /^[A-Za-z\s]*$/;                      //     /^[A-Za-z]+$/;
   var msg_id = inputtxt.name;
   msg_id = msg_id+"_msg";
   if(inputtxt.value.length > 0)
   {
      if(inputtxt.value.match(letters))
      {
         $("#"+msg_id).html("");
         return true;
      }
      else
      {
         $("#"+msg_id).html("Please input alphabet characters only");
         return false;
      }
   }
}


function numberOnly8(inputvalue) {
   var all_number1 = /^[0-9]+$/;   // /^[0-9]+$/;
   //var smallletter = /^[a-z]+$/;           // /^[a-z0-9 ]+$/;
   var erroeClass = document.getElementsByClassName('error1');
   if (inputvalue.length > 0)
   {
      if(inputvalue.match(all_number1))
     {
      erroeClass[3].innerHTML = " ";
     }
   else
     {
      erroeClass[3].innerHTML = "Only numbers allowed";
     }
   }
   else
   {
      erroeClass[3].innerHTML = " ";
   }
}

function smallOnly(inputvalue) {
   var smallletter = /^[a-z]+$/;           // /^[a-z0-9 ]+$/;
   var erroeClass = document.getElementsByClassName('error1');
   if (inputvalue.length > 0)
   {
      if(inputvalue.match(smallletter))
     {
      erroeClass[7].innerHTML = " ";
     }
   else
     {
      erroeClass[7].innerHTML = "Capital letter and special character not allow";
     }
   }
   else
   {
      erroeClass[7].innerHTML = " ";
   }
}
function numberOnly(inputvalue) {
   var all_number = /^[0-9]+$/;
   var erroeClass = document.getElementsByClassName('error1');
   if (inputvalue.length > 0)
   {
      if(inputvalue.match(all_number))
     {
      erroeClass[12].innerHTML = " ";
      num_value =true;
     }
   else
     {
      erroeClass[12].innerHTML = "This field accepted number only";
      num_value =false;
     }
   }
   else
   {
      erroeClass[12].innerHTML = " ";
   }
}



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
   // alert(num_value);
   var form = $('#addnewuniversity');
   var formData = new FormData($('#addnewuniversity')[0]);
   var select_courses = $("#courses").val();
   // console.log(select_courses);
   var schoolname = $("#longname").val();
   var shortname = $("#shortname").val();
   var client = $("#client").val();
   var rto = $("#rto").val();
   var address = $("#address").val();
   var country = $("#country").val();
   var city = $("#city").val();
   var domain = $("#domain").val();
   var package = $("#package").val();
   
   var username = $("#username").val();
   var firstname = $("#firstname").val();
   var lastname = $("#lastname").val();
   var phone = $("#phone").val();
   var email = $("#email").val();
   var confirm_email = $("#confirmemail").val();
   var password = $("#password").val();
   var confirm_password = $("#confirmpassword").val();
   var courses = $("#courses").val();
   var university_logo = $("#university_logo").val();
      // console.log(typeof(courses));
      // console.log(typeof(university_logo));
   var arr = [schoolname, shortname, client, rto, address, country, city, domain, package, username, firstname, lastname, phone, email, confirm_email, password, confirm_password];
   var arr_ids = ['longname', 'shortname', 'client', 'rto', 'address', 'country', 'city', 'domain', 'package', 'username', 'firstname', 'lastname', 'phone', 'email', 'confirmemail', 'password', 'confirmpassword'];
   var error1 = document.getElementsByClassName('error1');
   var check_true=true;
   var focusError = document.getElementsByClassName('focusError');
   
   for(let i=0; i<arr.length; i++)
   {
      var without_space = arr[i].trim();
      if (without_space !='') 
      {
         error1[i].innerHTML = "";
         error1[i].style.color = "none";
         focusError[i].style.border = "1px solid rgba(0,0,0,.1)";
      } 
      else
      {
         if(i == 5)
            error1[i].innerHTML = "Please Select One";
         else if(i == 8)
            error1[i].innerHTML = "Please Select One";
         else
         {
            error1[i].innerHTML = "This Field Is Required";
         }
         error1[i].style.color = "red";
         focusError[i].style.border = "1px solid red";
         check_true=false;
      }
   }

   for(let f=0; f<arr.length; f++)
   {
      var remove_space = arr[f].trim();
      if (remove_space =='') 
      {
         document.getElementById(arr_ids[f]).focus();
         break;
      }
   }
   // if(check_true)
   // {
   //    var d=$('#courses').val();
   //    console.log(d);
   //    alert("ok");
   // }
   var all_number = /^[0-9]+$/;
   if (phone.match(all_number)) {
      if (phone.length == 10) {
         error1[12].innerHTML = "";
         check_true =true;
      }
      else
      {
         error1[12].innerHTML = "Number length should be 10";
         check_true =false;
      }
   }
   else{
      error1[12].innerHTML = "This field accepted number only";
      check_true =false;
   }

   /* var all_number1 = /^[0-9]+$/;
   if (rto.match(all_number1)) {
      if (rto.length == 8) {
         error1[3].innerHTML = "";
         check_true =true;
      }
      else
      {
         error1[3].innerHTML = "Number length should be 8";
         check_true =false;
      }
   }
   else{
      error1[3].innerHTML = "This field accepted number only";
      check_true =false;
   }
*/



   if (check_true) 
   {
      if(validateEmail(email))
      {
         error1[13].innerHTML="";
         if (email == confirm_email) 
         {
            error1[14].innerHTML="";
            var pass= true;
         }
         else 
         {
            error1[14].innerHTML="Confirm Email Not Match";
            document.getElementById("confirmemail").focus();
         }
      }
      else
      {
         error1[13].innerHTML="Invalid Email Format";
         document.getElementById("email").focus();
      }
      if (pass) 
      {
         if (password == confirm_password) 
         {
            error1[16].innerHTML="";
            var all_true = true;
         } 
         else 
         {
            error1[16].innerHTML="Confirm Password Not Match";
            document.getElementById("confirmpassword").focus();
         }
      } 
   }
   
   if (all_true) 
   // if (true) 
   {
      $.ajax({
         type: "POST",
         url: "<?php echo $CFG->wwwroot ?>" + "/local/dashboard/customuniversity.php",
         dataType: "json",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
         // data: $("#addnewuniversity").serialize(),
         beforeSend: function(){
         $("#loader").show();
         },
         complete:function(data){
         $("#loader").hide();
         },
         success: function(json) 
         {
            if (json.success) {
               // alert(json.msg);
               window.location.href = "<?php echo $CFG->wwwroot ?>" + "/local/dashboard/table.php?msg="+json.msg;
            }
            if (json.msg1){
               error1[0].innerHTML = json.msg1;    
            }
            if (json.msg4){
               error1[1].innerHTML = json.msg4;    
            }
            if (json.client_id_msg){
               error1[2].innerHTML = json.client_id_msg;    
            }
            if (json.rto_code_msg){
               error1[3].innerHTML = json.rto_code_msg;    
            }
            if (json.unique){
               error1[7].innerHTML = json.unique;     
            }
            if (json.msg2){
               error1[9].innerHTML = json.msg2;    
            }
            if (json.msg3){
               error1[12].innerHTML = json.msg3;   
            }
            if (json.max_course){
               courses_msg.innerHTML = json.max_course;   
            }
            else
            {
               courses_msg.innerHTML = "";   
            }
         }
      });
   }  
}
// *********************************Validation and Ajax End**********************************
function selectPackage(package_id) 
{
   $.ajax({
      type: "POST",
      url: "<?php echo $CFG->wwwroot ?>" + "/local/createpackage/get_package_info.php",
      data: {id:package_id},
      dataType: "json",
      success: function(json) 
      {
         // console.log(json['course']);
         $("#package_info").html(json['html']);
      }
});
}
function selectedCategory(categoryid) 
{
    $('#selectedcategoryid').val(categoryid);
    $.ajax({
      type: "POST",
      url: "<?php echo $CFG->wwwroot ?>" + "/local/createadmin/getcoursesbycategory.php",
      data: {id:categoryid},
      dataType: "json",
      success: function(json) 
      {
         $("#courses").empty();
         console.log(json['courses']);
         var select = document.getElementById("courses");
         for(key in json['courses'] ){
             console.log(json['courses'][key].fullname);
             $('#courses').append('<option value="'+key+'">'+json['courses'][key].fullname+'</option>');

             //var option = document.createElement("option");
             //option.value = key;
             //option.text = json['courses'][key].fullname;
             //select.appendChild(option);
             
         }
         $("#courses").selectpicker("refresh");
         
         //$("#package_info").html(json['html']);
      }
});
}
   </script>
<script>
     
     var phone_number = window.intlTelInput(document.querySelector("#phone"), {
  separateDialCode: true,
  preferredCountries:["au"],
  hiddenInput: "full",
  utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
});

$("form").submit(function() {
  var full_number = phone_number.getNumber(intlTelInputUtils.numberFormat.E164);
$("input[name='phone_number[full]'").val(full_number);
  alert(full_number)
  
});

     function filterFunction() {
         var input, filter, ul, li, a, i;
         input = document.getElementById("myInput");
         filter = input.value.toUpperCase();
         div = document.getElementById("courses");
         a = div.getElementsByTagName("option");
         for (i = 0; i < a.length; i++) {
             txtValue = a[i].textContent || a[i].innerText;
             if (txtValue.toUpperCase().indexOf(filter) > -1) {
                 a[i].style.display = "";
             } else {
                 a[i].style.display = "none";
             }
         }
     }
 </script>

   
   
   
   <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
   <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
   
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
</body>

</html>
<?php echo $OUTPUT->footer(); ?>
