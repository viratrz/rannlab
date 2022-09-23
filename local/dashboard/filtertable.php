<?php
require_once('../../config.php');
require_once('lib.php');
$id      = $_GET['id'];
$value   = $_GET['value'];
GLOBAL $USER,$DB;

$wherename='';
if($id==1){
$wherename=" WHERE s.name LIKE '%".$value."%'";
}

$wherecountry='';
if($id==2){
$wherecountry=" WHERE s.country LIKE '%".$value."%'";
}

$wherecity='';
if($id==3){
$wherecity=" WHERE s.city LIKE '%".$value."%'";
}


$seller = $DB->get_records_sql("SELECT * FROM {school} AS s $wherename $wherecountry $wherecity ORDER BY id DESC");

$table = '';

foreach($seller as $slr)
{ 

              $table.='
              <tr>
                    <td>'.$slr->name.'</td>
                    <td>'.$slr->country.'</td>
                    <td>'.$slr->city.'</td>
                    <td><a href="#" class="p-2" onclick="editseller('.$slr->id.');"><i class="fa fa-pencil" aria-hidden="true" title="Edit Seller" style="color:#000;"></i></a>
                    <a href="#" class="p-2" onclick="assignSchool('.$slr->id.');"><i class="fa fa-university" aria-hidden="true" title="Assign School" style="color:#000;"></i></a>
                    </td>
                </tr>';
                 }
                


$json = array();
$json['success'] = true;
$json['html'] = $table;

echo json_encode($json);
?>