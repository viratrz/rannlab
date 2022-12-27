<?php
function local_createadmin_extend_navigation(global_navigation $nav)
{

    global $CFG, $PAGE,$DB,$USER;
   
    $role = $DB->get_record("role_assignments",array("userid"=>$USER->id));
      
    $icon = new pix_icon('user', '', 'local_createadmin', array('class' => 'icon pluginicon'));
    $table_icon = new pix_icon('table', '', 'local_createadmin', array('class' => 'icon pluginicon'));
    if(!user_has_role_assignment($USER->id, 5))
    {
        $nav->add(
            'Upload User',
            new moodle_url($CFG->wwwroot . '/admin/tool/uploaduser/index.php'),
            navigation_node::TYPE_SYSTEM,
            null,
            'local_createadmin',
            $icon,
        )->showinflatnavigation = true;
    }
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
            new moodle_url($CFG->wwwroot . '/local/createadmin/custom_admin_list.php'),
            navigation_node::TYPE_SYSTEM,
            null,
            'local_createadmin',
            $icon,
        )->showinflatnavigation = true;
  
    }
    if (is_siteadmin()) {
        $nav->add(
            'RTO Summary',
            new moodle_url($CFG->wwwroot . '/local/dashboard/rto_summary.php'),
            navigation_node::TYPE_SYSTEM,
            null,
            'local_createadmin',
            $table_icon,
        )->showinflatnavigation = true;
    }
}

