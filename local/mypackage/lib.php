<?php
function local_mypackage_extend_navigation(global_navigation $nav)
{

    global $CFG, $PAGE,$DB,$USER;
      
    $icon = new pix_icon('rupee', '', 'local_mypackage', array('class' => 'icon pluginicon'));
    $role = $DB->get_record("role_assignments",array("userid"=>$USER->id));
    // var_dump($role->roleid,$USER->id);
    // die;
    if($role->roleid == 9)
    {
        $package=$nav->add(
            'My Package',
            new moodle_url($CFG->wwwroot . '/local/mypackage/my_package.php'),
            navigation_node::TYPE_SYSTEM,
            null,
            'local_mypackage',
            $icon,
        )->showinflatnavigation = true;
       
    }

}