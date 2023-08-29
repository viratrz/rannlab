<?php

namespace local_dashboard\helper;

class pending_assessment {


  public static function assessment_due_admin() {
    global $DB;
    $dueover = 0;
    $due1week = 0;
    $due2week = 0;
    $quizsql = "SELECT group_concat(concat(q.id,':',q.timeclose)) as quiz,c.id as course from {quiz} q 
    left join {course} c on q.course=c.id 
    where c.visible=1 and q.timeclose<>0 group by c.id";

    $quizdatas = $DB->get_records_sql($quizsql);

    foreach ($quizdatas as $quizdata) {
      $quizes = explode(',', $quizdata->quiz);
      foreach ($quizes as $quiz) {
        list($quizid, $quizendtime) = explode(':', $quiz);

        $countsql = "Select count(DISTINCT ue.userid) as count from {user_enrolments} ue left join {enrol} e on e.id=ue.enrolid 
                                 where ue.status=0 and e.status=0 and e.courseid=? 
                                    and ue.userid not in (Select qa.userid from {quiz_attempts} qa where qa.quiz=?);";
        $count = $DB->get_record_sql($countsql, [$quizdata->course, $quizid]);


        if ($quizendtime < time()) {
          $dueover += $count->count;
        } elseif (time() < $quizendtime && $quizendtime <= time() + WEEKSECS) {
          $due1week += $count->count;
        } elseif (time() + WEEKSECS < $quizendtime && $quizendtime <= time() + 2 * WEEKSECS) {
          $due2week += $count->count;
        }
      }
    }

    return [$dueover,$due1week,$due2week];
  }

  public static function assessment_due_trainer()
  {
    global $DB,$SESSION;
    $universityid = $SESSION->university_id;
    $dueover = 0;
    $due1week = 0;
    $due2week = 0;
    if($universityid) {
      $quizsql = "SELECT group_concat(concat(q.id,':',q.timeclose)) as quiz,c.id as course from {quiz} q 
    left join {course} c on q.course=c.id 
    left join {school} s on s.coursecategory=c.category  
    where s.id =? and c.visible=1 and q.timeclose<>0 group by c.id";
      $quizdatas = $DB->get_records_sql($quizsql, [$universityid]);
      foreach ($quizdatas as $quizdata) {
        $quizes = explode(',', $quizdata->quiz);
        foreach ($quizes as $quiz) {
          list($quizid, $quizendtime) = explode(':', $quiz);

          $countsql = "Select count(DISTINCT ue.userid) as count from {user_enrolments} ue left join {enrol} e on e.id=ue.enrolid 
                                 where ue.status=0 and e.status=0 and e.courseid=? 
                                    and ue.userid not in (Select qa.userid from {quiz_attempts} qa where qa.quiz=?);";
          $count = $DB->get_record_sql($countsql, [$quizdata->course, $quizid]);


          if ($quizendtime < time()) {
            $dueover += $count->count;
          } elseif (time() < $quizendtime && $quizendtime <= time() + WEEKSECS) {
            $due1week += $count->count;
          } elseif (time() + WEEKSECS < $quizendtime && $quizendtime <= time() + 2 * WEEKSECS) {
            $due2week += $count->count;
          }
        }
      }
    }

    return [$dueover,$due1week,$due2week];
  }

  public static function assessment_due_rtoadmin()
  {
    global $DB,$SESSION;
    $universityid = $SESSION->university_id;
    $dueover = 0;
    $due1week = 0;
    $due2week = 0;
    if($universityid) {
      $quizsql = "SELECT group_concat(concat(q.id,':',q.timeclose)) as quiz,c.id as course from {quiz} q 
    left join {course} c on q.course=c.id 
    left join {school} s on s.coursecategory=c.category  
    where s.id =? and c.visible=1 and q.timeclose<>0 group by c.id";
      $quizdatas = $DB->get_records_sql($quizsql, [$universityid]);
      foreach ($quizdatas as $quizdata) {
        $quizes = explode(',', $quizdata->quiz);
        foreach ($quizes as $quiz) {
          list($quizid, $quizendtime) = explode(':', $quiz);

          $countsql = "Select count(DISTINCT ue.userid) as count from {user_enrolments} ue left join {enrol} e on e.id=ue.enrolid 
                                 where ue.status=0 and e.status=0 and e.courseid=? 
                                    and ue.userid not in (Select qa.userid from {quiz_attempts} qa where qa.quiz=?);";
          $count = $DB->get_record_sql($countsql, [$quizdata->course, $quizid]);


          if ($quizendtime < time()) {
            $dueover += $count->count;
          } elseif (time() < $quizendtime && $quizendtime <= time() + WEEKSECS) {
            $due1week += $count->count;
          } elseif (time() + WEEKSECS < $quizendtime && $quizendtime <= time() + 2 * WEEKSECS) {
            $due2week += $count->count;
          }
        }
      }
    }

    return [$dueover,$due1week,$due2week];
  }

