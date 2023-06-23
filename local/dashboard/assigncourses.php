<?php

require_once('../../config.php');
require_once('lib.php');
  require_login();
 GLOBAL $USER,$DB;
$id = $_GET['id'];

$ID=$USER->id;
if(!$id || !is_siteadmin()){
    $url = new moodle_url("/my");
    redirect($url);
 }

$countries = $DB->get_records_sql("SELECT * FROM {course_categories} WHERE depth=2 and parent=2");
$idcat=get_child_cat(2);
$lang = $DB->get_records_sql("SELECT * FROM {course_categories} WHERE depth=3 and parent IN ($idcat)");

$Schooldata=$DB->get_record_sql("SELECT * FROM {school} WHERE id=$id");
$resource_course_id = $DB->get_record("course_categories",['idnumber'=>'resourcecat']);

$course = $DB->get_records_sql("SELECT * FROM {course} WHERE category !=0 AND  category !=$resource_course_id->id AND cb_userid = 2" );
//var_dump($course); die;
$coursearray = (array) $course;

$schoolcoursedata = $DB->get_records_sql("SELECT c.* FROM {course} c INNER JOIN {assign_course} ac on c.id=ac.course_id WHERE ac.university_id=$id");


$title = 'Assign Courses';
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('standard');

$previewnode = $PAGE->navigation->add('School Management', new moodle_url('/local/dashboard/table.php'), navigation_node::TYPE_CONTAINER);
$thingnode = $previewnode->add('Assign Course', new moodle_url('/local/dashboard/assigncourses.php',array('id'=>$id)));
$thingnode->make_active();

echo $OUTPUT->header();
?>

<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <title>Assign Course</title>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
      <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
      <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
      <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
      <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
      <style>
         .box-shadow{
         box-shadow: 0 1px 3px rgb(0 0 0 / 12%), 0 1px 2px rgb(0 0 0 / 24%);
         padding: 0px 20px 20px;
         border-radius: 8px;
         }
         .box-1{
         height: 400px;
         width: 100%;
         border-radius: 5px;
         border: 1px solid #bab4b4;
         padding: 10px;
         box-shadow: 0 1px 3px rgb(0 0 0 / 12%), 0 1px 2px rgb(0 0 0 / 24%);
         outline: 1px solid #bab4b4;
           outline-offset: 3px;
         }
         body{
            background: #f7f7f7;
            font-family: "Nunito",sans-serif;
         }
         .heading-row{  
            background: #1f34d2;
            color: #fff;
            border: 2px solid #192dbf;
            padding: 16px 0px;
            border-radius: 8px;
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
         .box-1 h5{
         font-weight: 700;
         }
         .box-heading{
            font-size: 17px;
            font-weight: bold;
         }
         .box-1 p{
            font-weight: 600;
         }
         ul{
            list-style: none;
            padding-left: 15px;
         }
         ul li a{
            color: #000;
            text-decoration: none !important;
         }
         #item, #user_list{
  width: 100%;
  height: 93%;
}
.test{
  width: 146px !important;
  margin-right: 5px !important;
}
.test2{
width: 158px !important;
}
.test, .test2{
   margin-bottom: 16px;
  background: #000;
  color: #fff;
  border-radius: 4px;
  display: inline-block;
  padding: 6px;
  border: 1px solid #fbe700;
  width:150px;
}
.test2:focus-visible{
  outline:none;
}
.test:focus-visible{
  outline:none;
}
#msg_info
{
   height: 35px;
   *background: red;
   padding: 5px 10px;
   font-weight: bold;
}
#add
{
   color: green;
}
#msg
{
   color: red;
}
</style>
</head>

<body>

<!-- Modal -->

<div id="msg_info">
   <span id="add"></span>&nbsp;&nbsp;&nbsp;&nbsp;
   <span id="msg"></span>
</div>

<!-- Modal -->
<div class="modal fade" id="langmodal" tabindex="-1" role="dialog" aria-labelledby="langModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#1d1d1b; border:1px solid #ffe500;">
        <h5 class="modal-title" id="exampleModalLabel" style="color:#fff;"><b>Language Filter</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" style="color:#fff;">&times;</span>
        </button>
      </div>

      <div class="modal-body">
            <form id="language" method="post">
                  <input type="hidden" name="schoolid" id="schoolid" value="<?php echo $id; ?>">
                  <div class="form-group row">
                     <label for="label" class="col-md-3">Language</label>
                      <select name="languagefilter" id="languagefilter">
                     <option value="">Select a language...</option>
                        <?php foreach($lang as $langs){ ?>
                        <option value="<?php echo $langs->id; ?>"><?php echo $langs->name; ?></option>
                     <?php } ?>
                      </select>
                  </div>
                  <a href="#" class="button mb-1 text-center lg-filter" onclick="countryfilter();">Filter</a>
                  <a href="#" class="button mt-1 text-center" data-dismiss="modal">Cancel</a>
               </form>
      </div>
    </div>
  </div>
</div>

