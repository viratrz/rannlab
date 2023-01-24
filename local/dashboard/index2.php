<?php

require_once('../../config.php');
require_once('lib.php');
require_login();


global $USER, $DB;

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
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.8.1/css/bootstrap-select.css">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.8.1/js/bootstrap-select.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

   <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>

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
            right: 0px;
            top: 35%;
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
            <form action="<?php echo $CFG->wwwroot ?>/local/dashboard/logo_courses.php" id="addnewuniversity" method="post" enctype='multipart/form-data'>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Long Name <span class="err"> *</span> </label>
                  <input type="text" class="form-control col-md-9 err focusError" id="longname" placeholder="Enter Long Name" name="longname" onkeyup="smallOnly(this.value)" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Short Name <span class="err"> *</span></label>
                  <input type="text" class="form-control col-md-9 focusError" id="shortname" placeholder="Enter Short Name" name="shortname" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Client ID <span class="err"> *</span></label>
                  <input type="text" class="form-control col-md-9 focusError" id="client" placeholder="Enter Client ID" name="client_id" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">RTO Code <span class="err"> *</span></label>
                  <input type="text" class="form-control col-md-9 focusError" id="rto" placeholder="Enter RTO Code" name="rto_code" required>
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
                  <label for="label" class="col-md-3">Enter Domain<span class="err"> *</span></label> 
                  <input type="text" class="form-control col-md-9 focusError" id="domain" placeholder="Enter Domain" name="domain" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>

               <?php 
                  $all_packages = $DB->get_records_sql("SELECT * FROM {package}");
                  $all_courses = $DB->get_records_sql("SELECT * FROM {course} WHERE fullname != 'Resourcecourse'");
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
                  <h5 class="text-primary font-weight-bold">Enter University Super Admin Details</h5>
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
                  <input type="text" class="form-control col-md-9 focusError" id="phone" placeholder="Enter Phone Number" name="phone_no" onkeydown="numberOnly(this.value)" required>
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
                     <input type="password" style="width: 112%;" class="form-control focusError" placeholder="Enter Password" id="password" name="password" value="">
                     <i class="fa fa-eye-slash eye" aria-hidden="true" onclick="showPassword(1)"></i>
                     <span class="errormsg2" id="pasward"></span>
                     <span class="error1 col-md-8 pl-0 w-75"></span>
                  </div>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Confirm Password <span class="err">*</span></label>
                  <div class="calendar col-md-9 p-0">
                     <input type="password" class="form-control focusError" style="width: 112%;" placeholder="Confirm Password" id="confirmpassword" name="confirmpassword" value="">
                     <i class="fa fa-eye-slash eye" aria-hidden="true" onclick="showPassword()"></i>
                     <span class="error1 col-md-8 pl-0"></span>
                  </div>
               </div>
               <div class="form-group row">
               <label for="label" class="col-md-3">Select Courses <span class="err"></span> </label>
               <select class="selectpicker col-md-4 pl-0 " id="courses" name="courses[]" multiple data-live-search="true">
                  <?php foreach($all_courses as $course){?>
                     <option value="<?php echo $course->id; ?>"><?php echo $course->fullname; ?></option>
                  <?php } ?>
               </select>
               <span id="courses_msg"> </span>

               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Upload Univerty Logo <span class="err"></span></label>
                  <div class="calendar col-md-5 p-0"> 
                  <input type="file" name="university_logo"  id="university_logo" accept="image/*" onchange="loadFile(event)">
                  </div>
                  <div class="calendar col-md-4 p-0">
                  <img id="output"/>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="d-flex col-md-6">
                     <a href="##" class="button d-block  text-center" onclick="adduniversity();">Create New University</a>&nbsp;
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
function allLetter(inputtxt)
{ 
   var letters = /^[A-Za-z]+$/;
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


// function numberOnly(inputvalue) 
// {
   
//    var smallletter = /^[0-9]+$/;
//    if (inputvalue.length > 0 && inputvalue.match(smallletter))
//    {
//       phone.value = inputvalue;
//       console.log(inputvalue);
//    }
// }

function smallOnly(inputvalue) {
   var smallletter = /^[a-z0-9 ]+$/;
   var erroeClass = document.getElementsByClassName('error1');
   if (inputvalue.length > 0)
   {
      if(inputvalue.match(smallletter))
     {
      erroeClass[0].innerHTML = " ";
      // longname.style.border = "1px solid rgba(0,0,0,.1)";
     }
   else
     {
      erroeClass[0].innerHTML = "Capital letter and special character not allow";
      // longname.style.border = "1px solid red";
     }
   }
   else
   {
      erroeClass[0].innerHTML = " ";
      // longname.style.border = "1px solid rgba(0,0,0,.1)";
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
   var error1 = document.getElementsByClassName('error1');
   var check_true=true;
   var focusError = document.getElementsByClassName('focusError');
   for(let i=0; i<arr.length; i++)
   {
      var wthout_space = arr[i].trim();
      if (wthout_space !='') 
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
            error1[i].innerHTML = "This Field Is Required";
         error1[i].style.color = "red";
         focusError[i].style.border = "1px solid red";
         check_true=false;
      }
   }

   // if(check_true)
   // {
   //    var d=$('#courses').val();
   //    console.log(d);
   //    alert("ok");
   // }

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
            error1[14].innerHTML="Confirm Email Not Match";
      }
      else
      {
         error1[13].innerHTML="Invalid Email Format";
      }
      if (pass) 
      {
         if (password == confirm_password) 
         {
            error1[16].innerHTML="";
            var all_true = true;
         } 
         else 
            error1[16].innerHTML="Confirm Password Not Match";
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
   </script>
   <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
   <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</body>

</html>
<?php echo $OUTPUT->footer(); ?>