<?php
require_once('../../config.php');
require_once('lib.php');
require_login();

global $USER, $DB;

$user_id = (int)$_GET['edit_id'];
$user_info = $DB->get_record('user', ['id'=>$user_id]);
$user_role = $DB->get_record('role', ['id'=>$user_id]);
$user_role1 = $DB->get_record('role_assignments', ['userid'=>$user_id]);



$title = 'Update User';
$pagetitle = $title;
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('standard');
?>
<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <title>Update User </title>
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
        /* background: #000;
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
      *:focus, *:active, input:active, input:focus, a:active, a:focus, button:active, button:focus, .form-control:focus {
      border-color: #6c757d !important;
    }
    .form-group{
   font-family: "Nunito",sans-serif;
   }
   </style>
</head>

<body>
   <?php echo $OUTPUT->header(); 
   
   ?>
   <input type="hidden" id="userid" name="userid" value="<?php echo $user_id; ?>">
   <div class="container">
      <div class="row">
         <div class="col-md-12 px-0">
            <div class="heading mb-3 heading-row">
               <h5 style="color: red;" class="px-2 mb-0" ><b style="color: white;">Update User Details</b></h5>
            </div>
         </div>
         <div class="col-md-12 m-auto box-shadow bg-white p-4">
            <form action="/action_page.php" id="addnewAdmin" method="get">
                     
               <div class="form-group row">
                <input type="hidden" name="user_id" value="<?php echo $user_info->id ; ?>">
                  <label for="label" class="col-md-3">User name <span class="err">*</span></label>
                  <input type="text" class="form-control col-md-9" id="username" placeholder="Enter User Name" name="username" value="<?php echo $user_info->username ; ?>" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0" id="a_username"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">First Name <span class="err">*</span></label>
                  <input type="text" class="form-control col-md-9" id="firstname" placeholder="Enter First Name" name="firstname" value="<?php echo $user_info->firstname ; ?>" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Last Name <span class="err">*</span></label>
                  <input type="text" class="form-control col-md-9" id="lastname" placeholder="Enter Last Name" name="lastname" value="<?php echo $user_info->lastname ; ?>" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0"></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Email-Id <span class="err">*</span></label>
                  <input type="email" class="form-control col-md-9" id="email" placeholder="Enter Email-Id" name="email" value="<?php echo $user_info->email ; ?>" required>
                  <div class="col-md-3"></div>
                     <span class="error1 col-md-8 pl-0" id="a_email" ></span>
               </div>
               <div class="form-group row">
                  <label for="label" class="col-md-3 text-info">New Password <span class="err"></span></label>
                  <div class="col-md-9 p-0">
                     <input type="password"  class="form-control" placeholder="Enter New Password" id="password" name="password" value="<?php $user_info->password ; ?>">
                     <i class="fa fa-eye-slash eye" aria-hidden="true" onclick="showPassword(1)"></i></div>
                     <div class="col-md-3"></div>
                     <span class="errormsg2" id="pasward"></span>
                     <span class="error1 col-md-8 pl-0"></span>
               </div>

               <?php 
               $all_role = $DB->get_records_sql("SELECT * FROM {role}");
               $all_role_assign = $DB->get_records_sql("SELECT * FROM {role_assignments}");
                              
                ?>
               <div class="form-group row">
                  <label for="label" class="col-md-3">Role Of User<span class="err">*</span></label>
                  <div class="calendar col-md-3 p-0">
                     <select name="role" id="role" style="width: 100%;" class="form-control focusError">
                        <option value="">Select Role</option>
                     <?php 
                     
                        foreach($all_role as $role)
                        { 
                           if ($role->shortname === 'trainer' || $role->shortname === 'student'  || $role->shortname === 'leadtrainer'  || $role->shortname === 'subrtoadmin' || $role->shortname === '	
rtoadmin') 
                           {                          
                     ?>
                        <option value="<?php echo $role->id; ?>"><?php echo ucwords($role->name); ?></option>
                     <?php  } } ?>
                     </select>
                     <span class="errormsg2" id="roleid">   </span>                  
                  </div>
                  <div class="col-md-6 pl-2"><span class="error1 pl-0 "></span></div>

                  <div  >
                        
                     </div>

               </div>
               
               
                  <div class="d-flex">
                     <a href="#" class="button d-block  text-center" onclick="addAdmin();">Update</a> &nbsp;
                     <a href="<?php echo $CFG->wwwroot; ?>/local/createuser/user_list.php?>" class="button d-block   text-center">Cancel</a>
                  </div>
            </form>
         </div>
      </div>
   </div>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</body>
</html>

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
      var password = $("#password").val();
      var role = $("#role").val();

      var arr_val=[username, firstname, lastname, email, role];
      var err =document.getElementsByClassName("error1");
      var tiktok =true;
      for (let i = 0; i < 5; i++) 
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
            tiktok = false;
         }
      }
      
      if (tiktok) 
      {
         // if (!validateEmail(email)) {
         if (email.indexOf('@') < 0 || email.indexOf('.') < 0) 
         {
            $("#a_email").text('Email id not valid');
         }
         else 
         {
            $.ajax({
            type: "POST",
            url: "<?php echo $CFG->wwwroot.'/local/createuser/save_edit_user.php'; ?>",
            dataType: "json",
            data: $("#addnewAdmin").serialize(),
            async: false,
            success: function(json) 
            {
               if (json.success) 
               {
                  alert(json.msg);
                  window.location.href = "<?php echo $CFG->wwwroot.'/local/createuser/user_list.php'; ?>";
               } 
               if (json.username)
                  $("#a_username").html(json.username);
               if (json.email) 
                  $("#a_email").html(json.email);  
            }
         });
         }
      }
   }
</script>
<?php echo $OUTPUT->footer(); ?>