<form id="addcourseform" method="post">

      <div class="container">
         <div class="row mt-4">
            <div class="col-md-12 m-auto box-shadow bg-white">
               <div class="row mb-4 heading-row">
                  <div class="col-md-12">
                     <h5 class="mb-0 text-white"><?php echo $Schooldata->name; ?></h5>

                  </div>
               </div>
               <div class="row mb-1">
                 <div class="col-md-12 pr-0 d-flex">
                   <div class="country">
                   <p class="mb-0 "><b>Country</b></p>
                   <input class="test" value="" readonly/>
                  </div>
                 
                    <div class="country2">  
                   <p class="mb-0"><b>Language  <i class="fa fa-times"  onclick="closelanguage();"aria-hidden="true"></i></b></p>
                   <input class="test2" value="" readonly/>
                        </div>
                 </div>
                  
               </div>
               <div class="row">
                  <div class="col-md-4">
                     <div class="box-1">
                        <h6 class="box-heading">Potential Courses</h6>
                           <select id="user_list" name="categorylist[]" multiple="multiple">
                           <?php foreach($course as $cou){ ?>                      
                              <option value="<?php echo $cou->id; ?>"> <?php echo $cou->fullname; ?> </option>
                           <?php } ?>
                           </select>
                     </div>
                  </div>
                  <div class="col-md-4 align-self-center">
                     <a href="#" class="button d-block w-100 mb-1 text-center" onclick="add()">Add <i class="fa fa-arrow-right  mr-3" aria-hidden="true" ></i></a>
                     <a href="#" class="button d-block w-100 mt-1 text-center" onclick="remove()"><i class="fa fa-arrow-left ml-3" aria-hidden="true"></i> Remove</a>
                  </div>
                  <div class="col-md-4">
                     <div class="box-1">
                        <input type="hidden" name="schoolid" value="<?php echo $id; ?>">
                        <h6 class="box-heading">Assigned Courses</h6>
                           <select id="item" name="courselist[]" multiple>
                           <?php foreach($schoolcoursedata as $cou){ ?>    
                           <?php
                           $val = array_search($cou->id,array_keys($coursearray));
                            if(isset($val)){ ?>
                              <option value="<?php echo $cou->id; ?>"> <?php echo $cou->fullname; ?> </option>
                           <?php } ?>            
                           <?php } ?>          
                           </select> 
                     </div>
                  </div>
               </div>

            </div>
         </div>
      </div>
</form>
<script>
  $(document).ready(function(){
       $(".country").hide();
  });
  $(document).ready(function(){
       $(".country2").hide();
  });
</script>
<script>

    function closelanguage(){
        $(".country2").hide(); 
        countryfilter();
    }

   function countryfilter(){
      var countryval = $("#country1").val();
      var countrytext = $("#country1 option:selected").text();

      var langval = $("#languagefilter").val();
      var langtext = $("#languagefilter option:selected").text();

      if(countryval && !langval){
        $(".test").val(countrytext);
        $(".country").show(); 
        $(".country2").hide(); 
        $(".test2").val('');
      }
       
      if(langval){
       $(".test2").val(langtext);
       $(".country2").show(); 
      }

                $.ajax({
                  type: "GET",
                  url: "<?php echo $CFG->wwwroot?>"+"/local/dashboard/coursefilter.php",
                  dataType: "json",
                  data:{countryval:countryval,langval:langval},                      
                  async: false,
                  success: function (json) {
                    if(json.success){
                            $("#user_list").html(json.html);
                            if(countryval){
                              $("#languagefilter").html(json.language);   
                            }
                            
                            $(".modal,.modal-backdrop").hide();
                           // $("#country1").val('');
                           $("#languagefilter").val('');
                    $("#item option").each(function(){
                        var value = $(this).val();
                        $("#user_list option[value='"+$(this).val()+"']").remove(); 
                    });

                    }
                  }
            });
   }


       function addcourse(){
        $("#item option").prop('selected', true); 

              $.ajax({
                  type: "POST",
                  url: "<?php echo $CFG->wwwroot?>"+"/local/dashboard/schoolcourse.php",
                  dataType: "json",
                  data:$("#addcourseform").serialize(),                          
                  async: false,
                  success: function (json) {
                    if(json.success){
                      alert(json.msg);
                       window.location.reload();                     
                    }
                    else
                    {
                      
                                         
                    }
                  }
            });
    }

  k=0;

  $( document ).ready(function() {
          $("#item option").each(function(){
            var value = $(this).val();
            $("#user_list option[value='"+$(this).val()+"']").remove(); 
          });
});
function remove()
   {
        var vs = $("#item option:selected").val();
        if (vs) 
        {
            arr =[];
            $("#item option:selected").each(function () {
                $this = $(this);
                selvalues = $this.val();
                arr.push(selvalues);
            });
             $.ajax({
                    type: "GET",
                    url: "<?php echo $CFG->wwwroot?>" + "/local/dashboard/remove_course.php?uid= <?php echo $id; ?>",
                    dataType: "json",
                    data: {c_id:arr},
                    async: false,
                    success: function(json) {
                        if (json.success) {
                            alert(json.msg);
                            window.location.reload();
                        } 
                    }
                });
        } 
        else
        {
            alert("Please Select First");
        } 
    }

//script for adding category list in option while add new
var count = 0;
function add()
{
   var vs = $("#user_list option:selected").val();
   if (vs) 
   {
      arr =[];
      $("#user_list option:selected").each(function () {
            $this = $(this);
            selvalues = $this.val();
            arr.push(selvalues);
      });
         $.ajax({
               type: "POST",
               url: "<?php echo $CFG->wwwroot?>" + "/local/dashboard/add_course.php?uni_id=<?php echo $id ?>",
               dataType: "json",
               data: {course_id:arr},
               async: false,
               success: function(json) {
                  if (json.success) {
                        alert(json.msg);
                        window.location.reload();
                  }
                  else
                  {
                     if (json.add && json.msg) 
                     {
                        alert(json.add+" After "+json.msg);
                     } 
                     else 
                     {
                        //alert("Courses Assign Limit Exeed");
                        alert(json.add+" After "+json.msg);
                     }
                     //window.location.reload();
                  } 
               }
            });
   } 
   else
   {
      alert("Please Select First");
   }
}
   </script>
   </body>

</html>
<?php  echo $OUTPUT->footer();?>
