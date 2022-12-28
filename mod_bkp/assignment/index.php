<?php
/*63602*/

#Raju__

/*63602*/


require_once("../../config.php");

$id = required_param('id', PARAM_INT);

// Rest in peace old assignment!
redirect(new moodle_url('/mod/assign/index.php', array('id' => $id)));
