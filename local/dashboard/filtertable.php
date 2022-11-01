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

    $universities = $DB->get_records_sql("SELECT * FROM {school} AS s $wherename $wherecountry $wherecity ORDER BY id DESC");
    $table = '';
    foreach($universities as $university)
    { 
        $table.='
        <tr>
            <td>'.$university->name.'</td>
            <td>'.$university->country.'</td>
            <td>'.$university->city.'</td>
            <td><a href="#" class="p-2" onclick="editschool('.$university->id.');"><i class="fa fa-pencil" aria-hidden="true" title="Edit Seller" style="color:#000;"></i></a>
            <a href="#" class="p-2" onclick="assigncourse('.$university->id.');"><i class="fa fa-book" title="Assign Course" aria-hidden="true" style="color:#000;"></i></a>
            <a href="#" onclick="deleteUser('.$university->id.')" class="" style="padding:8px;" ><i class="fa fa-trash" title="Delete"  aria-hidden="true" style="color:red;"></i></a>
            </td>
        </tr>';
    }      

    $json = array();
    $json['success'] = true;
    $json['html'] = $table;
    echo json_encode($json);
?>