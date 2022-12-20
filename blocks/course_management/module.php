<?php 

require_once('../../config.php');
require_login();
global $DB,$PAGE,$USER,$OUTPUT;
$PAGE->set_title('Modules page');
$PAGE->set_heading(get_string('modulepage', 'block_course_management'));
$PAGE->set_pagelayout('admin');
require_once($CFG->dirroot . '/blocks/course_management/form.php');
require_once($CFG->dirroot . '/blocks/course_management/lib.php');
echo $OUTPUT->header();

    $add_act_reso= add_activity_resources();

    echo $add_act_reso;
   $mform = new section_courses_form();

    if ($mform->is_cancelled()) {
    
    } else if ($fromform = $mform->get_data()) {
      
    } else {
   
      
   
      $mform->set_data($toform);
      $mform->display();
    }
