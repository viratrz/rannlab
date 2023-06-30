<?php

//require_once('../../user/lib.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/clilib.php');
function local_dashboard_extend_navigation(global_navigation $nav){

    global $CFG, $PAGE,$DB,$USER;

    $universityadmin = $DB->get_record("universityadmin",array("userid"=>$USER->id));

    $role = $DB->get_record("role_assignments",array("userid"=>$USER->id));
    if($universityadmin)
    {
        $_SESSION['university_id'] = $universityadmin->university_id;
    }
    if($role)
    {
        $_SESSION['role_id'] = $role->roleid;
    }
   
  
    //    if ($PAGE->theme->resolve_image_location('icon', 'local_dashboard', null)) {
    $icon = new pix_icon('book', '', 'local_dashboard', array('class' => 'icon pluginicon'));
    $icon1 = new pix_icon('enrol', '', 'local_dashboard', array('class' => 'icon pluginicon'));
    $icon2 = new pix_icon('calendar', '', 'local_dashboard', array('class' => 'icon pluginicon'));
   // $icon1 = new pix_icon('switch1', '', 'local_dashboard', array('class' => 'icon pluginicon'));
    //     $icon1 = new pix_icon('online-course1', '', 'local_dashboard', array('class' => 'icon pluginicon'));
    // } 
    // else {
    //     $icon = new pix_icon('online-course', '', 'moodle', array(
    //         'class' => 'online',
    //         'width' => 5,
    //         'height' => 5
    //     ));
    // }
   
//   if(is_siteadmin()){
//       // $course=$nav->add('Create Course');    

//         $nav->add(
//         'Create new Unit',
//         new moodle_url($CFG->wwwroot . '/course/edit.php?category=0'),      //?category=0
//         navigation_node::TYPE_SYSTEM,
//         null,
//         'local_dashboard',
//         $icon,
//     )->showinflatnavigation = true;
    
    
// }
   
   
   

if(is_siteadmin()){
    //   $School=$nav->add('RTO Management');

    //     $School->add(
    //     'HOME',
    //     new moodle_url($CFG->wwwroot . '/?redirect=0'),
    //     navigation_node::TYPE_SYSTEM,
    //     null,
    //     'local_dashboard',
    //     $icon,
    // )->showinflatnavigation = true;
    
    // $School->add(
    //     'UNITS',
    //     new moodle_url($CFG->wwwroot . '/course/index.php'),
    //     navigation_node::TYPE_SYSTEM,
    //     null,
    //     'local_dashboard',
    //     $icon,
    // )->showinflatnavigation = true;
    
    
    // $School->add(
    //     'USERS',
    //     new moodle_url($CFG->wwwroot . '/user/index.php?id=1'),
    //     navigation_node::TYPE_SYSTEM,
    //     null,
    //     'local_dashboard',
    //     $icon,
    // )->showinflatnavigation = true;
    
    //     $School->add(
    //     'RTO List',
    //     new moodle_url($CFG->wwwroot . '/local/dashboard/table.php'),
    //     navigation_node::TYPE_SYSTEM,
    //     null,
    //     'local_dashboard',
    //     $icon,
    // )->showinflatnavigation = true;
    
    // $School->add(
    //     'CALENDER',
    //     new moodle_url($CFG->wwwroot . '/calendar/view.php?view=month&time=1685468168'),
    //     navigation_node::TYPE_SYSTEM,
    //     null,
    //     'local_dashboard',
    //     $icon,
    // )->showinflatnavigation = true;


    // $School->add(
    //     'INBOX/DISCUSSION',
    //     new moodle_url($CFG->wwwroot . '/message/index.php'),
    //     navigation_node::TYPE_SYSTEM,
    //     null,
    //     'local_dashboard',
    //     $icon,
    // )->showinflatnavigation = true;
    
    
    // $School->add(
    //     'ANNOUNCEMENTS',
    //     new moodle_url($CFG->wwwroot . '/mod/forum/post.php?forum=66'),
    //     navigation_node::TYPE_SYSTEM,
    //     null,
    //     'local_dashboard',
    //     $icon,
    // )->showinflatnavigation = true;
    
    
    // $School->add(
    //     'REPORTS',
    //     new moodle_url($CFG->wwwroot . '/report/view.php?courseid=1'),
    //     navigation_node::TYPE_SYSTEM,
    //     null,
    //     'local_dashboard',
    //     $icon,
    // )->showinflatnavigation = true;
    
    // $School->add(
    //     'TICKETS',
    //     new moodle_url($CFG->wwwroot . '/blocks/helpdesk/search.php'),
    //     navigation_node::TYPE_SYSTEM,
    //     null,
    //     'local_dashboard',
    //     $icon,
    // )->showinflatnavigation = true;
    
    //   $School->add(
    //     'SANDPIT',
    //     new moodle_url($CFG->wwwroot . '#'),
    //     navigation_node::TYPE_SYSTEM,
    //     null,
    //     'local_dashboard',
    //     $icon,
    // )->showinflatnavigation = true;
    
    // $School->add(
    //     'RTO SUMMARY',
    //     new moodle_url($CFG->wwwroot . '/local/dashboard/rto_summary.php'),
    //     navigation_node::TYPE_SYSTEM,
    //     null,
    //     'local_dashboard',
    //     $icon,
    // )->showinflatnavigation = true;
    
    // $School->add(
    //     'SETTINGS',
    //     new moodle_url($CFG->wwwroot . '#'),
    //     navigation_node::TYPE_SYSTEM,
    //     null,
    //     'local_dashboard',
    //     $icon,
    // )->showinflatnavigation = true;
    
    
   
    
    
    
    
    //  $School->add(
    //     'Impersonate',
    //     new moodle_url($CFG->wwwroot . '/admin/user.php'),
    //     navigation_node::TYPE_SYSTEM,
    //     null,
    //     'local_dashboard',
    //     $icon,
    // )->showinflatnavigation = true;
    // if(!user_has_role_assignment($USER->id, 11))
    // {
    //     $admin=$nav->add('School Administration');
    //     $admin->add(
    //     'Custom User Page',
    //     new moodle_url($CFG->wwwroot . '/local/dashboard/user.php'),
    //     navigation_node::TYPE_SYSTEM,
    //     null,
    //     'local_dashboard',
    //     $icon
    // )->showinflatnavigation = true;

    // }
    }

// if(!is_siteadmin()){
// if(!has_capability('local/dashboard:additionallinks',context_system::instance())){
// // $nav->add('Redeem Access Code',new moodle_url($CFG->wwwroot . '/local/coursedetail/redeemcode.php'),
// //     navigation_node::TYPE_SYSTEM,
// //     null,
// //     'local_coursedetail'
// //     )->showinflatnavigation=true;
//  }
//     if(has_capability('local/dashboard:additionallinks',context_system::instance()) && !(user_has_role_assignment($USER->id, 11))){

//     $var = $(".columnleft:contains('My courses')").css("display","none");
//     // $PAGE->requires->js_init_code($var);

//         $admin=$nav->add('School Administration');

//         $admin->add(
//         'Add New User',
//         new moodle_url($CFG->wwwroot . '/user/editadvanced.php?id=-1'),
//         navigation_node::TYPE_SYSTEM,
//         null,
//         'local_dashboard',
//         $icon
//     )->showinflatnavigation = true;


    
//         $admin->add(
//         'Import User',
//         new moodle_url($CFG->wwwroot . '/admin/tool/uploaduser/index.php'),
//         navigation_node::TYPE_SYSTEM,
//         null,
//         'local_dashboard',
//         $icon
//     )->showinflatnavigation = true;
//         $admin->add(
//         'Export User',
//         new moodle_url($CFG->wwwroot . '/admin/user/user_bulk.php'),
//         navigation_node::TYPE_SYSTEM,
//         null,
//         'local_dashboard',
//         $icon
//     )->showinflatnavigation = true;
// 		$admin->add(
//         'Create Course',
//         new moodle_url($CFG->wwwroot . '/course/edit.php?category=1&amp;returnto=topcat'),
//         navigation_node::TYPE_SYSTEM,
//         null,
//         'local_dashboard',
//         $icon
//     )->showinflatnavigation = true;

//         $admin->add(
//         'Custom User Page',
//         new moodle_url($CFG->wwwroot . '/local/dashboard/user.php'),
//         navigation_node::TYPE_SYSTEM,
//         null,
//         'local_dashboard',
//         $icon
//     )->showinflatnavigation = true;

//         $admin->add(
//         'Access Code List',
//         new moodle_url($CFG->wwwroot . '/local/accesscode/acesscodelist.php'),
//         navigation_node::TYPE_SYSTEM,
//         null,
//         'local_dashboard',
//         $icon
//     )->showinflatnavigation = true;

    // } 
//  }
 
}

