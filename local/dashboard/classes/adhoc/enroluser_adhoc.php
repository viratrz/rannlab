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
 * An adhoc to enrol a moodle user into given course.
 *
 * @package    local_dashboard
 * @category   adhoc
 */

namespace local_dashboard\adhoc;

defined('MOODLE_INTERNAL') || die();

/**
 * Class enroluser_adhoc  extends \core\task\adhoc_task
 *
 */
class enroluser_adhoc  extends \core\task\adhoc_task {

    public function execute() {
        mtrace('enroluser_adhoc initiated');
        $data = $this->get_custom_data();

        try{
            if(!is_siteadmin($data->userid)) {
                enrol_try_internal_enrol($data->courseid,$data->userid);
            }
            mtrace("Enrolled user with id $data->userid into course with id $data->courseid successfully");


        }catch (\moodle_exception $error){

            $errormessage = $error->a;
            mtrace("enroluser_adhoc error - ".$errormessage);
        }

    }

}
