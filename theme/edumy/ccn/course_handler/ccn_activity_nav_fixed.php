<?php
/*
@ccnRef: @
*/

defined('MOODLE_INTERNAL') || die();
include_once($CFG->dirroot . '/course/lib.php');
include_once($CFG->dirroot . '/mod/forum/lib.php');

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

		// $_ccnCourseFixedNav .= '<div class="modal fade" id="courseFixedNavigationModal" tabindex="-1" role="dialog" aria-labelledby="courseFixedNavigationModalLabel" aria-hidden="true">
		// <div class="modal-dialog" role="document">
		// <div class="modal-content">
		// <div class="modal-header">
		// <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		// <span aria-hidden="true">&times;</span>
		// </button>
		// </div>
		// <div class="modal-body">';

		$_ccnCourseFixedNav .= '
		<div class="details">
		<div class="container">
		<div class="row">
		<div class="col-12 px-0">
		<ul class="list-group">
		<li class="list-group-item"><a href="'.$CFG->wwwroot.'/my">Home</a></li>
		<li class="list-group-item"><a href="'.$CFG->wwwroot.'/my/courses.php">Units</a></li>
		<li class="list-group-item"><a href="'.$CFG->wwwroot.'/calendar/view.php?view=month&time='.time().'">Calendar</a></li>';

		// get the course announcement link
		if ($forum = forum_get_course_forum($COURSE->id, 'news')) {
			$_ccnCourseFixedNav .= '<li class="list-group-item"><a href="'.$CFG->wwwroot.'/mod/forum/view.php?f='.$forum->id.'">Announcement</a></li>';
		}

		// if (has_capability('mod/forum:addnews', $course_context)) {
		// 	$_ccnCourseFixedNav .= '<li class="list-group-item"><a href="#">Link</a></li>';
		// }


		$_ccnCourseFixedNav .= '<li class="list-group-item"><a href="'.$CFG->wwwroot.'/message/index.php">Inbox</a></li>
		<li class="list-group-item"><a href="'.$CFG->wwwroot.'/user/preferences.php">Settings</a></li>
		<li class="list-group-item"><a href="'.$CFG->wwwroot.'/blocks/helpdesk/search.php">Help</a></li>
		</ul>
		</div>
		</div>
		</div>
		</div>';

	}
}
