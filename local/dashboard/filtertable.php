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
            <td style="overflow-x: auto; max-width: 300px;">
                <a  href="http://'.$university->domain.'.'.$maindomain.'" target="_blank" class="p-0">'.$university->domain.'.'.$maindomain.'</a>
            </td>
            <td>'.$university->country.'</td>
            <td>'.$university->city.'</td>
            <td style="text-align: center;"><a href="'.$CFG->wwwroot.'/local/dashboard/subcription_payment.php?un_id='.$university->id.'" class="btn btn-info py-0 px-1">View</a></td>
            <td class="px-0">
                <a href="#" class="p-2" onclick="editschool('.$university->id.');"><i class="fa fa-pencil" aria-hidden="true" title="Edit Seller" style="color:#000;"></i></a>
                <a href="#" class="p-2" onclick="assigncourse('.$university->id.');"><i class="fa fa-book" title="Assign Course" aria-hidden="true" style="color:#000;"></i></a>
                
                <a href="'.$CFG->wwwroot.'/local/dashboard/course_report.php?uni_id='.$university->id.'" class="mr-1" style="padding:2px;" title="View RTO UA Summary"><i class="fa-sharp fa-solid fa-eye"></i></a>
                <a href="'.$CFG->wwwroot.'/local/changelogo/theme.php?uni_id='.$university->id.'" class="mr-1" style="padding:2px;" title="Change Theme"><i class="fa-sharp fa-solid fa-palette"></i></a>
                <a href="#" onclick="deleteUser('.$university->id.')" class="" style="padding:2px; color: red;" ><i class="fa-solid fa-trash"></i></a>
            </td>
        </tr>';
    }      

    $json = array();
    $json['success'] = true;
    $json['html'] = $table;
    echo json_encode($json);
?>
