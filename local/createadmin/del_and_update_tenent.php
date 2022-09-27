<?php

require_once('../../config.php');

global $DB,$OUTPUT;

if(isset($_GET['delete']))
{
    $del=$_GET['delete'];
    $status=$DB->execute("DELETE FROM {tenenst} WHERE Sdno='$del'");
    if($status)
    {
        echo ("Delete Successfully");
    }
}

?>