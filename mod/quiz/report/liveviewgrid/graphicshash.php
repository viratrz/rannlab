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
 * This script provides the hash telling the page to update or not.
 *
 * @package    quiz_liveviewgrid
 * @copyright  2016 W. F. Junkin, Eckerd College, http://www.eckerd.edu
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/config.php');

defined('MOODLE_INTERNAL') || die();

$id = optional_param('id', 0, PARAM_INT);
$cm = $DB->get_record('course_modules', array('id' => $id), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
require_login($course, true, $cm);
$contextinstance = context_module::instance($id);
$quizcontextid = $contextinstance->id;
$quiztime = $DB->get_record_sql("
    SELECT max(qa.timemodified)
    FROM {question_attempts} qa
    JOIN {question_usages} qu ON qu.id = qa.questionusageid
    WHERE qu.contextid = ?", array($quizcontextid));
foreach ($quiztime as $qkey => $qtm) {
    $qmaxtime = intval($qtm) + 1;
}

echo $qmaxtime;
