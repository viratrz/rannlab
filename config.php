<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();
//Demo Push
$CFG->dbtype    = 'mysqli';  //mysqli
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost';
$CFG->dbname    = 'elearngroup_moodlelms';
$CFG->dbuser    = 'elearngroup_vetmoodle';
$CFG->dbpass    = '=m2$jfM%mGrz';
$CFG->prefix    = 'mdl_';
$CFG->dbsessions='0';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => '',
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_general_ci',
);
$db_host='localhost';
$db_user='elearngroup_vetmoodle';
$db_pass='=m2$jfM%mGrz';
$db_name='elearngroup_moodlelms';

$currenturl=explode('.', @$_SERVER['HTTP_HOST']);
if($currenturl){
$tenantdomain=$currenturl[0];
}

$con = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);
//Demo of the Git

if (!$con) {
  echo "Error: " . mysqli_connect_error();
  exit();
}
$query="SELECT * FROM mdl_school where domain='".$tenantdomain."'";

$tenantdata = mysqli_query($con, $query);
$maindomain="uat.elearngroup.com.au";
if(@mysqli_num_rows($tenantdata)>0)
{
  while ($obj = $tenantdata->fetch_object()) 
  {
    $tsubdomain= $obj->domain;
    $CFG->wwwroot='http://'.$tsubdomain.'.'.$maindomain;
    
    session_start();
    $_SESSION["logo_path"] = $CFG->wwwroot.$obj->logo_path;
    $_SESSION["domain"] = $obj->domain;
    
  }

}
else
{

  // $CFG->wwwroot   = 'http://rationalmind.in/MoodleLMS6';
  $CFG->wwwroot   = 'https://uat.elearngroup.com.au';
  session_start();
 $_SESSION["logo_path"] ="https://uat.elearngroup.com.au/theme/image.php/mb2nl/theme/1664522056/logo-default";
}




// $CFG->wwwroot   = 'https://moodle.elearngroup.com.au/MoodleLMS';
$CFG->dataroot  = '/home/elearngroup/moodledata2';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;

require_once(__DIR__ . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
