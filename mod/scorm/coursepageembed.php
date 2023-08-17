<?php

require("../../config.php");
require_once($CFG->dirroot.'/mod/scorm/lib.php');
require_once($CFG->dirroot.'/mod/scorm/locallib.php');


$id = required_param('id', PARAM_INT);

$url = new moodle_url('/mod/scorm/coursepageembed.php', ['id' => $id]);

$PAGE->set_url($url);

$cm = get_coursemodule_from_id('scorm', $id, 0, true, MUST_EXIST);

$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

if (!$scorm = $DB->get_record("scorm", array("id" => $cm->instance))) {
	print_error('invalidcoursemodule');
}

require_login($course, false, $cm);
$coursecontext = context_course::instance($course->id);
$modcontext = context_module::instance($cm->id);
require_capability('moodle/course:activityvisibility', $modcontext);

$scoid = 0;
$orgidentifier = '';

$result = scorm_get_toc($USER, $scorm, $cm->id, TOCFULLURL);
if (!empty($result->sco->id)) {
	$sco = $result->sco;
} else {
	$sco = scorm_get_sco($scorm->launch, SCO_ONLY);
}

if (!empty($sco)) {
	$scoid = $sco->id;
	if (($sco->organization == '') && ($sco->launch == '')) {
		$orgidentifier = $sco->identifier;
	} else {
		$orgidentifier = $sco->organization;
	}
}

$scormembedurl = new moodle_url('{wwwroot}/mod/scorm/player.php', ['mode' => 'normal', 'scoid' => $scoid, 'cm' => $cm->id, 'display' => 'popup', 'autostart' => false]);
$scormembedurl = $scormembedurl->out(false);

$iframe = ["<iframe src='$scormembedurl' style='height:80vh; width:60vw'></iframe>"];

if (set_coursemodule_visible($cm->id, 0)) {
	\core\event\course_module_updated::create_from_cm($cm, $modcontext)->trigger();
}

if (set_coursemodule_visible($cm->id, 1, 0)) {
	\core\event\course_module_updated::create_from_cm($cm)->trigger();
}
$PAGE->set_pagelayout('standard');
$title = get_string('courseembedurl', 'scorm');
$PAGE->set_title($title);
$PAGE->set_heading(get_string('courseembedurl', 'scorm'));

echo $OUTPUT->header();
print_object($iframe);
echo $OUTPUT->footer();