  public static function assessment_due_student() {
    global $USER,$DB;

    $dueover = 0;
    $due1week = 0;
    $due2week = 0;

    $quizsql = "SELECT DISTINCT c.id as course,group_concat(concat(q.id,':',q.timeclose)) as quiz from {quiz} q 
    left join {course} c on q.course=c.id 
    left join {enrol} e on e.courseid=c.id 
    left join {user_enrolments} ue on ue.enrolid=e.id
    where ue.status=0 and c.visible=1 and e.status=0 and ue.userid=?
    group by c.id";

    $quizdatas = $DB->get_records_sql($quizsql, [$USER->userid]);

    foreach ($quizdatas as $quizdata) {
      $quizes = explode(',', $quizdata->quiz);
      foreach ($quizes as $quiz) {
        list($quizid, $quizendtime) = explode(':', $quiz);

        $countsql = "Select count(DISTINCT ue.userid) as count from {user_enrolments} ue left join {enrol} e on e.id=ue.enrolid 
                                 where ue.status=0 and e.status=0 and e.courseid=? 
                                    and ue.userid not in (Select qa.userid from {quiz_attempts} qa where qa.quiz=?);";
        $count = $DB->get_record_sql($countsql, [$quizdata->course, $quizid]);


        if ($quizendtime < time()) {
          $dueover += $count->count;
        } elseif (time() < $quizendtime && $quizendtime <= time() + WEEKSECS) {
          $due1week += $count->count;
        } elseif (time() + WEEKSECS < $quizendtime && $quizendtime <= time() + 2 * WEEKSECS) {
          $due2week += $count->count;
        }
      }
    }

    return [$dueover,$due1week,$due2week];
  }

  static function assessment_markingdue_rtoadmin()
  {
    global $DB, $SESSION;
    $universityid = $SESSION->university_id;
    $dueover = 0;
    $due1week = 0;
    $due2week = 0;
    if ($universityid) {
      $quizsql = "SELECT group_concat(concat(q.id,':',q.timeclose)) as quiz,c.id as course from {quiz} q 
    left join {course} c on q.course=c.id 
    left join {school} s on s.coursecategory=c.category  
    where s.id =? and c.visible=1 and q.timeclose<>0 group by c.id";
      $quizdatas = $DB->get_records_sql($quizsql, [$universityid]);

      foreach ($quizdatas as $quizdata) {
        $quizes = explode(',', $quizdata->quiz);
        foreach ($quizes as $quiz) {
          list($quizid, $quizendtime) = explode(':', $quiz);

          $countsql = "Select count(DISTINCT ue.userid) as count from {user_enrolments} ue 
    left join {enrol} e on e.id=ue.enrolid 
    left join {quiz_attempts} qa on ue.userid=qa.userid
                                 where ue.status=0 
                                   and e.status=0 
                                   and e.courseid=? 
                                   and qa.quiz=? 
                                   and qa.userid NOT in (SELECT qg.userid from {quiz_grades} qg WHERE qg.quiz=?);";
          $count = $DB->get_record_sql($countsql, [$quizdata->course, $quizid, $quizid]);


          if ($quizendtime < time()) {
            $dueover += $count->count;
          } elseif (time() < $quizendtime && $quizendtime <= time() + WEEKSECS) {
            $due1week += $count->count;
          } elseif (time() + WEEKSECS < $quizendtime && $quizendtime <= time() + 2 * WEEKSECS) {
            $due2week += $count->count;
          }
        }
      }

    }
    return [$dueover,$due1week,$due2week];
  }

  static function assessment_markingdue_trainer()
  {
    global $DB, $SESSION;
    $universityid = $SESSION->university_id;
    $dueover = 0;
    $due1week = 0;
    $due2week = 0;
    if ($universityid) {
      $quizsql = "SELECT group_concat(concat(q.id,':',q.timeclose)) as quiz,c.id as course from {quiz} q 
    left join {course} c on q.course=c.id 
    left join {school} s on s.coursecategory=c.category  
    where s.id =? and c.visible=1 and q.timeclose<>0 group by c.id";
      $quizdatas = $DB->get_records_sql($quizsql, [$universityid]);

      foreach ($quizdatas as $quizdata) {
        $quizes = explode(',', $quizdata->quiz);
        foreach ($quizes as $quiz) {
          list($quizid, $quizendtime) = explode(':', $quiz);

          $countsql = "Select count(DISTINCT ue.userid) as count from {user_enrolments} ue 
    left join {enrol} e on e.id=ue.enrolid 
    left join {quiz_attempts} qa on ue.userid=qa.userid
                                 where ue.status=0 
                                   and e.status=0 
                                   and e.courseid=? 
                                   and qa.quiz=? 
                                   and qa.userid NOT in (SELECT qg.userid from {quiz_grades} qg WHERE qg.quiz=?);";
          $count = $DB->get_record_sql($countsql, [$quizdata->course, $quizid, $quizid]);


          if ($quizendtime < time()) {
            $dueover += $count->count;
          } elseif (time() < $quizendtime && $quizendtime <= time() + WEEKSECS) {
            $due1week += $count->count;
          } elseif (time() + WEEKSECS < $quizendtime && $quizendtime <= time() + 2 * WEEKSECS) {
            $due2week += $count->count;
          }
        }
      }

    }
    return [$dueover,$due1week,$due2week];
  }

}
