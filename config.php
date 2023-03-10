<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mariadb';  //mysqli
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost';
$CFG->dbname    = 'moodlelms';
$CFG->dbuser    = 'root';
$CFG->dbpass    = '';
$CFG->prefix    = 'mdl_';
$CFG->dbsessions='0';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => '',
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_general_ci',
);
$db_host='localhost';
$db_user='root';
$db_pass='';
$db_name='moodlelms';

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

$maindomain="localhost";
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
  //$CFG->wwwroot   = 'http://rationalmind.in';
  $CFG->wwwroot   = 'http://localhost/MoodleLMS';
  session_start();
  $_SESSION["logo_path"] ="http://rationalmind.in/theme/image.php/mb2nl/theme/1664522056/logo-default";
}


//$CFG->dataroot  = '/var/www/newmoodledata/moodledata';
$CFG->dataroot = 'C:\xampp\moodledata2';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;

require_once(__DIR__ . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
