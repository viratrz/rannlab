<?php
include("../../config.php");
// include("navbar.mustache");
require_once("$CFG->libdir/formslib.php");
// require_once($CFG->dirroot.'/user/lib.php');
$PAGE->set_url(new moodle_url('/local/changelogo/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Change Logo');
$PAGE->set_pagelayout('standard');

$returnurl= new moodle_url('/');

require_login();
global $CFG, $DB,$USER;

class changelogo_form extends moodleform 
{
    //Add elements to form
    public function definition() 
    {
        $mform = $this->_form; // Don't forget the underscore! 
        $mform->addElement('filepicker', 'userfile', null,
                   array('maxbytes' => $maxbytes, 'accepted_types' => '*'));
        $this->add_action_buttons(true, 'Upload Logo');
    }
      //Custom validation should be added here
      function validation($data, $files) {
        return array();
    }
}
$mform = new changelogo_form();
// Send image
echo $OUTPUT->header();

    if ($mform->is_cancelled()) 
    {
    //   redirect($returnurl,"You Cancelled the form");   
    } 
    else if ($data = $mform->get_data()) 
    {   
        $new_name = $mform->get_new_filename('userfile');
        $path= 'logo/'.$new_name;
        $fullpath = "$CFG->httpswwwroot/local/changelogo/". $path;
        $success = $mform->save_file('userfile', $path, true);
        
        $university = $DB->get_record('universityadmin',array('userid' => $USER->id));
        // var_dump($university);
        // die;
        $set_logo = new stdclass();
        $set_logo->id = $university->university_id;
        $set_logo->logo_path = $fullpath;
        $DB->update_record('school', $set_logo);
        
        if($success)
        {
            redirect("http://148.72.245.159/MoodleLMS/my/", "Logo Changed Successfully");
            // echo "<img src='$path' style='width:100px; height: 80px;'>";
            // echo "<h5 style='background: green; color: white; padding: 5px 4px; width: fit-content; margin: 5px 0 0 0;'>Logo Changed Successfully Done!</h5>";
            // echo "<script> 
            // alert('Logo Changed Successfully');
            // window.location.reload;
            // </script>";
        }        
    } 
    else
    {
        $mform->set_data($toform);
        $mform->display();
    }
echo $OUTPUT->footer();
?>