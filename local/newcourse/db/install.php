<?php
defined('MOODLE_INTERNAL') || die();

require_once("{$CFG->libdir}/db/upgradelib.php");

function xmldb_local_newcourse_install() {
// function xmldb_local_newcourse_upgrade($oldversion) {
    global $CFG, $DB;
    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.
   
    // if ($oldversion <2022041925) {
     echo "upgrade to latest version please";

        $table = new xmldb_table('school_user');
        $field = new xmldb_field('colourr', XMLDB_TYPE_CHAR, '100', null, null, null, null, null);

    //  Conditionally launch add field email.
     if (!$dbman->field_exists($table, $field)) {
         $dbman->add_field($table, $field);
     }    
        // upgrade_plugin_savepoint(true, 2022041925, 'local', 'themechange');
 }
 return true;


