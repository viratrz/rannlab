<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost:3306';
$CFG->dbname    = 'elearngroup_vetmoodle';
$CFG->dbuser    = 'elearngroup_vetmoodle';
$CFG->dbpass    = 'YfQqb4)-=xcv';
$CFG->prefix    = 'mdl_';
$CFG->dbsessions='0';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => '',
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_unicode_ci',
);
$db_host='localhost:3306';
$db_user='elearngroup_vetmoodle';
$db_pass='YfQqb4)-=xcv';
$db_name='elearngroup_vetmoodle';

$currenturl=explode('.', @$_SERVER['HTTP_HOST']);
if($currenturl){
$tenantdomain=$currenturl[0];
}

$con = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);

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
    $CFG->wwwroot='https://'.$tsubdomain.'.'.$maindomain;
    
    session_start();
    $_SESSION["logo_path"] = $CFG->wwwroot.$obj->logo_path;
    $_SESSION["domain"] = $obj->domain;
    
  }

}
else
{

  // $CFG->wwwroot   = 'http://rationalmind.in/';
  $CFG->wwwroot   = 'https://uat.elearngroup.com.au';
  $CFG->maindomain=$maindomain;
  session_start();
  $_SESSION["logo_path"] ="https://uat.elearngroup.com.au/theme/image.php/mb2nl/theme/1664522056/logo-default";
}


// $CFG->dataroot  = __DIR__ .'/moodledata';
$CFG->dataroot  = '/home/elearngroup/moodledata';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;

require_once(__DIR__ . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
