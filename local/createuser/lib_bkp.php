<?php
function local_createuser_extend_navigation(global_navigation $nav)
{

    global $CFG, $PAGE,$DB,$USER;
   
    $role = $DB->get_record("role_assignments",array("userid"=>$USER->id));
      
    $icon = new pix_icon('cuser', '', 'local_createuser', array('class' => 'icon pluginicon'));
    $icon2 = new pix_icon('luser', '', 'local_createuser', array('class' => 'icon pluginicon'));

    if(user_has_role_assignment($USER->id, 9) || user_has_role_assignment($USER->id, 10))
    {
        $nav->add(
            'Create User',
            new moodle_url($CFG->wwwroot . '/local/createuser/index.php'),
            navigation_node::TYPE_SYSTEM,
            null,
            'local_createuser',
            $icon,
        )->showinflatnavigation = true;

        $nav->add(
            'Users List',
            new moodle_url($CFG->wwwroot . '/local/createuser/user_list.php'),
            navigation_node::TYPE_SYSTEM,
            null,
            'local_createuser',
            $icon2,
        )->showinflatnavigation = true;
    }

}

