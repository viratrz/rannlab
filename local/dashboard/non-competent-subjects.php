<?php
require_once '../../config.php';
require $CFG->libdir . "/tablelib.php";
$context = context_system::instance();
$sitecontext = $context;
require_login();
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$title = 'Non yet competent subjects';
$PAGE->set_title($title);
$PAGE->set_heading('Non yet competent subjects');
$PAGE->set_url('/local/dashboard/non-competent-subjects.php');
// require_capability('moodle/course:enrolconfig', $context);

$userroles = get_user_roles($context);
$isviewable = false;
foreach ($userroles as $key => $value) {
	if ($value->shortname == 'rtoadmin' || $value->shortname == 'trainer' || $value->shortname == 'subrtoadmin') {
		$isviewable = true;
	}
}

if (!$isviewable) {
	throw new moodle_exception('nopermissions');
}

$not_competent_sub_sql = "SELECT
c.id,
c.fullname AS 'coursefullname',
COUNT(DISTINCT(cmc.userid)) AS non_competent_students_count
FROM mdl_course_modules_completion cmc
JOIN mdl_user u ON u.id = cmc.userid
JOIN mdl_course_modules cm ON cmc.coursemoduleid = cm.id
JOIN mdl_course c ON cm.course = c.id
JOIN mdl_assign_course ac ON ac.course_id = c.id
JOIN mdl_modules m ON cm.module = m.id
WHERE cmc.completionstate <> 2 AND ac.university_id = ?
GROUP BY c.id
ORDER BY non_competent_students_count";

$ncsub_records = $DB->get_records_sql($not_competent_sub_sql, [$SESSION->university_id]);

if ($ncsub_records) {
	// $series = 'series';
	$barchart = new core\chart_bar();
	$CFG->chart_colorset = ['#001f3f', '#01ff70', '#F012BE', '#85144b', '#B10DC9'];
	$barchart->get_xaxis(0, true)->set_label("Courses");
	$barchart->get_yaxis(0, true)->set_label("No of students");
	$barchart->get_yaxis(0, true)->set_stepsize(10);
	$labels = [];
	$barchart->set_legend_options(['display' => false]);
	$barseries = [];

	foreach ($ncsub_records as $key => $value) {
		$barseries[] = $value->non_competent_students_count;
		$labels[] = $value->coursefullname;

	}

	$series = new core\chart_series('Not competent students', $barseries);
	$barchart->add_series($series);
	$barchart->set_labels($labels);

}

echo $OUTPUT->header();

if ($ncsub_records) {
	echo $OUTPUT->render_chart($barchart, false);

} else {
	echo "No data found";
}

echo $OUTPUT->footer();
