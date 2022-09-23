<?php
function local_createpackage_extend_navigation(global_navigation $nav)
{

    global $CFG, $PAGE,$DB,$USER;
      
    $icon = new pix_icon('rupee', '', 'local_createpackage', array('class' => 'icon pluginicon'));

    if(is_siteadmin())
    {
        $package=$nav->add('Manage Package');
        $package->add(
            'Create New Package',
            new moodle_url($CFG->wwwroot . '/local/createpackage/index.php'),
            navigation_node::TYPE_SYSTEM,
            null,
            'local_createpackage',
            $icon,
        )->showinflatnavigation = true;
        $package->add(
            'Package List',
            new moodle_url($CFG->wwwroot . '/local/createpackage/package_list.php'),
            navigation_node::TYPE_SYSTEM,
            null,
            'local_createpackage',
            $icon,
        )->showinflatnavigation = true;
    }

}