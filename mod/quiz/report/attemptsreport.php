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
 * The file defines a base class that can be used to build a report like the
 * overview or responses report, that has one row per attempt.
 *
 * @package   mod_quiz
 * @copyright 2010 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/tablelib.php');


/**
 * Base class for quiz reports that are basically a table with one row for each attempt.
 *
 * @copyright 2010 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class quiz_attempts_report extends quiz_default_report {
    /** @var int default page size for reports. */
    const DEFAULT_PAGE_SIZE = 30;

    /** @var string constant used for the options, means all users with attempts. */
    const ALL_WITH = 'all_with';
    /** @var string constant used for the options, means only enrolled users with attempts. */
    const ENROLLED_WITH = 'enrolled_with';
    /** @var string constant used for the options, means only enrolled users without attempts. */
    const ENROLLED_WITHOUT = 'enrolled_without';
    /** @var string constant used for the options, means all enrolled users. */
    const ENROLLED_ALL = 'enrolled_any';

    /** @var string the mode this report is. */
    protected $mode;

    /** @var context_module the quiz context. */
    protected $context;

    /** @var mod_quiz_attempts_report_form The settings form to use. */
    protected $form;

    /** @var string SQL fragment for selecting the attempt that gave the final grade,
     * if applicable. */
    protected $qmsubselect;

    /** @var boolean caches the results of {@link should_show_grades()}. */
    protected $showgrades = null;

    /**
     *  Initialise various aspects of this report.
     *
     * @param string $mode
     * @param string $formclass
     * @param object $quiz
     * @param object $cm
     * @param object $course
     * @return array with four elements:
     *      0 => integer the current group id (0 for none).
     *      1 => \core\dml\sql_join Contains joins, wheres, params for all the students in this course.
     *      2 => \core\dml\sql_join Contains joins, wheres, params for all the students in the current group.
     *      3 => \core\dml\sql_join Contains joins, wheres, params for all the students to show in the report.
     *              Will be the same as either element 1 or 2.
     */
    public function init($mode, $formclass, $quiz, $cm, $course) {
        $this->mode = $mode;

        $this->context = context_module::instance($cm->id);

        list($currentgroup, $studentsjoins, $groupstudentsjoins, $allowedjoins) = $this->get_students_joins(
                $cm, $course);

        $this->qmsubselect = quiz_report_qm_filter_select($quiz);

        $this->form = new $formclass($this->get_base_url(),
                array('quiz' => $quiz, 'currentgroup' => $currentgroup, 'context' => $this->context));

        return array($currentgroup, $studentsjoins, $groupstudentsjoins, $allowedjoins);
    }

    /**
     * Get the base URL for this report.
     * @return moodle_url the URL.
     */
    protected function get_base_url() {
        return new moodle_url('/mod/quiz/report.php',
                array('id' => $this->context->instanceid, 'mode' => $this->mode));
    }

    /**
     * Get sql fragments (joins) which can be used to build queries that
     * will select an appropriate set of students to show in the reports.
     *
     * @param object $cm the course module.
     * @param object $course the course settings.
     * @return array with four elements:
     *      0 => integer the current group id (0 for none).
     *      1 => \core\dml\sql_join Contains joins, wheres, params for all the students in this course.
     *      2 => \core\dml\sql_join Contains joins, wheres, params for all the students in the current group.
     *      3 => \core\dml\sql_join Contains joins, wheres, params for all the students to show in the report.
     *              Will be the same as either element 1 or 2.
     */
    protected function get_students_joins($cm, $course = null) {
        $currentgroup = $this->get_current_group($cm, $course, $this->context);

        $empty = new \core\dml\sql_join();
        if ($currentgroup == self::NO_GROUPS_ALLOWED) {
            return array($currentgroup, $empty, $empty, $empty);
        }

        $studentsjoins = get_enrolled_with_capabilities_join($this->context, '',
                array('mod/quiz:attempt', 'mod/quiz:reviewmyattempts'));

        if (empty($currentgroup)) {
            return array($currentgroup, $studentsjoins, $empty, $studentsjoins);
        }

        // We have a currently selected group.
        $groupstudentsjoins = get_enrolled_with_capabilities_join($this->context, '',
                array('mod/quiz:attempt', 'mod/quiz:reviewmyattempts'), $currentgroup);

        return array($currentgroup, $studentsjoins, $groupstudentsjoins, $groupstudentsjoins);
    }

    /**
     * Outputs the things you commonly want at the top of a quiz report.
     *
     * Calls through to {@link print_header_and_tabs()} and then
     * outputs the standard group selector, number of attempts summary,
     * and messages to cover common cases when the report can't be shown.
     *
     * @param stdClass $cm the course_module information.
     * @param stdClass $course the course settings.
     * @param stdClass $quiz the quiz settings.
     * @param mod_quiz_attempts_report_options $options the current report settings.
     * @param int $currentgroup the current group.
     * @param bool $hasquestions whether there are any questions in the quiz.
     * @param bool $hasstudents whether there are any relevant students.
     */
    protected function print_standard_header_and_messages($cm, $course, $quiz,
            $options, $currentgroup, $hasquestions, $hasstudents) {
        global $OUTPUT;

        $this->print_header_and_tabs($cm, $course, $quiz, $this->mode);

        if (groups_get_activity_groupmode($cm)) {
            // Groups are being used, so output the group selector if we are not downloading.
            groups_print_activity_menu($cm, $options->get_url());
        }

        // Print information on the number of existing attempts.
        if ($strattemptnum = quiz_num_attempt_summary($quiz, $cm, true, $currentgroup)) {
            echo '<div class="quizattemptcounts">' . $strattemptnum . '</div>';
        }

        if (!$hasquestions) {
            echo quiz_no_questions_message($quiz, $cm, $this->context);
        } else if ($currentgroup == self::NO_GROUPS_ALLOWED) {
            echo $OUTPUT->notification(get_string('notingroup'));
        } else if (!$hasstudents) {
            echo $OUTPUT->notification(get_string('nostudentsyet'));
        } else if ($currentgroup && !$this->hasgroupstudents) {
            echo $OUTPUT->notification(get_string('nostudentsingroup'));
        }
    }

    /**
     * Add all the user-related columns to the $columns and $headers arrays.
     * @param table_sql $table the table being constructed.
     * @param array $columns the list of columns. Added to.
     * @param array $headers the columns headings. Added to.
     */
    protected function add_user_columns($table, &$columns, &$headers) {
        global $CFG;
        if (!$table->is_downloading() && $CFG->grade_report_showuserimage) {
            $columns[] = 'picture';
            $headers[] = '';
