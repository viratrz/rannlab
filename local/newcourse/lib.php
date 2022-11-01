<?php

function local_newcourse_extend_navigation(global_navigation  $navigation)
{
    global $CFG, $PAGE,$DB,$USER, $admin,$icon;
        $icon = new pix_icon('course', '', 'local_newcourse', array('class' => 'icon pluginicon'));

        if (user_has_role_assignment($USER->id, 9000000000)) {
        $navigation->add('Manage Course and Categories',
         new moodle_url($CFG->wwwroot . '/course/management.php'),
         navigation_node::TYPE_SYSTEM,
         null,
         'local_newcourse',
         $icon
     )->showinflatnavigation = true;
    }
    
}


