<?php
require_once('../../config.php');
require_once('lib.php');

if (isset($_GET['id']) && isset($_GET['uni_id'])) 
{
    $pac_id = $_GET['id'];
    $uni_id = $_GET['uni_id'];

    global $USER, $DB, $CFG;
    date_default_timezone_set('Asia/Kolkata');
    $my_package_id = $DB->get_record_sql("SELECT *, DATE(sub_date) AS date_of_sub FROM {admin_subscription} WHERE university_id=$uni_id");
    $my_package = $DB->get_record_sql("SELECT * FROM {package} WHERE id= $my_package_id->package_id");
    $sub_date=date_create($my_package_id->sub_date);
    $end_date=date_create($my_package_id->end_date);
    $diff=date_diff($sub_date,$end_date);
    $left_days = $diff->d;
    $total_days_in_current_month = date('t');
    $used_day = ($total_days_in_current_month - $left_days);
    $one_day_price = ($my_package->package_value / $total_days_in_current_month);
    $one_day_price = round($one_day_price,2);
    $left_money = $one_day_price * $left_days;
    $genrated_bill = $used_day*$one_day_price;
    $current_bill = $my_package_id->current_bill;
    // $current_bill = $current_bill + $genrated_bill;
    // echo "Left Days =".$left_days."<br>Tatal days =".$total_days_in_current_month."<br>Used Days =".$used_day."<br>One Days Price =".$one_day_price."<br>Left Money = ".$left_money."<br> Crrent Bill =".$current_bill."<br>--------------<br>";
    $update_package = new stdClass();
    $update_package->id = $my_package_id->id;
    $update_package->package_id = $pac_id;
    $update_package->sub_date = date('Y/m/d H:i:s');
    $update_package->end_date = date('Y-m-d', strtotime('+1 month'));
    // $update_package->current_bill = $current_bill;
    purge_caches();
    $status = $DB->update_record("admin_subscription", $update_package);

    if ($status) {
        $package_up_down = new stdClass();
        $package_up_down->subscription_id = $my_package_id->id;
        $package_up_down->last_package_id = $my_package_id->package_id;
        $package_up_down->current_package_id = $pac_id;
        $package_up_down->last_package_bill = $genrated_bill;
        $package_up_down->up_down_date = date('Y/m/d');
        purge_caches();
        $DB->insert_record("package_up_down", $package_up_down);

        redirect("subcription_payment.php?un_id=$uni_id", "Your plan is upgrade successfully!",null, \core\output\notification::NOTIFY_SUCCESS);
    }
    else {
        redirect("subcription_payment.php?un_id=$uni_id", "Somethiing is wrong",null, \core\output\notification::NOTIFY_ERROR);
    }            
} 
else 
{
    redirect("subcription_payment.php?un_id=$uni_id", "Somethng went is wrong",null, \core\output\notification::NOTIFY_ERROR);
    // echo "<h5> Somethng went is wrong </h5>";
}
?>