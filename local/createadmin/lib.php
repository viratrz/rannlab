<?php
function local_createadmin_extend_navigation(global_navigation $nav)
{

    global $CFG, $PAGE,$DB,$USER;
   
    $role = $DB->get_record("role_assignments",array("userid"=>$USER->id));
      
    $icon = new pix_icon('user', '', 'local_createadmin', array('class' => 'icon pluginicon'));

    if(user_has_role_assignment($USER->id, 9))
    {
        $nav->add(
            'Create New Admin',
            new moodle_url($CFG->wwwroot . '/local/createadmin/index.php'),
            navigation_node::TYPE_SYSTEM,
            null,
            'local_createadmin',
            $icon,
        )->showinflatnavigation = true;

        $nav->add(
            'Admin List',
            new moodle_url($CFG->wwwroot . '/local/createadmin/table.php'),
            navigation_node::TYPE_SYSTEM,
            null,
            'local_createadmin',
            $icon,
        )->showinflatnavigation = true;
    }

}