function get_child_cat($id){ 
   GLOBAL $DB;
   $id1 = '';
   $id1.= $id;
   $category = $DB->get_records("course_categories",array('parent'=>$id), $sort='', $fields='*', $limitfrom=0, $limitnum=0);
   if($category){
    foreach($category as $cat){
     $id1.= ','.get_child_cat($cat->id); 
    }
   }
       
   return $id1;
}
// function createresource($id,$schoolid){
//    global $DB;
// 	$tomorrow = new DateTime("now", core_date::get_server_timezone_object());
//     $newcourse=new stdClass();
//     $newcourse->category=22;
//     $newcourse->sortorder=1;
//     $newcourse->fullname='Resourcecourse';
//     $newcourse->shortname=$school."-".$id."#".time();
//     $newcourse->idnumber="";
//     $newcourse->summary="";
//     $newcourse->summaryformat=1;
//     $newcourse->format="topics";
//     $newcourse->showgrades=1;
//     $newcourse->newsitems=3;
//     $newcourse->startdate=$tomorrow->getTimestamp();
//     $newcourse->enddate=$tomorrow->getTimestamp();
//     $newcourse->relativedatesmode=0;
//     $newcourse->marker=1;
//     $newcourse->maxbytes=0;
//     $newcourse->legacyfiles=0;
//     $newcourse->showreports=0;
//     $newcourse->visible=1;
//     $newcourse->visibleold=1;
//     $newcourse->groupmode=0;
//     $newcourse->groupmodeforce=0;
//     $newcourse->defaultgroupingid=0;
//     $newcourse->lang="";
//     $newcourse->calendartype="";
//     $newcourse->theme="";
//     $newcourse->timecreated=time();
//     $newcourse->timemodified=time();
//     $newcourse->requested=0;
//     $newcourse->enablecompletion=1;
//     $newcourse->completionnotify=0;
//     $newcourse->cacherev=1655381653;
//     $newcourse->showactivitydates=1;
//     $newcourse->showcompletionconditions=1;
//      //$DB->set_debug(true);
//      $courseid=$DB->insert_record('course', $newcourse);
//      return $courseid;

