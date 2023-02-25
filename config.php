<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost';
$CFG->dbname    = 'elearngroup_moodlelms';
$CFG->dbuser    = 'elearngroup_vetmoodle';
$CFG->dbpass    = 'YfQqb4)-=xcv';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => '',
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_unicode_ci',
);

$CFG->dbsessions='0';
//$CFG->debug = 38911;
//$CFG->debugdisplay = 1;
$db_host=$CFG->dbhost;
$db_user=$CFG->dbuser;
$db_pass=$CFG->dbpass;
$db_name=$CFG->dbname;

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
    // $CFG->wwwroot   = 'http://localhost/MoodleLMS6';

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
//var_dump($CFG->wwwroot);
//die;
// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
