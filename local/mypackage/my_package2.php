<?php
    require_once('../../config.php');
    require_once('lib.php');
    require_login();

global $USER, $DB, $CFG, $SESSION;

$uni_id =$SESSION->university_id;

    $my_package_id = $DB->get_record_sql("SELECT *, DATE(sub_date) AS date_of_sub FROM {admin_subscription} WHERE university_id=$uni_id");
    $my_package = $DB->get_record_sql("SELECT * FROM {package} WHERE id= $my_package_id->package_id");
    $upgrade_plans = $DB->get_records_sql("SELECT * FROM {package} WHERE package_value > $my_package->package_value");
    $downgrade_plans = $DB->get_records_sql("SELECT * FROM {package} WHERE package_value < $my_package->package_value");
    // var_dump($my_package,$upgrade_plans);
    // die;
    $current_bill = $my_package_id->current_bill;
    // echo date("Y/m/d");
    $date1=date_create(date("Y/m/d"));
    $date2=date_create($my_package_id->end_date);
    $diff=date_diff($date1,$date2);
    $diff=date_diff($date1,$date2);
    $total_days_in_current_month = date('t');
    $left_days = $diff->days;
    $left_days = $left_days-1;
    $used_days = $total_days_in_current_month - $left_days;

    $one_day_price = (int)($my_package->package_value / $total_days_in_current_month);
    $used_money = $one_day_price * $used_days;
    $left_money = $my_package->package_value - $used_money;
    // $left_days = $diff->d;
    // $total_days_in_current_onth = date('t');
    // $one_day_price = (int)($my_package->package_value / $total_days_in_current_onth);
    // $left_money = $one_day_price * $left_days;
    // var_dump($my_package_id);
    // var_dump($CFG->wwwroot);
    // echo "<br>";
    // var_dump($my_package);
    // echo "<br>";
    // var_dump($one_day_price);
    // echo "<br>";
    // var_dump($left_money);
    // die;
    $title = 'My Current Package';
    $pagetitle = $title;
    $PAGE->set_title($title);
    $PAGE->set_heading($title);
    $PAGE->set_pagelayout('standard');

