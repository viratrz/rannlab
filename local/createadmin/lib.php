<?php
function local_createadmin_extend_navigation(global_navigation $nav)
{

    global $CFG, $PAGE,$DB,$USER,$SESSION;
    $uni_id = $SESSION->university_id;
    
    $role = $DB->get_record("role_assignments",array("userid"=>$USER->id));
      
    $icon = new pix_icon('user', '', 'local_createadmin', array('class' => 'icon pluginicon'));
    
    $icon1 = new pix_icon('cuser', '', 'local_createuser', array('class' => 'icon pluginicon'));
    $icon2 = new pix_icon('luser', '', 'local_createuser', array('class' => 'icon pluginicon'));
    $icon3 = new pix_icon('eye', '', 'local_createuser', array('class' => 'icon pluginicon'));
    $table_icon = new pix_icon('table', '', 'local_createadmin', array('class' => 'icon pluginicon'));
    /*if(!user_has_role_assignment($USER->id, 5)  || !user_has_role_assignment($USER->id, 9) || !user_has_role_assignment($USER->id, 10))
    {
        $nav->add(
            'Upload User',
            new moodle_url($CFG->wwwroot . '/admin/tool/uploaduser/index.php'),
            navigation_node::TYPE_SYSTEM,
            null,
            'local_createadmin',
            $icon,
        )->showinflatnavigation = false;
    } */
    if(user_has_role_assignment($USER->id, 9))
    {
        $profile = $nav->add('Manage Profile');
        /* $profile->add(
            'Create New Admin',
            new moodle_url($CFG->wwwroot . '/local/createadmin/index.php'),
            navigation_node::TYPE_SYSTEM,
            null,
            'local_createadmin',
            $icon,
        )->showinflatnavigation = true;

        $profile->add(
            'Admin List',
            new moodle_url($CFG->wwwroot . '/local/createadmin/custom_admin_list.php'),
            navigation_node::TYPE_SYSTEM,
            null,
            'local_createadmin',
            $icon,
        )->showinflatnavigation = true;  */
        
        // $profile->add(
        //     'HOME',
        //     new moodle_url($CFG->wwwroot . '/?redirect=0'),
        //     navigation_node::TYPE_SYSTEM,
        //     null,
        //     'local_createuser',
        //     $icon,
        // )->showinflatnavigation = true;

        // $profile->add(
        //     'UNITS',
        //     new moodle_url($CFG->wwwroot . '/course/index.php'),
        //     navigation_node::TYPE_SYSTEM,
        //     null,
        //     'local_createuser',
        //     $icon,
        // )->showinflatnavigation = true;
        
        
        // $profile->add(
        //     'TICKETS',
        //     new moodle_url($CFG->wwwroot . '/blocks/helpdesk/search.php'),
        //     navigation_node::TYPE_SYSTEM,
        //     null,
        //     'local_createuser',
        //     $icon,
        // )->showinflatnavigation = true;
        
        //   $profile->add(
        //     'GROUP/CLASS',
        //     new moodle_url($CFG->wwwroot . '/group/index.php?id=1'),
        //     navigation_node::TYPE_SYSTEM,
        //     null,
        //     'local_createuser',
        //     $icon,
        // )->showinflatnavigation = true;
        
        
        //   $profile->add(
        //     'CALENDER',
        //     new moodle_url($CFG->wwwroot . '/calendar/view.php?view=month&time=1685468168'),
        //     navigation_node::TYPE_SYSTEM,
        //     null,
        //     'local_createuser',
        //     $icon,
        // )->showinflatnavigation = true;
        
        
        //   $profile->add(
        //     'INBOX',
        //     new moodle_url($CFG->wwwroot . '/message/index.php'),
        //     navigation_node::TYPE_SYSTEM,
        //     null,
        //     'local_createuser',
        //     $icon,
        // )->showinflatnavigation = true;
        
        
        // $profile->add(
        //     'DISCUSSION',
        //     new moodle_url($CFG->wwwroot . '#'),
        //     navigation_node::TYPE_SYSTEM,
        //     null,
        //     'local_createuser',
        //     $icon,
        // )->showinflatnavigation = true;
        
        
        // $profile->add(
        //     'ANNOUNCEMENTS',
        //     new moodle_url($CFG->wwwroot . '/mod/forum/post.php?forum=66'),
        //     navigation_node::TYPE_SYSTEM,
        //     null,
        //     'local_createuser',
        //     $icon,
        // )->showinflatnavigation = true;
        
        // $profile->add(
        //     'REPORTS',
        //     new moodle_url($CFG->wwwroot . '/report/view.php?courseid=1'),
        //     navigation_node::TYPE_SYSTEM,
        //     null,
        //     'local_createuser',
        //     $icon,
        // )->showinflatnavigation = true;
        
        
        // $profile->add(
        //     'ANALYTICS',
        //     new moodle_url($CFG->wwwroot . '/report/overviewstats/index.php?course=1'),
        //     navigation_node::TYPE_SYSTEM,
        //     null,
        //     'local_createuser',
        //     $icon,
        // )->showinflatnavigation = true;
        
        
        // $profile->add(
        //     'FILES',
        //     new moodle_url($CFG->wwwroot . '/user/files.php'),
        //     navigation_node::TYPE_SYSTEM,
        //     null,
        //     'local_createuser',
        //     $icon,
        // )->showinflatnavigation = true;
        
        
        // $profile->add(
        //     'LIBRARY',
        //     new moodle_url($CFG->wwwroot . '#'),
        //     navigation_node::TYPE_SYSTEM,
        //     null,
        //     'local_createuser',
        //     $icon,
        // )->showinflatnavigation = true;
        
        // $profile->add(
        //     'SETTINGS',
        //     new moodle_url($CFG->wwwroot . '#'),
        //     navigation_node::TYPE_SYSTEM,
        //     null,
        //     'local_createuser',
        //     $icon,
        // )->showinflatnavigation = true;
        
        // $profile->add(
        //     'Create User',
        //     new moodle_url($CFG->wwwroot . '/local/createuser/index.php'),
        //     navigation_node::TYPE_SYSTEM,
        //     null,
        //     'local_createuser',
        //     $icon,
        // )->showinflatnavigation = true;

        // $profile->add(
        //     'Users List',
        //     new moodle_url($CFG->wwwroot . '/local/createuser/user_list.php'),
        //     navigation_node::TYPE_SYSTEM,
        //     null,
        //     'local_createuser',
        //     $icon2,
        // )->showinflatnavigation = false;

        // $profile->add(
        //     'RTO Unit Allocation Report',
        //     new moodle_url($CFG->wwwroot . "/local/dashboard/course_report.php?uni_id=$uni_id"),
        //     navigation_node::TYPE_SYSTEM,
        //     null,
        //     'local_createuser',
        //     $icon3,
        // )->showinflatnavigation = true;
        
        // $profile->add(
        //     'C.U.E. Summary',
        //     new moodle_url($CFG->wwwroot . "/local/createuser/student_list.php?"),
        //     navigation_node::TYPE_SYSTEM,
        //     null,
        //     'local_createuser',
        //     $icon3,
        // )->showinflatnavigation = true;
        
       /* $profile->add(
            'Upload User',
            new moodle_url($CFG->wwwroot . '/admin/tool/uploaduser/index.php'),
            navigation_node::TYPE_SYSTEM,
            null,
            'local_createadmin',
            $icon,
        )->showinflatnavigation = false; */
  
    }
    // if (is_siteadmin()) {
        // $nav->add(
        //     'RTO SUMMARY',
        //     new moodle_url($CFG->wwwroot . '/local/dashboard/rto_summary.php'),
        //     navigation_node::TYPE_SYSTEM,
        //     null,
        //     'local_createadmin',
        //     $table_icon,
        // )->showinflatnavigation = true;
    // }
    
    // if(user_has_role_assignment($USER->id, 5))
    // {
    
    //  $profile = $nav->add('Manage Profile');
    //  $profile->add(
    //         'HOME',
    //         new moodle_url($CFG->wwwroot . '/?redirect=0'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon2,
    //     )->showinflatnavigation = true;
    
    //     $profile->add(
    //         'UNITS',
    //         new moodle_url($CFG->wwwroot . '/my/courses.php'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon2,
    //     )->showinflatnavigation = true;
        
    //     $profile->add(
    //         'CALENDER',
    //         new moodle_url($CFG->wwwroot . '/calendar/view.php?view=month&time=1685468168'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon2,
    //     )->showinflatnavigation = true;
        
    //     $profile->add(
    //         'ANNOUNCEMENTS',
    //         new moodle_url($CFG->wwwroot . '/mod/forum/post.php?forum=66'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon2,
    //     )->showinflatnavigation = true;
        
    //     $profile->add(
    //         'INBOX',
    //         new moodle_url($CFG->wwwroot . '/message/index.php'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon2,
    //     )->showinflatnavigation = true;
        
    //     $profile->add(
    //         'DISCUSSION',
    //         new moodle_url($CFG->wwwroot . '#'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon2,
    //     )->showinflatnavigation = true;
        
    //     $profile->add(
    //         'ATTENDANCE',
    //         new moodle_url($CFG->wwwroot . '/blocks/autoattend/index.php?course=310&class=0'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon2,
    //     )->showinflatnavigation = true;
        
    //     $profile->add(
    //         'SUPPORT',
    //         new moodle_url($CFG->wwwroot . '/blocks/helpdesk/search.php'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon2,
    //     )->showinflatnavigation = true;
        
    //     $profile->add(
    //         'SETTINGS',
    //         new moodle_url($CFG->wwwroot . '#'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon2,
    //     )->showinflatnavigation = true;
    // }
    
    // if(user_has_role_assignment($USER->id, 3))
    // {
    
    //  $profile = $nav->add('Manage Profile');
    //  $profile->add(
    //         'HOME',
    //         new moodle_url($CFG->wwwroot . '/?redirect=0'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon1,
    //     )->showinflatnavigation = true;
    
    //     $profile->add(
    //         'UNITS',
    //         new moodle_url($CFG->wwwroot . '/my/courses.php'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon1,
    //     )->showinflatnavigation = true;
        
    //      $profile->add(
    //         'ANNOUNCEMENTS',
    //         new moodle_url($CFG->wwwroot . '/mod/forum/post.php?forum=66'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon1,
    //     )->showinflatnavigation = true;
        
    //     $profile->add(
    //         'CALENDER',
    //         new moodle_url($CFG->wwwroot . '/calendar/view.php?view=month&time=1685468168'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon1,
    //     )->showinflatnavigation = true;
        
    //     $profile->add(
    //         'INBOX',
    //         new moodle_url($CFG->wwwroot . '/message/index.php'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon1,
    //     )->showinflatnavigation = true;
        
    //     $profile->add(
    //         'DISCUSSION',
    //         new moodle_url($CFG->wwwroot . '#'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon1,
    //     )->showinflatnavigation = true;
        
    //     $profile->add(
    //         'GROUP/CLASS',
    //         new moodle_url($CFG->wwwroot . '#'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon1,
    //     )->showinflatnavigation = true;
        
    //     $profile->add(
    //         'ATTENDANCE',
    //         new moodle_url($CFG->wwwroot . '/blocks/autoattend/index.php?course=310&class=0'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon1,
    //     )->showinflatnavigation = true;
        
    //     $profile->add(
    //         'REPORTS',
    //         new moodle_url($CFG->wwwroot . '/report/view.php?courseid=1'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon1,
    //     )->showinflatnavigation = true;
        
    //     $profile->add(
    //         'SUPPORT',
    //         new moodle_url($CFG->wwwroot . '/blocks/helpdesk/search.php'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon1,
    //     )->showinflatnavigation = true;
        
    //     $profile->add(
    //         'SETTINGS',
    //         new moodle_url($CFG->wwwroot . '#'),
    //         navigation_node::TYPE_SYSTEM,
    //         null,
    //         'local_createuser',
    //         $icon1,
    //     )->showinflatnavigation = true;
    // }
    
}