// }

function create_coursess($event)
{
    global $DB, $USER;
    purge_caches();
    $table="course";
    $dataobject=new stdClass();
    $dataobject->id=(int)$event->objectid;
    $dataobject->cb_userid=(int)$event->userid;
    $dataobject->tenent_id=(int)$_SESSION['university_id'];
    $DB->update_record($table, $dataobject);

    if(!is_siteadmin()) {
        enrol_try_internal_enrol($dataobject->id,$USER->id);
    }
}

function module_create($evv)
{
    global $DB;
    purge_caches();
    $table2="course_modules";
    $dataobject2=new stdClass();
    $dataobject2->id=(int)$evv->objectid;
    $dataobject2->cb_userid=(int)$evv->userid;
    $dataobject2->tenent_id=(int)$_SESSION['university_id'];
    $DB->update_record($table2, $dataobject2);
}

function createresource($id,$schoolid,$user){
    global $DB, $USER;
    $main_course = $DB->get_record("course", ['id'=>$id]);
    $school_name = $DB->get_record("school", ['id'=>$schoolid]);
    //$maincat2 = $DB->get_record_sql("SELECT id FROM {course_categories} WHERE idnumber='resourcecat'");
    
	$tomorrow = new DateTime("now", core_date::get_server_timezone_object());
    
    $newcourse=new stdClass();
    //$newcourse->category=$maincat2->id;
    $newcourse->category=$main_course->category;
    $newcourse->sortorder=900000;
    $newcourse->fullname=$main_course->fullname;
    $newcourse->shortname=$main_course->shortname."_".$id."_".$schoolid;
    $newcourse->idnumber=$main_course->idnumber;
    $newcourse->summary=$main_course->summary;
    $newcourse->summaryformat=$main_course->summaryformat;
    $newcourse->format=$main_course->format;
    $newcourse->showgrades=$main_course->showgrades;
    $newcourse->newsitems=$main_course->newsitems;
    $newcourse->startdate=$tomorrow->getTimestamp();
    $newcourse->enddate=$tomorrow->getTimestamp();
    $newcourse->relativedatesmode=$main_course->relativedatesmode;
    $newcourse->marker=$main_course->marker;
    $newcourse->maxbytes=$main_course->maxbytes;
    $newcourse->legacyfiles=$main_course->legacyfiles;
    $newcourse->showreports=$main_course->showreports;
    $newcourse->visible=$main_course->visible;
    $newcourse->visibleold=$main_course->visibleold;
    $newcourse->groupmode=$main_course->groupmode;
    $newcourse->groupmodeforce=$main_course->groupmodeforce;
    $newcourse->defaultgroupingid=$main_course->defaultgroupingid;
    $newcourse->lang=$main_course->lang;
    $newcourse->calendartype=$main_course->calendartype;
    $newcourse->theme=$main_course->theme;
    $newcourse->timecreated=time();
    $newcourse->timemodified=time();
    $newcourse->requested=$main_course->requested;
    $newcourse->enablecompletion=$main_course->enablecompletion;
    $newcourse->completionnotify=$main_course->completionnotify;
    $newcourse->cacherev=1655381653;
    $newcourse->showactivitydates=$main_course->showactivitydates;
    $newcourse->showcompletionconditions=$main_course->showcompletionconditions;
    $newcourse->cb_userid=$user;
    $newcourse->tenent_id=$schoolid;
     //$DB->set_debug(true);
     
    $courseid=$DB->insert_record('course', $newcourse);
    
    // if($courseid)
    //  {
    //     $enrol=new stdClass();
    //     $enrol->enrol="manual";
    //     $enrol->status=0;
    //     $enrol->courseid=$courseid;
    //     $enrol->sortorder=0;
    //     $enrol->enrolperiod=0;
    //     $enrol->enrolstartdate=0;
    //     $enrol->enrolenddate=0;
    //     $enrol->expirynotify=0;
    //     $enrol->expirythreshold=86400;
    //     $enrol->notifyall=0;
    //     $enrol->roleid=3;
    //     $enrol->timecreated=time();
    //     $enrol->timemodified=time();
    //     $enrolid=$DB->insert_record('enrol', $enrol);
    //  }
    if ($courseid) 
    {
        // Duplicate the course sections and activities
        $sourceSections = $DB->get_records('course_sections', array('course' => $id), 'section');
        
        foreach ($sourceSections as $sourceSection) {
            $newSection = new stdClass();
            $newSection->course = $courseid;
            $newSection->section = $sourceSection->section;
            $newSection->summary = $sourceSection->summary;
        
            $newSectionId = $DB->insert_record('course_sections', $newSection);
            
            $sourceActivities = $DB->get_records('course_modules', array('course' => $id, 'section' => $sourceSection->id));
            
            foreach ($sourceActivities as $sourceActivity) {
                // Duplicate each activity
                
                $newActivity = new stdClass();
                $newActivity->course = $courseid;
                $newActivity->module = $sourceActivity->module;
                $newActivity->instance = $sourceActivity->instance;
                $newActivity->section = $newSectionId;
        
                $newActivityId = $DB->insert_record('course_modules', $newActivity);
                
        
                // Duplicate associated module settings
                $sourceModule = $DB->get_record('course_modules', array('id' => $sourceActivity->id));
                return($sourceModule->instance);
                $sourceModuleName = $DB->get_field('modules', 'name', array('id' => $sourceModule->module));
                
                $newModuleName = $DB->get_field('modules', 'name', array('name' => $sourceModuleName));
                
                $moduleTable = 'course_' . $newModuleName;
        
                $sourceModuleSettings = $DB->get_record($moduleTable, array('id' => $sourceModule->instance));
                
                $newModuleSettings = clone $sourceModuleSettings;
                $newModuleSettings->course = $courseid;
                $newModuleSettings->id = $newActivityId;
        
                $DB->insert_record($moduleTable, $newModuleSettings);
            }
            
        }
        
        
        
        $enrol=new stdClass();
        $enrol->enrol="manual";
        $enrol->status=0;
        $enrol->courseid=$courseid;
        $enrol->sortorder=0;
        $enrol->enrolperiod=0;
        $enrol->enrolstartdate=0;
        $enrol->enrolenddate=0;
        $enrol->expirynotify=0;
        $enrol->expirythreshold=86400;
        $enrol->notifyall=0;
        $enrol->roleid=1;
        $enrol->timecreated=time();
        $enrol->timemodified=time();
        $enrolid=$DB->insert_record('enrol', $enrol);
        purge_caches();
        $internalenrol = enrol_try_internal_enrol($courseid, $user, 12);
        $courseresource = new stdClass();
        $courseresource->university_id=$schoolid;
        $courseresource->course_id=$id;
        $courseresource->userid	=$user;
        $courseresource->timecreated=time();
        $courseresource->resourcecourseid=$courseid;
        $courseresource->usermodified=$USER->id;
        $courseresinserted = $DB->insert_record('courseresource', $courseresource);
        return $courseresinserted;
        
    }
}

?>