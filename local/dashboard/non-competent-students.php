<?php
require_once '../../config.php';
require $CFG->libdir . "/tablelib.php";
$context = context_system::instance();
$sitecontext = $context;
require_login();
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$title = 'Non yet competent students';
$PAGE->set_title($title);
$PAGE->set_heading('Non yet competent students');
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

class non_competent_subject_table extends table_sql {

	private $scounter = 1;

	/**
	 * Constructor
	 * @param int $uniqueid all tables have to have a unique id, this is used
	 *      as a key when storing table properties like sort order in the session.
	 */
	function __construct($uniqueid, $baseurl) {
		parent::__construct($uniqueid);
		$this->define_baseurl($baseurl);

		$head_cols = [
			's_no' => 'S.No.',
			'coursefullname' => 'Unit',
			'ncsztot' => '15-30 Days',
			'ncsttos' => '31-60 Days',
			'ncssplus' => '61+ Days',
		];

		$this->define_columns(array_keys($head_cols));
		$this->define_headers(array_values($head_cols));

		// Allow pagination.
		$this->pageable(true);
		// Allow downloading.
		$name = format_string("approval_requests");
		$this->is_downloadable(false);

		// Allow sorting. Default to sort by id ASC.
		$this->sortable(false);

		// $this->no_sorting('sta');

		list($fields, $from, $where, $params) = $this->get_sql_fragments();

		list($count_sql, $count_params) = $this->get_count_sql();

		$this->set_sql($fields, $from, $where, $params);

		$this->set_count_sql($count_sql, $count_params);
	}

	public function col_s_no($row): int {
		$count = ($this->currpage * $this->pagesize) + $this->scounter;
		$this->scounter = $this->scounter + 1;
		return $count;
	}

	private function get_sql_fragments() {

		global $SESSION;

		$not_competent_sub_sql = "SELECT
c.id,
c.fullname AS 'coursefullname',
COUNT(DISTINCT(cmc1.userid)) AS ncsztot,
COUNT(DISTINCT(cmc2.userid)) AS ncsttos,
COUNT(DISTINCT(cmc3.userid)) AS ncssplus
FROM mdl_course_modules_completion cmc
LEFT JOIN mdl_course_modules_completion cmc1 ON cmc1.userid = cmc.userid AND cmc1.timemodified < $now -15*24*60*60 AND cmc1.timemodified > $now -30*24*60*60
LEFT JOIN mdl_course_modules_completion cmc2 ON cmc2.userid = cmc.userid AND cmc2.timemodified < $now - 30*24*60*60 -1 AND cmc2.timemodified > $now -60*24*60*60
LEFT JOIN mdl_course_modules_completion cmc3 ON cmc3.userid = cmc.userid AND cmc3.timemodified < $now - 60*24*60*60 -1
JOIN mdl_user u ON u.id = cmc.userid
JOIN mdl_course_modules cm ON cmc.coursemoduleid = cm.id
JOIN mdl_course c ON cm.course = c.id
JOIN mdl_assign_course ac ON ac.course_id = c.id
JOIN mdl_modules m ON cm.module = m.id
WHERE cmc.completionstate <> 2 AND ac.university_id = $SESSION->university_id
GROUP BY c.id";

		$now = time();

		$fields = "c.id,
c.fullname AS 'coursefullname',
COUNT(DISTINCT(cmc1.userid)) AS ncsztot,
COUNT(DISTINCT(cmc2.userid)) AS ncsttos,
COUNT(DISTINCT(cmc3.userid)) AS ncssplus";

		$from = "mdl_course_modules_completion cmc
LEFT JOIN mdl_course_modules_completion cmc1 ON cmc1.userid = cmc.userid AND cmc1.timemodified < $now -15*24*60*60 AND cmc1.timemodified > $now -30*24*60*60
LEFT JOIN mdl_course_modules_completion cmc2 ON cmc2.userid = cmc.userid AND cmc2.timemodified < $now - 30*24*60*60 -1 AND cmc2.timemodified > $now -60*24*60*60
LEFT JOIN mdl_course_modules_completion cmc3 ON cmc3.userid = cmc.userid AND cmc3.timemodified < $now - 60*24*60*60 -1
JOIN mdl_user u ON u.id = cmc.userid
JOIN mdl_course_modules cm ON cmc.coursemoduleid = cm.id
JOIN mdl_course c ON cm.course = c.id
JOIN mdl_assign_course ac ON ac.course_id = c.id
JOIN mdl_modules m ON cm.module = m.id";

		$where = "cmc.completionstate <> 2 AND ac.university_id = ?
GROUP BY c.id";

		$params = [];

		$params[] = $SESSION->university_id;

		return [$fields, $from, $where, $params];

	}

	private function get_count_sql() {

		global $SESSION;
		$now = time();

		$sql = "SELECT
COUNT(DISTINCT(c.id))
FROM mdl_course_modules_completion cmc
LEFT JOIN mdl_course_modules_completion cmc1 ON cmc1.userid = cmc.userid AND cmc1.timemodified < $now -15*24*60*60 AND cmc1.timemodified > $now -30*24*60*60
LEFT JOIN mdl_course_modules_completion cmc2 ON cmc2.userid = cmc.userid AND cmc2.timemodified < $now - 30*24*60*60 -1 AND cmc2.timemodified > $now -60*24*60*60
LEFT JOIN mdl_course_modules_completion cmc3 ON cmc3.userid = cmc.userid AND cmc3.timemodified < $now - 60*24*60*60 -1
JOIN mdl_user u ON u.id = cmc.userid
JOIN mdl_course_modules cm ON cmc.coursemoduleid = cm.id
JOIN mdl_course c ON cm.course = c.id
JOIN mdl_assign_course ac ON ac.course_id = c.id
JOIN mdl_modules m ON cm.module = m.id
WHERE cmc.completionstate <> 2 AND ac.university_id = $SESSION->university_id";

		$params = [];

		$params[] = $SESSION->university_id;

		return [$sql, $params];
	}

}

$sqltable = new non_competent_subject_table('non_competent_subject_table', $PAGE->url);

echo $OUTPUT->header();
$sqltable->out(10, false);
echo $OUTPUT->footer();
