<?php

function local_changelogo_extend_navigation(global_navigation $navigation)
{ 
    global $CFG, $PAGE,$DB,$USER, $admin,$icon;
    $role = $DB->get_record("role_assignments",array("userid"=>$USER->id));
    $icon = new pix_icon('change', '', 'local_changelogo', array('class' => 'icon pluginicon'));
    $icon2 = new pix_icon('theme', '', 'local_changelogo', array('class' => 'icon pluginicon'));
    if($role)
    {
    if ($role->roleid ==9) {

    $navigation->add('Change University Logo',
     new moodle_url($CFG->wwwroot . '/local/changelogo/index.php'),
     navigation_node::TYPE_SYSTEM,
     null,
     'local_changelogo',
     $icon
 )->showinflatnavigation = true;

 $navigation->add('Change Theme',
     new moodle_url($CFG->wwwroot . '/local/changelogo/theme.php'),
     navigation_node::TYPE_SYSTEM,
     null,
     'local_changelogo',
     $icon2
 )->showinflatnavigation = true;
}
}
}
