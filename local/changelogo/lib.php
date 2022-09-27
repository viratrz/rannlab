<?php

function local_changelogo_extend_navigation($navigation)
{ 
    global $CFG, $PAGE,$DB,$USER, $admin,$icon;
    $role = $DB->get_record("role_assignments",array("userid"=>$USER->id));
    $icon = new pix_icon('change', '', 'local_changelogo', array('class' => 'icon pluginicon'));
 
    if ($role->roleid==9) {

    $navigation->add('Change University Logo',
     new moodle_url($CFG->wwwroot . '/local/changelogo/index.php'),
     navigation_node::TYPE_SYSTEM,
     null,
     'local_changelogo',
     $icon
 )->showinflatnavigation = true;
}
}
