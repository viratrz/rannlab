<?php
namespace local_dashboard\table;

use html_writer;
use moodle_url;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/tablelib.php');

class assessment_markingdue_rtoadmin extends \table_sql {

  private $quizes;

  function __construct($uniqueid)
  {
    global $DB,$SESSION;

    parent::__construct($uniqueid);

//    $this->define_baseurl(new \moodle_url('/local/dashboard/assessment_due.php'));

    $universityid = $SESSION->university_id;
    $quizids =[];
    $courseids = [];
    $quizes = [];

    $quizsql = "SELECT q.id, q.timeclose, c.id as course from {quiz} q 
    left join {course} c on q.course=c.id 
    left join {school} s on s.coursecategory=c.category  
    where s.id =? and c.visible=1 and q.timeclose<>0 group by c.id";

    $quizdatas = $DB->get_records_sql($quizsql,[$universityid]);

    foreach ($quizdatas as $quizdata) {
      $quizids[] = $quizdata->id;
      $courseids[] = $quizdata->course;
      if(empty($quizes)){
        $quizes = [$quizdata->course=>['id'=>$quizdata->id,'timeclose'=>$quizdata->timeclose,'course'=>$quizdata->course]];
      } elseif(isset($quizes[$quizdata->course])) {
        if ($quizdata->timeclose<$quizes[$quizdata->course]['timeclose']){
          $quizes[$quizdata->course]['timeclose'] = $quizdata->timeclose;
        }
      } else {
        $quizes = $quizes+[$quizdata->course=>['id'=>$quizdata->id,'timeclose'=>$quizdata->timeclose,'course'=>$quizdata->course]];
      }
    }
    $this->quizes = $quizes;
    list($courseinsql, $courseinsqlparam) = $DB->get_in_or_equal($courseids);
    list($quizinsql, $quizinsqlparam) = $DB->get_in_or_equal($quizids);

    $params = array_merge($courseinsqlparam, $quizinsqlparam, $quizinsqlparam);

    $fields = "ue.id, ue.userid as userid, e.courseid, q.id as quiz";
    $from = " {user_enrolments} ue left join {enrol} e on e.id=ue.enrolid
                left join {quiz} q on q.course=e.courseid 
                left join {quiz_attempts} qa on ue.userid=qa.userid";
    $where = " ue.status=0 
                                   and e.status=0 
                                   and e.courseid $courseinsql 
                                   and qa.quiz $quizinsql 
                                   and not exists (SELECT qg.userid, qg.quiz from {quiz_grades} qg WHERE qg.quiz $quizinsql and ue.userid=qg.userid and q.id=qg.quiz)";
    $this->set_sql($fields,$from,$where,$params);

    $countsql = "SELECT COUNT(ue.id) FROM $from WHERE $where";
    $this->set_count_sql($countsql,$params);

    $columns = ['user','course','duedate'];

    $this->define_columns($columns);
    $this->define_headers($columns);
    $this->is_collapsible = false;
  }
  function col_user($data){
    // If we reach that point new users logs have been generated since the last users db query.
    $userfieldsapi = \core_user\fields::for_name();
    $fields = $userfieldsapi->get_sql('', false, '', '', false)->selects;
    $user = \core_user::get_user($data->userid, $fields);

    return html_writer::link(new moodle_url('/user/view.php', ['id'=>$data->userid]), fullname($user));
  }

  function col_course($data)
  {
    return course_get_url($data->courseid);
  }

  function col_duedate($data)
  {
    $quizes = $this->quizes;
    return userdate($quizes[$data->courseid]['timeclose']);
  }
}
