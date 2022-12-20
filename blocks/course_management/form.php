<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package   block_iomad_company_admin
 * @copyright 2021 Derick Turner
 * @author    Derick Turner
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once("$CFG->libdir/formslib.php");
require_once(__DIR__.'/../../config.php');

class section_courses_form extends moodleform {
  

    public function definition() {
        global $OUTPUT,$DB,$PAGE;

        $PAGE->requires->js_call_amd('block_course_management/course', 'init', $courseid);
       $mform = $this->_form; 

          $mform->addElement('html', ' <div class="container"><div class="row"> <div class="col-sm" style="border-right: 1px solid;">');

            $courses_array = array('' => 'Select Course');  //right screen
            $courses1=$DB->get_records_sql("SELECT id,fullname FROM {course} c WHERE c.visible=1");

            foreach($courses1 as  $value){
        
            $courses_array[$value->id] = $value->fullname; 
            }
                       
         $mform->addElement('select', 'selectcourse', get_string('selectcourse', 'block_course_management'),$courses_array);
         $mform->setDefault('selectcourse', "Select Course");

          $mform->addElement('html', '</div>
              
             <div class="col-sm">');
  
         $courses_array2 = array('' => 'Select Section');  //right screen
            $courses2=$DB->get_records_sql("SELECT id,name FROM {course_sections} c WHERE c.visible=1");

            foreach($courses2 as  $value){
        
            $courses_array2[$value->id] = $value->name; 
            }



         $mform->addElement('select', 'selectcoursesection', get_string('selectcoursesection', 'block_course_management'),$courses_array2);
         $mform->setDefault('selectcoursesection', "Select Course");

       $mform->addElement('html', '
              </div>
           
          </div></div>');

    }
function validation($data, $files) {
        return array();
    }
}

?>
