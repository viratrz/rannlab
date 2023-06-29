<?php
//This file is a part of random_mail_send local plugin.
//
//Local plugin - random_mail_send is an application
//for user finding and saving for sending random mail.
//
//This application is developed as per task assigned by lingel learning.
/**
 * user finding and saving for sending random mail
 *
 * @package    local_dashboard
 * @copyright  2022 Krishna Mohan Prasad <kmp8072@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_dashboard\task;

use core\task\adhoc_task;
use core_php_time_limit;
use core_user;
use stdClass;
use function raise_memory_limit;
use core\task\manager;
use core\task\asynchronous_copy_task;

/**
 *adhoc task to send random mails to users that were uploaded using csv
 *
 * @package    local_dashboard
 * @copyright  2022 Krishna Mohan Prasad <kmp8072@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class assign_courses extends adhoc_task
{

    /**
     * Performs the email sending for the users whose status is RMS_EMAIL_TO_BE_SENT.
     *
     * @return void
     */
    public function execute()
    {
        global $DB, $CFG,$USER;
        //this is going to take time
        core_php_time_limit::raise();
        raise_memory_limit(MEMORY_UNLIMITED);
        // get the pending courses assignment
        
        $pending_courses = $DB->get_records('assign_course', ['is_pending' => 1]);
        foreach ($pending_courses as $key => $pcourse) {
            // get the university
            try {
                $university = $DB->get_record('school', ['id' => $pcourse->university_id], '*', MUST_EXIST);
            } catch (Exception $e) {
                continue;    
            }
            // get the course
            try {
                $course = get_course($pcourse->course_id);
            } catch (\Exception $e) {
                continue;
            }
            // get the university admin
            $university_admin_userid = $DB->get_record('universityadmin', ['university_id' => $pcourse->university_id], 'userid', IGNORE_MISSING);
            $mdata = new stdClass();
            $mdata->courseid = $pcourse->course_id;
            $mdata->fullname = $course->fullname;
            $mdata->shortname = $course->shortname."_".$pcourse->university_id;
            $mdata->category = $university->coursecategory;
            $mdata->visible = 1;
            $mdata->startdate = 0;
            $mdata->enddate = 0;
            $mdata->idnumber = $course->shortname.$university->rto_code;
            $mdata->userdata = 0;
            // Create the copy task.
            $backupcopy = new \local_dashboard\copy\copy($mdata);
            $copyids = $backupcopy->create_copy();

            $backupid = $copyids['backupid'];
            $restoreid = $copyids['restoreid'];

            $asynctask = new \core\task\asynchronous_copy_task();
            $asynctask->set_blocking(false);
            $asynctask->set_custom_data($copyids);

            $asynctask->execute();

            $restorerecord = $DB->get_record('backup_controllers', array('backupid' => $restoreid), 'id, itemid', MUST_EXIST);
            
            // we expect that task executed successfully
            $pcourse->is_pending = 0;
            $DB->update_record('assign_course', $pcourse);
            $universityadmin = \core_user::get_user($university_admin_userid->userid);
            enrol_try_internal_enrol($restorerecord->itemid, $universityadmin->id, 12);
            enrol_try_internal_enrol($restorerecord->itemid, $universityadmin->id, 1);
            $courseresource = new stdClass();
            $courseresource->university_id=$pcourse->university_id;
            $courseresource->course_id=$pcourse->course_id;
            $courseresource->userid =$university_admin_userid->userid;
            $courseresource->timecreated=time();
            $courseresource->resourcecourseid=$restorerecord->itemid;
            $courseresource->usermodified=$USER->id;
            $courseresinserted = $DB->insert_record('courseresource', $courseresource);

            $check_uni_id = $DB->get_record_sql("SELECT id,university_id FROM {university_user_course_count} WHERE university_id = $pcourse->university_id");

            $total_course = $DB->count_records('assign_course', array('university_id'=>$pcourse->university_id));

            $user_course =  new stdClass();
            if ($check_uni_id) 
            {
                $user_course->id = $check_uni_id->id;
                $user_course->course_count = $total_course;
                $updated = $DB->update_record("university_user_course_count", $user_course, false);
            } 
            else 
            {
                $user_course->university_id = $pcourse->university_id;
                $user_course->course_count = $total_course;
                $inserted_count = $DB->insert_record("university_user_course_count", $user_course, false);
            }

        }


    }

}
