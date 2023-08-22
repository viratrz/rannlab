<?php
/*
@ccnRef: @
*/

defined('MOODLE_INTERNAL') || die();
include_once($CFG->dirroot . '/course/lib.php');

$_ccnCourseFixedNav = '';
$ccnCourseUrl = '';
if ($DB->record_exists('course', array('id' => $COURSE->id))) {
	$ccnCourseFormat = $COURSE->format;

	if (
		$ccnCourseFormat !== 'site' &&
		(
			($PAGE->pagelayout == 'course')
			|| ($PAGE->pagelayout == 'incourse')
		)
	){

		$ccnCourseRecord = $DB->get_record('course', array('id' => $COURSE->id));

		$course_context = \context_course::instance($ccnCourseRecord->id);

		$_ccnCourseFixedNav .= '
		<div class="details">
		<div class="container">
		<ul class="list-group">
		<li class="list-group-item"><a href="'.$CFG->wwwroot.'/my">Home</a></li>
		<li class="list-group-item"><a href="'.$CFG->wwwroot.'/my/courses.php">Units</a></li>
		<li class="list-group-item"><a href="'.$CFG->wwwroot.'/calendar/view.php?view=month&time='.time().'">Calendar</a></li>';

		// if (has_capability('mod/forum:addnews', $course_context)) {
		// 	$_ccnCourseFixedNav .= '<li class="list-group-item"><a href="#">Link</a></li>';
		// }

		
		$_ccnCourseFixedNav .= '<li class="list-group-item"><a href="'.$CFG->wwwroot.'/message/index.php">Inbox</a></li>
		<li class="list-group-item"><a href="'.$CFG->wwwroot.'/user/preferences.php">Settings</a></li>
		<li class="list-group-item"><a href="'.$CFG->wwwroot.'/blocks/helpdesk/search.php">Help</a></li>
		</ul>
		</div>
		</div>';

	}
}