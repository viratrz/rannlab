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
 * External files API
 *
 * @package    core_files
 * @category   external
 */

defined('MOODLE_INTERNAL') || die;

global $DB,$PAGE,$CFG;
require_once($CFG->libdir . '/externallib.php');
$PAGE->requires->js_call_amd('block_course_management/course', 'init', $courseid);
class block_course_management_external extends external_api {


    public static function get_course_id_is_allowed_from_ajax() {
        return true;
    }

    public static function get_course_id_parameters() {
       return new external_function_parameters(
            array(
               'courseid' => new external_value(PARAM_INT),
               
            )
        );
    }

    public static function get_course_id_returns() {
        return new external_value(PARAM_RAW);
      
    }
   
    public static function get_course_id($courseid) {  

        global $CFG, $DB, $USER;     
   
      return  $courseid;
         
    }
  

}
