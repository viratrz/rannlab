<?php
include("../../config.php");
require_once("$CFG->libdir/formslib.php");
$PAGE->set_url(new moodle_url('/local/changelogo/theme.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Change Theme');
$PAGE->set_pagelayout('standard');

$returnurl= new moodle_url('/');

require_login();
global $CFG, $DB,$USER;
echo $OUTPUT->header();
if (is_siteadmin()) {
  $university_id = $_GET['uni_id'];
} else {
  $university_id = $_SESSION['university_id'];
}

$preset_color = $DB->get_record("school", ['id'=>"$university_id"]);
?>
<!-- CSS only -->
 <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous"> -->
<div class="card">
  <?php if (is_siteadmin()) { if(!$preset_color->header_color){?>
  <div style="font-size:18px; height:50px; padding-top: 1%; background-color: gray; color:white; padding-left: 10px;">Theme Not Set Now</div>
  <?php }else{?>
  <div style="background-color: <?php echo $preset_color->header_color; ?>; color:white; font-size:18px; height:80px; text-align:center; padding-top: 2.5%;">Current Header Color</div>
  <?php } }?>
  <div class="row pl-3 pr-3">
  <div class="col card-body d-flex flex-column col-md-6">
    <form action="save_color.php" method="post">
      <div class="p-2">
        <input type="hidden" name="university_id" value="<?php echo $university_id; ?>">
      <h3>Choose Header Color</h3>
        <input type="color" name="h_color" id="color" value="<?php echo $preset_color->header_color; ?>">
    </div>
    <div class="p-2 mt-2">
        <h3>Choose Footer Color</h3>
        <input type="color" name="f_color" id="color" value="<?php echo $preset_color->footer_color; ?>">
        </div>
        <br>
        <input type="submit" value="Set Color" class="font-weight-bold">
    </form>
    </div>


</div>

    
  
  <?php if (is_siteadmin()) { if($preset_color->header_color){?>

  <div class="col col-md-6">
    <h4 style="text-align:center; padding: 30% 0%;"><?php echo $preset_color->name; ?></h4>
  </div>
  </div>
  <div style="background-color: <?php echo $preset_color->footer_color; ?>; color:white; font-size:18px; height:80px; text-align:center; padding-top: 2.5%;">Current Footer Color</div>
  <?php }} ?>

</div>
<?php

echo $OUTPUT->footer();
?>