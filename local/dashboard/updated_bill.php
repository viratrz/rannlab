<?php
require_once('../../config.php');
require_once('lib.php');
// echo date("Y/m/d");
// die;
global $USER, $DB, $CFG;
date_default_timezone_set('Asia/Kolkata');
if (($total_days_in_current_month == date("j"))) {
    $all_bill = $DB->get_records_sql("SELECT * FROM {admin_subscription}");
    $all_alter_bill = $DB->get_records_sql("SELECT * FROM {package_up_down} WHERE YEAR(up_down_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(up_down_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)");
    foreach ($all_bill as $bill) 
    {
        // var_dump($bill->university_id);
        $my_package_id = $DB->get_record_sql("SELECT *, DATE(sub_date) AS date_of_sub FROM {admin_subscription} WHERE university_id=$bill->university_id");
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
        $current_bill = $current_bill + $genrated_bill;
        echo "Left Days =".$left_days."<br>Tatal days =".$total_days_in_current_month."<br>Used Days =".$used_day."<br>One Days Price =".$one_day_price."<br>Left Money = ".$left_money."<br> Crrent Bill =".$current_bill."<br>--------------<br>";
        $update_bill = new stdClass();
        $update_bill->id = $bill->id;
        $update_bill->sub_date = date('Y/m/d H:i:s');
        $update_bill->current_bill = $current_bill;
        // purge_caches();
        // $DB->update_record("admin_subscription", $update_bill);
          
    }
}
var_dump($all_alter_bill);
?>