?>
<?php echo $OUTPUT->header(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <title>My Current Package</title>
    <style>
        #table
        {
            *padding: 0 5% 0 5%;
        }
        #del_package
        {
            background-color: red;
            color: white;
        }
        #upgrade_plan
        {
            display: none;
        }
    </style>
</head>
<body> 
    <span id="del_package" ></span>
    <div class="border rounded pb-0">
        <div class="bg-dark py-1 px-2 rounded">
            <h4 class="text-white">My Current Package</h4>
        </div>
        <div></div>
        <div id="table" class="shadow">
            <table class="table table-hover mb-0 rounded">
                <thead>
                <tr>
                    <th scope="col">Package Value</th>
                    <th scope="col">Number of Users</th>
                    <th scope="col">Number of Course</th>
                    <th scope="col">Suscribed Date</th>
                    <th scope="col">Suscription End</th>
                    <th scope="col" >Current Bill</th>
                    <th scope="col" ></th>
                </tr>
                </thead>
                <tbody>
                <tr id="del<?php echo $my_package->id; ?>">
                    <td><?php echo $my_package->package_value; ?></td>
                    <td><?php echo $my_package->num_of_user; ?></td>
                    <td><?php echo $my_package->num_of_course; ?></td>
                    <td><span><?php echo $my_package_id->date_of_sub; ?></span></td>
                    <td><span><?php echo $my_package_id->end_date; ?></span></td>
                    <td><span><?php echo $current_bill ?></span></td>
                    <td ><button class="btn bg-info text-white px-1 py-1" onclick="showHideUpgradePackagePlan(1)">View Upgrade Plan</button></td>
                    
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Upgrade Plan Dive -->
    <div class="border rounded pb-0 mt-4" id="upgrade_plan">
    <button type="button" class="close p-2 text-danger" onclick="showHideUpgradePackagePlan(2)">&times;</button>
        <div class="bg-info py-1 px-2 rounded">
            <h4 class="text-white">Upgrade Plan</h4>
        </div>
        <div id="table" class="shadow">
            <table class="table table-hover mb-0 rounded">
                <thead>
                <tr>
                    <th scope="col">Package Value</th>
                    <th scope="col">Number of Users</th>
                    <th scope="col">Number of Course</th>
                    <th scope="col">Current Price</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($upgrade_plans as $upgrade_to_plan) {?>
                <tr id="upgrade<?php echo $upgrade_to_plan->id; ?>">
                    <td><?php echo $upgrade_to_plan->package_value; ?></td>
                    <td><?php echo $upgrade_to_plan->num_of_user; ?></td>
                    <td><?php echo $upgrade_to_plan->num_of_course; ?></td>
                    <td><?php echo $upgrade_to_plan->package_value-$left_money; ?></td>
                    <!-- <td><a class="btn bg-primary text-white px-1 py-0" href="<?php echo $upgrade_to_plan->id,',',$upgrade_to_plan->package_value-$left_money; ?>)">Upgrade</button></td> -->
                    <td><a href="<?php $CFG->wwwroot; ?>" class="btn bg-primary text-white px-1 py-1" >Upgrade</a></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<script>
    function showHideUpgradePackagePlan(plan) {
        if (plan == 1) {
            upgrade_plan.style.display="block";
        } else {
            upgrade_plan.style.display="none";
        }
    }

    // function upgradePackagePlan(pac_id, price) {
    //     console.log(pac_id, price);
    // }

    function deletePackage(package_id) 
   {
      del="#del"+package_id;
      if (confirm("Are You Sure Do You Want To Delete This Package?")) 
      {
        $.post(
         "<?php echo $CFG->wwwroot ?>" + "/local/createpackage/delete_package.php",
         {id:package_id},
         function(json) 
               {
                  $("#del_package").html(json);  
                  $('#del_package').css("padding","2px 5px");
                  $(del).fadeOut();              
               }
        );
      }
    
   }
</script>
<?php echo $OUTPUT->footer(); ?>

<!-- Next -->
<?php
require_once('../../config.php');
require_once('lib.php');
date_default_timezone_set('Asia/Kolkata');
require_login();

global $USER, $DB, $CFG, $SESSION;
// echo date("Y/m/d");
// die;
$uni_id =$SESSION->university_id;

    $my_package_id = $DB->get_record_sql("SELECT *, DATE(sub_date) AS date_of_sub FROM {admin_subscription} WHERE university_id=$uni_id");
    $my_package = $DB->get_record_sql("SELECT * FROM {package} WHERE id= $my_package_id->package_id");
    $upgrade_plans = $DB->get_records_sql("SELECT * FROM {package} WHERE package_value > $my_package->package_value");
    $downgrade_plans = $DB->get_records_sql("SELECT * FROM {package} WHERE package_value < $my_package->package_value");
    // var_dump($my_package,$upgrade_plans);
    // die;
    $current_bill = $my_package_id->current_bill;
    $date1=date_create(date("Y/m/d"));
    $date2=date_create($my_package_id->end_date);
    $diff=date_diff($date1,$date2);
    $total_days_in_current_month = date('t');
    $left_days = $diff->days;
    $left_days = $left_days-1;
    $used_days = $total_days_in_current_month - $left_days;
    
    $one_day_price = (int)($my_package->package_value / $total_days_in_current_month);
    $used_money = $one_day_price * $used_days;
    $left_money = $my_package->package_value - $used_money;
    // echo "<pre>";
    // var_dump($one_day_price ,$left_days,$used_days,$used_money,$left_money);
    // echo "</pre>";
    // die;
    if (!((int)$current_bill)) {
        $current_bill = "Bill Not Genrated Yet";
    }
    
    $title = 'My Current Package';
    $pagetitle = $title;
    $PAGE->set_title($title);
    $PAGE->set_heading($title);
    $PAGE->set_pagelayout('standard');
    echo $OUTPUT->header();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous"> -->
    <title>Package and Payment Details</title>
    <style>
        #upgrade_plan
        {
            display: none;
        }
        #downgrade_plan
        {
            display: none;
        }

    </style>
    </head>
    <body> 
    <span id="del_package" ></span>
    <div class="border rounded pb-0">
        <div class="bg-dark rounded">
            <h4 class="text-white py-2">Package and Payment Details</h4>
        </div>
        <div></div>
        <div id="table" class="shadow">
            <table class="table table-hover mb-0 rounded">
                <thead>
                <tr>
                    <th scope="col">Package Name</th>
                    <th scope="col">Package Value</th>
                    <th scope="col">Number of Users</th>
                    <th scope="col">Number of Course</th>
                    <!-- <th scope="col">Suscribed Date</th> -->
                    <!-- <th scope="col">Suscription End</th> -->
                    <th scope="col" ></th>
                    <th scope="col" ></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($downgrade_plans as $downgrade_to_plan) {?>
                <tr id="upgrade<?php echo $upgrade_to_plan->id; ?>">
                <td><?php if($downgrade_plans){ echo "Downgrade";} ?></td>
                    <td><?php echo $downgrade_to_plan->package_value; ?></td>
                    <td><?php echo $downgrade_to_plan->num_of_user; ?></td>
                    <td><?php echo $downgrade_to_plan->num_of_course; ?></td>
                    <td></td>
                    <!-- <td><?php echo $downgrade_to_plan->package_value-$left_money; ?></td> -->
                    <!-- <td><a class="btn bg-primary text-white px-1 py-0" href="<?php echo $downgrade_to_plan->id,',',$downgrade_to_plan->package_value-$left_money; ?>)">Upgrade</button></td> -->
                    <td><a href="<?php echo $CFG->wwwroot.'/local/dashboard/upgrade_down_plan.php?id='.$downgrade_to_plan->id.'&amp;uni_id='.$uni_id.'';?>" class="btn text-white px-1 py-1" >Down Plan</a></td>
                </tr>
                <?php } ?>
                <tr id="del<?php echo $my_package->id; ?>" style="background: #00ffb84a">
                    <td><?php if($my_package){ echo "Current";} ?></td>
                    <td><?php echo $my_package->package_value; ?></td>
                    <td><?php echo $my_package->num_of_user; ?></td>
                    <td><?php echo $my_package->num_of_course; ?></td>
                    <!-- <td><span><?php echo $my_package_id->date_of_sub; ?></span></td> -->
                    <!-- <td><span><?php echo $my_package_id->end_date; ?></span></td> -->
                    <td><span><?php echo $current_bill; ?></span></td>
                    <td ><?php if(!(is_string($current_bill))){ ?><button class="btn bg-info text-white px-2 py-0">Pay</button><?php }else{ echo "Current Plan";} ?></td>
                </tr>
                <?php foreach ($upgrade_plans as $upgrade_to_plan) {?>
                <tr id="upgrade<?php echo $upgrade_to_plan->id; ?>">
                    <td><?php if($upgrade_plans){ echo "Upgrade";} ?></td>
                    <td><?php echo $upgrade_to_plan->package_value; ?></td>
                    <td><?php echo $upgrade_to_plan->num_of_user; ?></td>
                    <td><?php echo $upgrade_to_plan->num_of_course; ?></td>
                    <td></td>
                    <!-- <td><?php echo $upgrade_to_plan->package_value - $left_money; ?></td> -->
                    <!-- <td><a class="btn bg-primary text-white px-1 py-0" href="<?php echo $upgrade_to_plan->id,',',$upgrade_to_plan->package_value-$left_money; ?>)">Upgrade</button></td> -->
                    <td><a href="<?php echo $CFG->wwwroot.'/local/dashboard/upgrade_down_plan.php?id='.$upgrade_to_plan->id.'&amp;uni_id='.$uni_id.'';?>" class="btn bg-primary text-white py-1 px-2" >Upgrade</a></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
            
        </div>
    </div> 
    
    <div class="row mt-4 ml-0 p-0 col-md-12">
        <div class="col-md-4 bg-dark "><h5 class="text-white" style="padding-top: 10px; margin-bottom: 1px;">More Plan -></h5></div>
        <div class="col-md-4"><button class="btn btn-info text-white" onclick="showHideUpgradePackagePlan(1)">View Upgrade Plan</button></div>
        <div class="col-md-4"><button class="btn btn-info text-white" onclick="showHideDowngradePackagePlan(1)">View Downgrade Plan</button></div>
    </div>
     <!-- Upgrade Plan Dive -->
    <div class="border rounded pb-0 mt-4" id="upgrade_plan">
        <button type="button" class="close p-2 text-danger" onclick="showHideUpgradePackagePlan(2)">&times;</button>
        <?php if ($upgrade_plans) {?>
        <div class="bg-info px-2 rounded">
            <h4 class="text-white py-2">Upgrade Plan</h4>
        </div>
        <div id="table" class="shadow">
            
            <table class="table table-hover mb-0 rounded">
                <thead>
                <tr>
                    <th scope="col">Package Value</th>
                    <th scope="col">Number of Users</th>
                    <th scope="col">Number of Course</th>
                    <th scope="col">Current Price</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($upgrade_plans as $upgrade_to_plan) {?>
                <tr id="upgrade<?php echo $upgrade_to_plan->id; ?>">
                    <td><?php echo $upgrade_to_plan->package_value; ?></td>
                    <td><?php echo $upgrade_to_plan->num_of_user; ?></td>
                    <td><?php echo $upgrade_to_plan->num_of_course; ?></td>
                    <td><?php echo $upgrade_to_plan->package_value - $left_money; ?></td>
                    <!-- <td><a class="btn bg-primary text-white px-1 py-0" href="<?php echo $upgrade_to_plan->id,',',$upgrade_to_plan->package_value-$left_money; ?>)">Upgrade</button></td> -->
                    <td><a href="<?php echo $CFG->wwwroot.'/local/dashboard/upgrade_down_plan.php?id='.$upgrade_to_plan->id.'&amp;uni_id='.$uni_id.'';?>" class="btn bg-primary text-white py-1 px-2" >Upgrade</a></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <?php }else {
            echo "<h5 style='margin-bottom: 0px; padding: 10px;' class='bg-info text-white'>You are already in max plan </h5>";
        } ?>
    </div>
    
     <!-- Upgrade Plan Dive End-->
     <!-- Downgrade Plan Dive -->
    <div class="border rounded pb-0 mt-4" id="downgrade_plan">
        <button type="button" class="close p-2 text-danger" onclick="showHideDowngradePackagePlan(2)">&times;</button>
        <?php if ($downgrade_plans) {?>
        <div class="bg-info px-2 rounded">
            <h4 class="text-white py-2">Downgrade Plan</h4>
        </div>
        <div id="table" class="shadow">
            
            <table class="table table-hover mb-0 rounded">
                <thead>
                <tr>
                    <th scope="col">Package Value</th>
                    <th scope="col">Number of Users</th>
                    <th scope="col">Number of Course</th>
                    <th scope="col">Current Price</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($downgrade_plans as $downgrade_to_plan) {?>
                <tr id="upgrade<?php echo $upgrade_to_plan->id; ?>">
                    <td><?php echo $downgrade_to_plan->package_value; ?></td>
                    <td><?php echo $downgrade_to_plan->num_of_user; ?></td>
                    <td><?php echo $downgrade_to_plan->num_of_course; ?></td>
                    <td><?php echo $downgrade_to_plan->package_value-$left_money; ?></td>
                    <!-- <td><a class="btn bg-primary text-white px-1 py-0" href="<?php echo $downgrade_to_plan->id,',',$downgrade_to_plan->package_value-$left_money; ?>)">Upgrade</button></td> -->
                    <td><a href="<?php echo $CFG->wwwroot.'/local/dashboard/upgrade_down_plan.php?id='.$downgrade_to_plan->id.'&amp;uni_id='.$uni_id.'';?>" class="btn bg-primary text-white px-1 py-1" >Down Plan</a></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <?php }
    else {
            echo "<h5 style='margin-bottom: 0px; padding: 10px;'>You are already in lowest plan </h5>";
        } 
        ?>
    </div>
    
     Upgrade Plan Dive End
    </body>
    </html>

<script>
    function showHideUpgradePackagePlan(plan) {
        if (plan == 1) {
            upgrade_plan.style.display="block";
        } else {
            upgrade_plan.style.display="none";
        }
    }
    function showHideDowngradePackagePlan(plan) {
        if (plan == 1) {
            downgrade_plan.style.display="block";
        } else {
            downgrade_plan.style.display="none";
        }
    }
</script>
<?php echo $OUTPUT->footer(); ?>