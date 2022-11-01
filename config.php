<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost';
$CFG->dbname    = 'MoodleLms2';
$CFG->dbuser    = 'yatharthd';
$CFG->dbpass    = 'Yath@6849';
$CFG->prefix    = 'mdl_';
$CFG->dbsessions='0';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => '',
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_unicode_ci',
);
$db_host='localhost';
$db_user='yatharthd';
$db_pass='Yath@6849';
$db_name='MoodleLms2';

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

$maindomain="rationalmind";
if(@mysqli_num_rows($tenantdata)>0)
{
  while ($obj = $tenantdata->fetch_object()) 
  {
    $tsubdomain= $obj->domain;
    $CFG->wwwroot='http://'.$tsubdomain.'.'.$maindomain.'.in';
    
    session_start();
    $_SESSION["logo_path"] = $CFG->wwwroot.$obj->logo_path;
    $_SESSION["domain"] = $obj->domain;
    
  }

}
else
{
  $CFG->wwwroot   = 'http://rationalmind.in';
  session_start();
  $_SESSION["logo_path"] ="http://rationalmind.in/theme/image.php/mb2nl/theme/1664522056/logo-default";
}


$CFG->dataroot  = '/var/www/newmoodledata/moodledata';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;

require_once(__DIR__ . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
